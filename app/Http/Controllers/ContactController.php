<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendContactMail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên của bạn.',
            'name.max' => 'Họ và tên không được dài quá 255 ký tự.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.max' => 'Địa chỉ email không được dài quá 255 ký tự.',
            'subject.required' => 'Vui lòng nhập chủ đề liên hệ.',
            'subject.max' => 'Chủ đề không được dài quá 255 ký tự.',
            'message.required' => 'Vui lòng nhập nội dung tin nhắn.',
            'message.max' => 'Nội dung tin nhắn không được vượt quá 5000 ký tự.',
        ]);

        $adminEmails = config('mail.adminEmails');

        if($adminEmails) {
            Mail::to($adminEmails)->send(new sendContactMail($validatedData));
        }

        return redirect()->back()->with('success', 'Yêu cầu liên hệ của bạn đã được gửi thành công! VNTech sẽ phản hồi lại bạn sớm nhất.');
    }
}
