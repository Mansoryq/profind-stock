<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function setLocale(Request $request, $locale)
    {
        // Праоверяем, поддерживается ли данная локль
        $supportedLocales = config('app.supported_locales', ['ru', 'kk']);

        if (!in_array($locale, $supportedLocales)) {
            return redirect()->back()->with('error', 'Неподдерживаемый язык');
        }

        // Устанавливаем локаль в сессии
        Session::put('locale', $locale);

        return redirect()->back()->with('success', 'Язык успешно изменен');
    }
}
