<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
class LocalizationController extends Controller
{
    public function change($locale)
    {
        if (in_array($locale, ['uz', 'ru', 'en'])) {
            Session::put('locale', $locale);
        }

        return redirect()->back();
    }
}
