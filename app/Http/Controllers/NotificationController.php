<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function getNotification(string $ma_nguoi_dung) {
        $notifications = Notification::where('ma_nguoi_dung', $ma_nguoi_dung,)->latest()->get();
        $unreadCount = $notifications->where('da_doc', false)->count();
        return [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ];
    }

    public function readNotification(?string $ma_thong_bao = null) {
        if ($ma_thong_bao) {
            $notification = Notification::where('ma_thong_bao', $ma_thong_bao)->first();
            if ($notification) {
                $notification->update(['da_doc' => true]);
                if (!empty($notification->duong_dan)) {
                    return redirect($notification->duong_dan);
                }
            }
        }
        else {
            $ma_nguoi_dung = Auth::id();
            if ($ma_nguoi_dung) {
                Notification::where('ma_nguoi_dung', $ma_nguoi_dung)
                    ->where('da_doc', false)
                    ->update(['da_doc' => true]);
            }
        }       
        return redirect()->back();
    }

    public function createNotification(Request $request) {
        $data = $request->validate([
            'ma_nguoi_dung'     => 'required|string',
            'tieu_de'           => 'required|string',
            'noi_dung'          => 'required|string',
            'loai'              => 'required|string',
            'duong_dan'         => 'nullable|string',
            'da_doc'            => 'required|boolean'
        ]);

        $notification = Notification::create($data);
        $notification->ma_thong_bao = $notification->_id;
        $notification->save();

        return redirect()->back();
    }
}
