<?php

namespace App\Ai\Tools;

use App\Models\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetProductDetailsTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Lấy thông tin chi tiết của một sản phẩm cụ thể bằng mã sản phẩm (ma_san_pham). Bao gồm mô tả chi tiết, thông số kỹ thuật chung, các phiên bản biến thể (variants), giá bán và số lượng tồn kho.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $maSanPham = $request['ma_san_pham'];
        if (empty($maSanPham)) {
            return 'Vui lòng cung cấp mã sản phẩm.';
        }

        // Tìm sản phẩm và các biến thể đi kèm
        $product = Product::with('variants')->where('ma_san_pham', $maSanPham)->first();

        if (!$product) {
            return "Không tìm thấy sản phẩm với mã: '{$maSanPham}'.";
        }

        $product->variants->each(function ($variant) use ($product) {
            $variant->setRelation('product', $product);
        });

        return $product->toJson(JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ma_san_pham' => $schema->string()->required()->description('Mã sản phẩm cần lấy thông tin chi tiết')
        ];
    }
}
