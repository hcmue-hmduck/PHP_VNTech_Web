<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductAdminController extends Controller
{
    public function viewProductAdmin()
    {
        $products = Product::with('variants')->latest()->paginate(10);
        return view('adminUI.productsAdmin', compact('products'));
    }

    public function viewCreateProductAdmin() 
    {
        return view('adminUI.formProductsAdmin');
    }

    public function storeCreateProductAdmin(Request $request) 
    {
        $data = $request->validate([
            'ten_san_pham'              => 'required|string|max:255',
            'ma_thuong_hieu'            => 'required|string|max:100',
            'mo_ta_ngan'                => 'nullable|string|max:500',
            'mo_ta_chi_tiet'            => 'nullable|string',
            'link_anh_dai_dien'         => 'nullable', 
            'trang_thai'                => 'required|in:active,inactive',
            'gia_thap_nhat'             => 'required|numeric|min:0',
            'hinh_anh'                  => 'nullable|array',
            'thong_so_ky_thuat_chung'   => 'nullable|array',
            'thong_tin_them'            => 'nullable|array',
        ]);

        unset($data['variants']);

        $product = Product::create($data);
        $product->ma_san_pham = $product->_id;
        $product->save();

        if ($request->hasFile('link_anh_dai_dien')) {
            $file = $request->file('link_anh_dai_dien');
            try {
                $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => "vntech/products/" . ($product->ma_san_pham)
                ]);
                $product->update(['link_anh_dai_dien' => $upload['secure_url']]);
            } catch (\Exception $e) {
                return back()->withErrors(['cloudinary' => 'Lỗi upload: ' . $e->getMessage()]);
            }
        }

        if ($request->hasFile('hinh_anh')) {
            $galleryImages = [];
            foreach ($request->file('hinh_anh') as $file) {
                try {
                    $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                        'folder' => "vntech/products/" . ($product->ma_san_pham) . "/gallery"
                    ]);
                    $galleryImages[] = $upload['secure_url'];
                } catch (\Exception $e) {
                    return back()->withErrors(['cloudinary' => 'Lỗi upload gallery: ' . $e->getMessage()]);
                }
            }
            $product->update(['hinh_anh' => $galleryImages]);
        }

        if ($request->has('variants')) {
            foreach ($request->variants as $index => $variant) {
                $thuocTinhRaw = $variant['thong_so_ky_thuat_rieng'] ?? [];

                $product_variant = $product->variants()->create([
                    'ma_san_pham' => $product->ma_san_pham,
                    'ten_bien_the' => $variant['ten_bien_the'] ?? null,
                    'gia_ban' => $variant['gia_ban'],
                    'gia_niem_yet' => $variant['gia_niem_yet'],
                    'so_luong_ton_kho' => $variant['so_luong_ton_kho'],
                    'trang_thai' => $variant['trang_thai'],
                    'thong_so_ky_thuat_rieng' => $thuocTinhRaw,
                ]);
                $product_variant->ma_bien_the = $product_variant->_id;
                $product_variant->save();

                
                if ($request->hasFile("variants.$index.link_anh_bien_the")) {
                    $file = $request->file("variants.$index.link_anh_bien_the");
                    try {
                        $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                            'folder' => "vntech/products/" . $product->ma_san_pham . "/variants/" . $product_variant->ma_bien_the
                        ]);
                        $product_variant->update(['link_anh_bien_the' => $upload['secure_url']]);
                    } catch (\Exception $e) {
                        return back()->withErrors(['cloudinary' => 'Lỗi upload: ' . $e->getMessage()]);
                    }
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function viewEditProductAdmin(Product $product) {
        $product_variant = ProductVariant::where('ma_san_pham', $product->ma_san_pham)->get();
        return view('adminUI.formProductsAdmin', compact('product', 'product_variant'));
    }

    public function updateEditProductAdmin(Request $request, Product $product) {
        $data = $request->validate([
            'ten_san_pham'              => 'required|string|max:255',
            'ma_thuong_hieu'            => 'required|string|max:100',
            'mo_ta_ngan'                => 'nullable|string|max:500',
            'mo_ta_chi_tiet'            => 'nullable|string',
            'link_anh_dai_dien'         => 'nullable|image|max:5120', 
            'trang_thai'                => 'required|in:active,inactive',
            'gia_thap_nhat'             => 'required|numeric|min:0',
            'thong_so_ky_thuat_chung'   => 'nullable|array',
            'thong_tin_them'            => 'nullable|array',
            'hinh_anh.*'                => 'nullable|image|max:5120'
        ]);

        if ($request->hasFile('link_anh_dai_dien')) {
            $file = $request->file('link_anh_dai_dien');
            try {
                $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => "vntech/products/{$product->ma_san_pham}"
                ]);
                $data['link_anh_dai_dien'] = $upload['secure_url'];
            } catch (\Exception $e) {
                return back()->withErrors(['cloudinary' => 'Lỗi upload: ' . $e->getMessage()]);
            }
        }

        if ($request->hasFile('hinh_anh')) {
            $gallery = [];
            foreach ($request->file('hinh_anh') as $file) {
                $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => "vntech/products/{$product->ma_san_pham}/gallery"
                ]);
                $gallery[] = $upload['secure_url'];
            }
            $data['hinh_anh'] = $gallery;
        }

        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }
}