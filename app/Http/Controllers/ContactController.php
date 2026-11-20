<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Mail\ContactMessageMail;
use App\Models\Category;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('contact', compact('categories'));
    }

    public function store(StoreContactRequest $request)
    {
        $contactMessage = ContactMessage::create([
            ...$request->validated(),
            'ip_address' => $request->ip(),
        ]);

        Mail::to(config('mail.contact_address'))
            ->queue(new ContactMessageMail($contactMessage));

        return redirect()
            ->route('contact')
            ->with('success', __('Xabaringiz yuborildi. Tez orada siz bilan bog‘lanamiz.'));
    }
}
