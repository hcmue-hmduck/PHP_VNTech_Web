<?php

namespace App\Http\Controllers;

use App\Ai\Agents\VnTechAssistant;
use App\Models\AgentConversation;
use App\Models\AgentConversationMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Contracts\ConversationStore;
use Throwable;

class AiController extends Controller
{
    // POST /chat
    public function chat(Request $request)
    {
        set_time_limit(120);
        $data = $request->validate([
            'message' => 'required|string',
            'conversation_id' => 'nullable|string|uuid',
        ]);
        try {
            $user = Auth::user() ?: (object) [
                'id' => $request->session()->getId() ?: 'guest_' . md5($request->ip() ?? '')
            ];

            $needsFreshData = $this->needsFreshData($data['message']);

            $agent = $needsFreshData
                ? VnTechAssistant::makeWithRequiredTool()
                : VnTechAssistant::make();

            if ($conversationId = $data['conversation_id']) {
                $agent->continue($conversationId, as: $user);
            } else {
                $agent->forUser($user);
            }

            $response = $agent->prompt($data['message']);

            $message = $this->stripThinking((string) $response);

            return response()->json([
                'success'         => true,
                'message'         => $message,
                'conversation_id' => $response->conversationId ?? null,
            ]);
        } catch (Throwable $e) {
            Log::error('Chatbot error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            console_log(['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra trong quá trình xử lý tin nhắn của bạn.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        } finally {
            // Giải phóng memory sau khi xử lý
            unset($agent, $response);
            gc_collect_cycles();
        }
    }

    // GET chat/history
    public function history(Request $request, ConversationStore $store)
    {
        $data = $request->validate([
            'conversation_id' => 'nullable|string',
        ]);

        try {
            $user = Auth::user() ?: (object) [
                'id' => $request->session()->getId() ?: 'guest_' . md5($request->ip() ?? '')
            ];

            $conversationId = $data['conversation_id'];

            if (!$conversationId) {
                $conversationId = $store->latestConversationId($user->id);
            }

            if ($conversationId) {
                $isDeleted = AgentConversation::where('_id', $conversationId)
                    ->where('is_deleted', true)
                    ->exists();

                if ($isDeleted) {
                    $conversationId = null;
                }
            }

            // Nếu user này chưa từng chat
            if (!$conversationId) {
                return response()->json([
                    'success' => true,
                    'messages' => [],
                    'conversation_id' => null
                ]);
            }

            $messages = AgentConversationMessage::where('conversation_id', $conversationId)
                ->orderBy('created_at', 'asc')
                ->get(['role', 'content'])
                ->map(function ($msg) {
                    if ($msg->role === 'assistant') {
                        $msg->content = $this->stripThinking($msg->content ?? '');
                    }
                    return $msg;
                });

            return response()->json([
                'success' => true,
                'messages' => $messages,
                'conversation_id' => $conversationId
            ]);
        } catch (Throwable $e) {
            Log::error('Chatbot history loading error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Không thể tải lịch sử tin nhắn.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    // POST /chat/clear
    public function clear(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => 'required|string',
        ]);

        try {
            AgentConversation::where('_id', $data['conversation_id'])->update([
                'is_deleted' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa lịch sử trò chuyện.'
            ]);
        } catch (Throwable $e) {
            Log::error('Chatbot clear error: ' . $e->getMessage());

            console_log(['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa lịch sử trò chuyện.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    private function stripThinking(string $text): string
    {
        $tag = '</think>';
        $pos = strrpos($text, $tag); // tìm vị trí CUỐI CÙNG
        if ($pos !== false) {
            $text = substr($text, $pos + strlen($tag));
        }
        return trim($text);
    }

    private function needsFreshData(string $message): bool
    {
        $message = mb_strtolower(trim($message));

        if ($message === '') {
            return false;
        }

        $freshDataKeywords = [
            // Hành động yêu cầu kiểm tra / làm mới dữ liệu
            'kiểm tra',
            'kiem tra',
            'check',
            'xem lại',
            'xem lai',
            'cập nhật',
            'cap nhat',

            // Ngữ cảnh thời điểm hiện tại
            'hiện tại',
            'hien tai',
            'bây giờ',
            'bay gio',
            'đang',
            'dang',

            // Tồn kho / số lượng sản phẩm
            'còn không',
            'con khong',
            'còn hàng',
            'con hang',
            'tồn kho',
            'ton kho',
            'số lượng',
            'so luong',

            // Giá sản phẩm
            'giá',
            'gia',
            'bao nhiêu',
            'bao nhieu',

            // Tìm kiếm / xem danh sách sản phẩm
            'tìm',
            'tim',
            'tìm kiếm',
            'tim kiem',
            'xem',
            'liệt kê',
            'liet ke',
            'có loại nào',
            'co loai nao',
            'có mẫu nào',
            'co mau nao',

            // Danh mục / thương hiệu
            'danh mục',
            'danh muc',
            'thương hiệu',
            'thuong hieu',
            'hãng',
            'hang',

            // Nhu cầu mua hàng
            'mua',
            'đặt mua',
            'dat mua',
            'đặt hàng',
            'dat hang',

            // Đơn hàng
            'đơn hàng',
            'don hang',
            'order',
            'đơn của tôi',
            'don cua toi',
            'trạng thái đơn',
            'trang thai don',
            'theo dõi đơn',
            'theo doi don',

            // Trạng thái xử lý đơn / giao hàng
            'giao hàng',
            'giao hang',
            'vận chuyển',
            'van chuyen',
            'ship',
            'tracking',

            // Chính sách / hỗ trợ
            'chính sách',
            'chinh sach',
            'hỗ trợ',
            'ho tro',
            'liên hệ',
            'lien he',
            'hotline',
            'số điện thoại',
            'so dien thoai',
            'email',
            'địa chỉ',
            'dia chi',
            'giờ làm việc',
            'gio lam viec',
            'thời gian làm việc',
            'thoi gian lam viec',
            'faq',
            'hỏi đáp',
            'hoi dap',
            'bảo hành',
            'bao hanh',
            'đổi trả',
            'doi tra',
            'hoàn tiền',
            'hoan tien',
            'bảo mật',
            'bao mat',
            'đồng kiểm',
            'dong kiem',
            'driver',

            // Flash sale / khuyến mãi
            'flash sale',
            'khuyến mãi',
            'khuyen mai',
            'giảm giá',
            'giam gia',
            'voucher',
            'mã giảm',
            'ma giam',
            'ưu đãi',
            'uu dai',
            'sale',
        ];

        foreach ($freshDataKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return true;
            }
        }

        return false;
    }
}
