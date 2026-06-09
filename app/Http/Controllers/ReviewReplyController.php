<?php

namespace App\Http\Controllers;

use App\Mail\sendReviewReplyMail;
use App\Models\Notification;
use App\Models\Review;
use App\Models\ReviewReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReviewReplyController extends Controller
{
    public function store(Request $request, Review $review)
    {
        $data = $request->validate([
            'noi_dung' => 'required|string|max:2000',
        ]);

        $reply = ReviewReply::create([
            'ma_phan_hoi' => 'temp',
            'ma_danh_gia' => $review->ma_danh_gia,
            'ma_admin' => Auth::id(),
            'noi_dung' => $data['noi_dung'],
            'lich_su_phan_hoi' => null,
            'is_updated' => false,
            'trang_thai' => 'active',
        ]);
        $reply->ma_phan_hoi = (string) $reply->_id;
        $reply->save();

        $this->sendReplyMail($review, $reply);
        $this->createReplyNotification($review);

        if ($request->wantsJson()) {
            return response()->json($reply, 201);
        }

        return back()->with('success', 'Đã phản hồi đánh giá');
    }

    public function update(Request $request, Review $review, ReviewReply $reviewReply)
    {
        if ($reviewReply->ma_danh_gia !== $review->ma_danh_gia) {
            abort(404);
        }

        $data = $request->validate([
            'noi_dung' => 'required|string|max:2000',
        ]);

        $replyHistory = $reviewReply->lich_su_phan_hoi ?? [];
        $replyHistory = array_is_list($replyHistory) ? $replyHistory : [$replyHistory];
        $replyHistory[] = [
            'noi_dung' => $reviewReply->noi_dung,
            'ngay_sua' => now()->toISOString(),
        ];

        $reviewReply->lich_su_phan_hoi = $replyHistory;
        $reviewReply->noi_dung = $data['noi_dung'];
        $reviewReply->is_updated = true;
        $reviewReply->save();

        $this->sendReplyMail($review, $reviewReply, true);
        $this->createReplyNotification($review, true);

        if ($request->wantsJson()) {
            return response()->json($reviewReply);
        }

        return back()->with('success', 'Đã cập nhật phản hồi đánh giá');
    }

    private function sendReplyMail(Review $review, ReviewReply $reply, bool $isUpdated = false): void
    {
        $review->loadMissing(['user', 'product']);
        $email = $review->user?->email;

        if (!$email) {
            return;
        }

        Mail::to($email)->send(new sendReviewReplyMail($review, $reply, $isUpdated));
    }

    private function createReplyNotification(Review $review, bool $isUpdated = false): void
    {
        if (!$review->ma_nguoi_dung) {
            return;
        }

        $review->loadMissing('product');
        $productName = trim((string) ($review->product?->ten_san_pham ?? '') . ' ' . (string) ($review->ten_bien_the ?? ''));
        $productName = $productName !== '' ? $productName : 'sản phẩm bạn đã đánh giá';

        if ($review->ma_san_pham) {
            $routeParams = ['ma_san_pham' => $review->ma_san_pham];

            if ($review->ma_bien_the) {
                $routeParams['ma_bien_the'] = $review->ma_bien_the;
            }

            $url = route('home.product_detail', $routeParams, false);
        } else {
            $url = $review->ma_don_hang ? '/orders/' . $review->ma_don_hang : '/user';
        }

        $notification = Notification::create([
            'ma_nguoi_dung' => $review->ma_nguoi_dung,
            'tieu_de' => $isUpdated ? 'Cập nhật phản hồi đánh giá' : 'VNTech đã phản hồi đánh giá',
            'noi_dung' => $isUpdated
                ? 'Phản hồi cho đánh giá về ' . $productName . ' đã được cập nhật.'
                : 'VNTech đã phản hồi đánh giá của bạn về ' . $productName . '.',
            'loai' => 'review_reply',
            'duong_dan' => $url,
            'da_doc' => false,
        ]);
        $notification->ma_thong_bao = $notification->_id;
        $notification->save();
    }
}
