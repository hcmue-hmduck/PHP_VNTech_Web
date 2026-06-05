<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // GET /products/{ma_san_pham}/reviews
    public function index(Request $request, string $ma_san_pham)
    {
        $query = Review::where('ma_san_pham', $ma_san_pham)
            ->where('trang_thai', 'active')
            ->with(['user:ma_nguoi_dung,ho_ten,avatar_url', 'product:ma_san_pham,ten_san_pham'])
            ->select(['ma_san_pham', 'ma_nguoi_dung', 'ten_bien_the', 'so_sao', 'noi_dung', 'danh_sach_anh', 'video', 'is_anonymous', 'created_at', 'is_updated']);

        if ($request->boolean('co_media')) {
            $query->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereNotNull('danh_sach_anh')
                        ->where('danh_sach_anh', '!=', '[]')
                        ->where('danh_sach_anh', '!=', '');
                })
                    ->orWhere(function ($query) {
                        $query->whereNotNull('video')
                            ->where('video', '!=', '');
                    });
            });
        }

        if ($request->boolean('co_binh_luan')) {
            $query->whereNotNull('noi_dung')
                ->where('noi_dung', '!=', '');
        }

        if ($request->filled('so_sao')) {
            $rating = (int) $request->query('so_sao');

            if ($rating >= 1 && $rating <= 5) {
                $query->where('so_sao', $rating);
            }
        }

        $reviews = $query->latest()->paginate(10);
        $reviews->getCollection()->transform(function ($review) {
            $review->ten_hien_thi = trim((string) ($review->product?->ten_san_pham ?? '') . ' ' . (string) ($review->ten_bien_the ?? ''));

            return $review;
        });

        return response()->json($reviews);
    }

    // GET /orders/{ma_don_hang}/reviews
    public function byOrder(string $ma_don_hang)
    {
        $reviews = Review::where('ma_don_hang', $ma_don_hang)
            ->where('ma_nguoi_dung', Auth::id())
            ->where('trang_thai', 'active')
            ->get()
            ->map(function ($review) {
                $review->can_update = !($review->is_updated ?? false)
                    && $review->created_at?->greaterThanOrEqualTo(now()->subDays(30));

                return $review;
            })
            ->keyBy('ma_chi_tiet_don_hang');

        return response()->json($reviews);
    }

    // POST /reviews
    public function store(Request $request)
    {
        $data = $request->validate([
            'ma_don_hang' => 'required|string',
            'ma_chi_tiet_don_hang' => 'required|string',
            'so_sao' => 'required|integer|min:1|max:5',
            'noi_dung' => 'nullable|string|max:2000',
            'is_anonymous' => 'nullable|boolean',
        ]);

        $request->validate([
            'danh_sach_hinh_anh' => 'nullable|array|max:5',
            'danh_sach_hinh_anh.*' => 'image|max:5120',
            'video_danh_gia' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo|max:51200',
        ], [
            'danh_sach_hinh_anh.max' => 'Chỉ được chọn tối đa 5 hình ảnh'
        ]);

        $data['so_sao'] = (int) $data['so_sao'];
        $orderInfo = OrderItem::where('ma_chi_tiet_don_hang', $data['ma_chi_tiet_don_hang'])
            ->with('variant:ma_bien_the,ma_san_pham')
            ->select(['ma_bien_the', 'ten_bien_the', 'ma_san_pham'])
            ->first();

        $data['ma_nguoi_dung'] = Auth::id();
        $data['ma_san_pham'] = $orderInfo->variant?->ma_san_pham;
        $data['ma_bien_the'] = $orderInfo->ma_bien_the;
        $data['ten_bien_the'] = $orderInfo->ten_bien_the;
        $data['trang_thai'] = 'active';
        $data['is_anonymous'] = $request->boolean('is_anonymous');


        $review = Review::create($data);
        $review->ma_danh_gia = (string) $review->_id;
        $review->save();

        if ($request->hasFile('danh_sach_hinh_anh')) {
            $images = [];
            foreach ($request->file('danh_sach_hinh_anh') as $file) {
                $upload = cloudinary()->uploadApi()->upload($file->getRealPath(), [
                    'folder' => "vntech/reviews/" . ($review->ma_danh_gia)
                ]);

                $images[] = [
                    'url' => $upload['secure_url'],
                    'public_id' => $upload['public_id'],
                ];
            }

            $review->danh_sach_anh = $images;
        }

        if ($request->hasFile('video_danh_gia')) {
            $file = $request->file('video_danh_gia');
            $upload = cloudinary()->uploadApi()->upload($file->getRealPath(), [
                'folder' => "vntech/reviews/" . ($review->ma_danh_gia),
                'resource_type' => 'video'
            ]);

            $review->video = [
                'url' => $upload['secure_url'],
                'public_id' => $upload['public_id'],
            ];
        }

        $review->save();
        $this->syncProductReviewStats($review->ma_san_pham);

        return back()->with('success', 'Đã thêm đánh giá');
    }

    // PUT /reviews/{review}
    public function update(Request $request, Review $review)
    {
        if ($review->ma_nguoi_dung !== Auth::id()) {
            return back()->withErrors([
                'review' => 'Bạn không có quyền sửa đánh giá này.',
            ]);
        }

        $canUpdate = !($review->is_updated ?? false)
            && $review->created_at?->greaterThanOrEqualTo(now()->subDays(30));

        if (!$canUpdate) {
            return back()->withErrors([
                'review' => 'Đánh giá này đã hết thời hạn chỉnh sửa.',
            ]);
        }

        $data = $request->validate([
            'so_sao' => 'required|integer|min:1|max:5',
            'noi_dung' => 'nullable|string|max:2000',
            'is_anonymous' => 'nullable|boolean',
        ]);

        $request->validate([
            'danh_sach_hinh_anh' => 'nullable|array|max:5',
            'danh_sach_hinh_anh.*' => 'image|max:5120',
            'video_danh_gia' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo|max:51200',
            'xoa_hinh_anh_public_ids' => 'nullable|array',
            'xoa_hinh_anh_public_ids.*' => 'string',
            'xoa_video_public_id' => 'nullable|string',
        ], [
            'danh_sach_hinh_anh.max' => 'Chỉ được chọn tối đa 5 hình ảnh'
        ]);
        $data['is_anonymous'] = $request->boolean('is_anonymous');
        $data['so_sao'] = (int) $data['so_sao'];

        $review->fill($data);
        $review->is_updated = true;

        $deleteImagePublicIds = collect($request->input('xoa_hinh_anh_public_ids', [])) // biến array thường thành collection
            ->filter(); // lọc bỏ giá trị falsy

        $currentImages = collect($review->danh_sach_anh ?? []);

        $keptImages = $currentImages
            ->reject(fn($image) => $deleteImagePublicIds->contains($image['public_id'] ?? null))
            ->values() // reset lại index của collection
            ->all(); // đổi collection về array thường

        $newImageFiles = $request->file('danh_sach_hinh_anh', []);

        if (count($keptImages) + count($newImageFiles) > 5) {
            return back()->withErrors([
                'review' => 'Mỗi đánh giá chỉ được có tối đa 5 hình ảnh.',
            ]);
        }

        // Xử lý hình ảnh
        if ($deleteImagePublicIds->isNotEmpty() || $request->hasFile('danh_sach_hinh_anh')) {
            $newImages = [];

            if ($request->hasFile('danh_sach_hinh_anh')) {
                foreach ($newImageFiles as $file) {
                    $upload = cloudinary()->uploadApi()->upload($file->getRealPath(), [
                        'folder' => "vntech/reviews/" . ($review->ma_danh_gia)
                    ]);

                    $newImages[] = [
                        'url' => $upload['secure_url'],
                        'public_id' => $upload['public_id'],
                    ];
                }
            }

            if ($deleteImagePublicIds->isNotEmpty()) {
                // đảm bảo danh sách ảnh cần xoá phải tồn tại trong đúng review
                $deletedImages = $currentImages
                    ->filter(fn($image) => $deleteImagePublicIds->contains($image['public_id'] ?? null));

                foreach ($deletedImages as $image) {
                    cloudinary()->uploadApi()->destroy($image['public_id'], [
                        'resource_type' => 'image',
                    ]);
                }
            }

            $review->danh_sach_anh = array_merge($keptImages, $newImages);
        }

        // Xử lý video
        $deleteVideoPublicId = $request->input('xoa_video_public_id');
        $currentVideoPublicId = $review->video['public_id'] ?? null;


        if ($deleteVideoPublicId && $currentVideoPublicId === $deleteVideoPublicId) {
            cloudinary()->uploadApi()->destroy($deleteVideoPublicId, [
                'resource_type' => 'video',
            ]);

            $review->video = null;
        }

        if ($request->hasFile('video_danh_gia') && $review->video) {
            return back()->withErrors([
                'review' => 'Mỗi đánh giá chỉ được có tối đa 1 video',
            ]);
        }

        if ($request->hasFile('video_danh_gia')) {
            $file = $request->file('video_danh_gia');
            $upload = cloudinary()->uploadApi()->upload($file->getRealPath(), [
                'folder' => "vntech/reviews/" . ($review->ma_danh_gia),
                'resource_type' => 'video'
            ]);

            $review->video = [
                'url' => $upload['secure_url'],
                'public_id' => $upload['public_id'],
            ];
        }

        $review->save();
        $this->syncProductReviewStats($review->ma_san_pham);

        return back()->with('success', 'Đã cập nhật đánh giá');
    }

    private function syncProductReviewStats(?string $ma_san_pham): void
    {
        if (!$ma_san_pham) {
            return;
        }

        $reviews = Review::where('ma_san_pham', $ma_san_pham)
            ->where('trang_thai', 'active')
            ->get(['so_sao']);

        $reviewCount = $reviews->count();
        $totalRating = (int) $reviews->sum(fn($review) => (int) $review->so_sao);

        Product::where('ma_san_pham', $ma_san_pham)->update([
            'tong_so_sao' => $totalRating,
            'so_luot_danh_gia' => $reviewCount,
            'so_sao_trung_binh' => $reviewCount > 0 ? round($totalRating / $reviewCount, 2) : 0,
        ]);
    }
}
