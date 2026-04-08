<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'message' => 'required|string',
        ]);

        try {
            Mail::to('meraukevisit@gmail.com')
                ->send(new ContactMail($request->all()));

            return back()->with('success', 'Pesan berhasil dikirim!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim pesan. Silakan coba lagi.');
        }
    }
}
