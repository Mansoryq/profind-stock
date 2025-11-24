<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Mail\ForgotPasswordMail;

class ForgotPassword extends Component
{
    public $email = '';
    public $sent = false;

    protected $rules = [
        'email' => 'required|email',
    ];

    public function sendResetLink()
    {
        $this->validate();

        // Check if user exists with this email
        $user = \App\Models\User::where('email', $this->email)->first();
        
        if (!$user) {
            $this->addError('email', 'Пользователь с таким email не найден.');
            return;
        }

        try {
            // Generate a reset token
            $token = Str::random(60);
            
            // Store the token in database (you can create a password_resets table)
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $this->email],
                [
                    'email' => $this->email,
                    'token' => $token,
                    'created_at' => now()
                ]
            );

            // Send email
            Mail::to($this->email)->send(new ForgotPasswordMail($user, $token));

            $this->sent = true;
            $this->email = '';
        } catch (\Exception $e) {
            // If email sending fails, still show success for security
            $this->sent = true;
            $this->email = '';
            
            // Log the error for debugging
            Log::error('Failed to send password reset email: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.forgot-password');
    }
}
