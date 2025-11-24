<?php

namespace App\Http\Controllers;

use App\Models\Talent;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class TalentAdviceImportController extends Controller
{
    public function showImportForm()
    {
        return view('admin.talents.import-advice');
    }
    
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        try {
            $file = $request->file('excel_file');
            $filePath = $file->getPathname();
            
            // Load the Excel file
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            
            $highestRow = $worksheet->getHighestRow();
            $talentsUpdated = 0;
            $talentsNotFound = [];
            
            // Start from row 2 assuming row 1 is header
            for ($row = 2; $row <= $highestRow; $row++) {
                $talentName = $worksheet->getCell('A' . $row)->getValue();
                
                if (empty($talentName)) {
                    continue;
                }
                
                // Collect advice data
                $adviceItems = [];
                
                // Check if we have advice columns (B, C for name and description)
                $adviceName = $worksheet->getCell('B' . $row)->getValue();
                $adviceDescription = $worksheet->getCell('C' . $row)->getValue();
                
                if (!empty($adviceName)) {
                    $adviceItems[] = [
                        'name' => $adviceName,
                        'description' => $adviceDescription ?? ''
                    ];
                }
                
                // Check for additional advice columns (D, E, F, G, etc.)
                $colPairs = [
                    ['D', 'E'], // Second advice pair
                    ['F', 'G'], // Third advice pair
                    ['H', 'I'], // Fourth advice pair
                    ['J', 'K']  // Fifth advice pair
                ];
                
                foreach ($colPairs as $colPair) {
                    $name = $worksheet->getCell($colPair[0] . $row)->getValue();
                    $description = $worksheet->getCell($colPair[1] . $row)->getValue();
                    
                    if (!empty($name)) {
                        $adviceItems[] = [
                            'name' => $name,
                            'description' => $description ?? ''
                        ];
                    }
                }
                
                // Find the talent by name
                $talent = Talent::where('name', $talentName)->first();
                
                if ($talent) {
                    // Update the talent with JSON advice
                    $talent->advice = $adviceItems;
                    $talent->save();
                    
                    $talentsUpdated++;
                } else {
                    $talentsNotFound[] = $talentName;
                }
            }
            
            return back()->with([
                'success' => "Successfully updated {$talentsUpdated} talents with advice data.",
                'not_found' => $talentsNotFound,
            ]);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }
}