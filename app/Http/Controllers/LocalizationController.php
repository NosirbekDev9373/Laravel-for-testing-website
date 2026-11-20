<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class LocalizationController extends Controller
{
    public function change($locale)
    {
        if (in_array($locale, ['uz', 'ru', 'en'])) {
            Session::put('locale', $locale);
        }

        $previous = url()->previous();
        $isSameHost = parse_url($previous, PHP_URL_HOST) === request()->getHost();

        return redirect()->to($isSameHost ? $previous : route('main'));
    }
}
