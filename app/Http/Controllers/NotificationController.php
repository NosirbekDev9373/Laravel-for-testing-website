<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return view('notifications.index');
    }

    public function read(string $id)
    {
        auth()->user()->notifications->find($id)->markAsRead();
        return back();
    }

    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}
