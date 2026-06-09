<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetSupportPoliciesTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Lấy thông tin chính sách, hỗ trợ và liên hệ của VNTech từ nguồn dữ liệu chính thức: FAQ, bảo hành, đổi trả/hoàn tiền, bảo mật thông tin, vận chuyển/giao nhận, hotline, email, địa chỉ, giờ làm việc. Dùng khi khách hỏi về chính sách, hỗ trợ, liên hệ, hotline, email, địa chỉ, giờ làm việc, bảo hành, đổi trả, hoàn tiền, giao hàng, phí ship, đồng kiểm, bảo mật thông tin hoặc driver/hướng dẫn.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $supportPolicies = config('support_policies');

        if (empty($supportPolicies)) {
            return 'Hiện chưa có dữ liệu chính sách và hỗ trợ của VNTech.';
        }

        $requestedTopics = $this->requestedTopics($request);

        if (empty($requestedTopics)) {
            return json_encode([
                'contact' => $supportPolicies['contact'] ?? [],
                'faqs' => $supportPolicies['faqs'] ?? [],
                'policies' => $supportPolicies['policies'] ?? [],
            ], JSON_UNESCAPED_UNICODE);
        }

        $result = [];

        foreach ($requestedTopics as $topic) {
            if ($topic === 'contact') {
                $result['contact'] = $supportPolicies['contact'] ?? [];
                continue;
            }

            if ($topic === 'faqs') {
                $result['faqs'] = $supportPolicies['faqs'] ?? [];
                continue;
            }

            $result['policies'][$topic] = $supportPolicies['policies'][$topic] ?? [];
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'contact' => $schema->boolean()->nullable()->description('True nếu cần lấy thông tin liên hệ: hotline, email, địa chỉ, giờ làm việc và mạng xã hội.'),
            'faqs' => $schema->boolean()->nullable()->description('True nếu cần lấy câu hỏi thường gặp và hướng dẫn hỗ trợ.'),
            'warranty' => $schema->boolean()->nullable()->description('True nếu cần lấy chính sách bảo hành.'),
            'return' => $schema->boolean()->nullable()->description('True nếu cần lấy chính sách đổi trả và hoàn tiền.'),
            'privacy' => $schema->boolean()->nullable()->description('True nếu cần lấy chính sách bảo mật thông tin.'),
            'shipping' => $schema->boolean()->nullable()->description('True nếu cần lấy chính sách vận chuyển, giao nhận, phí ship và đồng kiểm.'),
        ];
    }

    private function requestedTopics(Request $request): array
    {
        $topics = ['contact', 'faqs', 'warranty', 'return', 'privacy', 'shipping'];

        return array_values(array_filter($topics, fn (string $topic) => $request[$topic] ?? false));
    }
}
