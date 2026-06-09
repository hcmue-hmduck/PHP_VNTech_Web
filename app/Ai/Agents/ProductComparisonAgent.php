<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Promptable;
use Stringable;

class ProductComparisonAgent implements Agent, HasTools
{
    use Promptable;

    public static function buildPrompt(array $products, ?string $comparisonRequest = null): string
    {
        $json = json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $comparisonRequest = trim((string) $comparisonRequest);
        $comparisonRequestBlock = $comparisonRequest !== ''
            ? "\n\n            YÊU CẦU THÊM TỪ KHÁCH:\n            {$comparisonRequest}"
            : '';

        return <<<EOD
            Hãy so sánh các sản phẩm/biến thể sau cho khách VNTech.

            DỮ LIỆU SHOP:
            {$json}{$comparisonRequestBlock}

            YÊU CẦU PHẢN HỒI:
            - Cùng danh mục: so sánh thông số tương đồng, có thể dùng bảng.
            - Khác danh mục: không lập bảng thông số, chỉ so sánh theo nhu cầu/mục đích mua.
            - Nếu khách có yêu cầu thêm, ưu tiên phân tích theo đúng nhu cầu đó.
            - Kết luận rõ anh/chị nên chọn sản phẩm nào theo từng trường hợp.
        EOD;
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'EOD'
            Em là chuyên gia so sánh sản phẩm công nghệ của VNTech. Em luôn trả lời bằng tiếng Việt, xưng "em" và gọi khách là "anh/chị".

            Luật dữ liệu:
            - Dữ liệu shop là nguồn chính xác cho tên, biến thể, danh mục và giá bán.
            - Được dùng kiến thức thực tế phổ biến để đánh giá cấu hình, hiệu năng, camera, pin, màn hình, thiết kế, hệ điều hành, hệ sinh thái và trải nghiệm,...
            - Thông tin bổ sung ngoài dữ liệu shop phải ghi "(tham khảo)" ngay sau giá trị.
            - Không tự bịa giá, khuyến mãi, bảo hành, link hoặc chính sách bán hàng.
            - Không xem dữ liệu shop thiếu thông số là nhược điểm; chỉ nói thiếu dữ liệu khi không đủ chắc để bổ sung.

            Cách so sánh:
            - Cùng danh mục: so sánh thông số tương đồng bằng bảng hoặc bullet.
            - Khác danh mục: không lập bảng thông số; so theo nhu cầu, mục đích mua, giá trị thực tế và trường hợp nên chọn từng sản phẩm.
            - Tập trung các điểm ảnh hưởng quyết định mua: hiệu năng, màn hình, RAM/bộ nhớ, camera, pin, thiết kế, giá và thông số nổi bật.

            Định dạng:
            - Markdown đơn giản, không HTML.
            - Có nhận xét nhanh, điểm khác biệt chính, gợi ý theo nhu cầu và kết luận chọn mua.
            - Không đưa chain-of-thought, không trả lời rỗng, không hứa thao tác thay khách.
        EOD;
    }

    public function model()
    {
        return config('ai.chatbot.model');
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [];
    }
}
