<?php

namespace App\Http\Controllers;

use App\Ai\Agents\ProductComparisonAgent;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class CompareController extends Controller
{
    private const MAX_COMPARE_ITEMS = 3;

    public function variants(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'variant_ids' => ['required', 'array', 'min:1', 'max:' . self::MAX_COMPARE_ITEMS],
            'variant_ids.*' => ['required', 'string', 'distinct'],
        ]);


        $variantIds = array_values($validated['variant_ids']);

        $variants = ProductVariant::query()
            ->select([
                'ma_san_pham',
                'ma_bien_the',
                'ten_bien_the',
                'link_anh_bien_the',
                'gia_ban',
                'thong_so_ky_thuat_rieng',
                'trang_thai',
            ])
            ->with(['product' => function ($query) {
                $query->select([
                    'ma_san_pham',
                    'ten_san_pham',
                    'ma_danh_muc',
                    'link_anh_dai_dien',
                ]);
            }])
            ->whereIn('ma_bien_the', $variantIds)
            ->where('trang_thai', '!=', 'deleted')
            ->get()
            ->sortBy(fn($variant) => array_search((string) $variant->ma_bien_the, $variantIds, true))
            ->values();

        $categoryNames = Category::query()
            ->select(['ma_danh_muc', 'ten_danh_muc'])
            ->whereIn(
                'ma_danh_muc',
                $variants->pluck('product.ma_danh_muc')->filter()->unique()->values()->all()
            )
            ->get()
            ->keyBy('ma_danh_muc');

        return response()->json([
            'success' => true,
            'items' => $variants->map(function ($variant) use ($categoryNames) {
                $product = $variant->product;
                $category = $product ? $categoryNames->get($product->ma_danh_muc) : null;

                return [
                    'ma_bien_the' => (string) $variant->ma_bien_the,
                    'ten_bien_the' => $variant->ten_bien_the,
                    'ten_san_pham' => $product?->ten_san_pham,
                    'ten_danh_muc' => $category?->ten_danh_muc,
                    'gia_ban' => ($variant->gia_ban ?? 0),
                    'link_anh' => $variant->link_anh_bien_the ?: ($product?->link_anh_dai_dien ?? null),
                    'thong_so_rieng' => $variant->thong_so_ky_thuat_rieng ?? [],
                    'url' => $product ? route('viewProductDetail', ['ma_san_pham' => $product->ma_san_pham, 'ma_bien_the' => (string) $variant->ma_bien_the]) : null,
                ];
            })->all(),
        ]);
    }

    public function aiCompare(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'variant_ids' => ['required', 'array', 'min:2', 'max:' . self::MAX_COMPARE_ITEMS],
            'variant_ids.*' => ['required', 'string', 'distinct'],
        ]);

        try {
            $items = $this->comparisonData(array_values($validated['variant_ids']));

            if (count($items) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cần ít nhất 2 sản phẩm hợp lệ để so sánh.',
                ], 422);
            }

            $response = ProductComparisonAgent::make()
                ->prompt(ProductComparisonAgent::buildPrompt($items));

            return response()->json([
                'success' => true,
                'message' => $this->stripThinking((string) $response),
            ]);
        } catch (Throwable $e) {
            Log::error('Product comparison AI error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo nội dung so sánh AI lúc này.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function comparisonData(array $variantIds): array
    {
        $variants = ProductVariant::query()
            ->select([
                'ma_san_pham',
                'ma_bien_the',
                'ten_bien_the',
                'gia_ban',
                'gia_niem_yet',
                'thong_so_ky_thuat_rieng',
                'trang_thai',
            ])
            ->with(['product' => function ($query) {
                $query->select([
                    'ma_san_pham',
                    'ten_san_pham',
                    'ma_danh_muc',
                    'thong_so_ky_thuat_chung',
                ]);
            }])
            ->whereIn('ma_bien_the', $variantIds)
            ->where('trang_thai', '!=', 'deleted')
            ->get()
            ->sortBy(fn($variant) => array_search((string) $variant->ma_bien_the, $variantIds, true))
            ->values();

        $categoryNames = Category::query()
            ->select(['ma_danh_muc', 'ten_danh_muc'])
            ->whereIn(
                'ma_danh_muc',
                $variants->pluck('product.ma_danh_muc')->filter()->unique()->values()->all()
            )
            ->get()
            ->keyBy('ma_danh_muc');

        return $variants->map(function ($variant) use ($categoryNames) {
            $product = $variant->product;
            $category = $product ? $categoryNames->get($product->ma_danh_muc) : null;

            return [
                'ma_bien_the' => (string) $variant->ma_bien_the,
                'ten_san_pham' => $product?->ten_san_pham,
                'ten_bien_the' => $variant->ten_bien_the,
                'danh_muc' => $category?->ten_danh_muc,
                'gia_ban' => (float) ($variant->gia_ban ?? 0),
                'gia_niem_yet' => (float) ($variant->gia_niem_yet ?? 0),
                'thong_so_chung' => $product?->thong_so_ky_thuat_chung ?? [],
                'thong_so_rieng' => $variant->thong_so_ky_thuat_rieng ?? [],
            ];
        })->all();
    }

    private function stripThinking(string $text): string
    {
        $tag = '</think>';
        $pos = strrpos($text, $tag);

        if ($pos !== false) {
            $text = substr($text, $pos + strlen($tag));
        }

        return trim($text);
    }
}
