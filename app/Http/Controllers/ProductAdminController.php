<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
            'ten_san_pham'      => 'required|string|max:255',
            'slug'              => 'required|string|max:255|unique:products,slug',
            'ma_thuong_hieu'    => 'required|string|max:100',
            'mo_ta_ngan'        => 'nullable|string|max:500',
            'mo_ta_chi_tiet'    => 'nullable|string',
            'link_anh_dai_dien' => 'nullable', 
            'trang_thai'        => 'required|in:active,inactive',
            'gia_thap_nhat'     => 'required|numeric|min:0',
            'hinh_anh'          => 'nullable|array',
            'thuoc_tinh_chung'  => 'nullable|array',
        ]);

        if ($request->hasFile('link_anh_dai_dien')) {
            $file = $request->file('link_anh_dai_dien');
            try {
                $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => "vntech/products/" . ($request->ma_san_pham ?? 'new')
                ]);
                $data['link_anh_dai_dien'] = $upload['secure_url'];
            } catch (\Exception $e) {
                return back()->withErrors(['cloudinary' => 'Lỗi upload: ' . $e->getMessage()]);
            }
        }

        $data['luot_xem'] = 0;
        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function viewEditProductAdmin(Request $request, Product $product) {
        return view('adminUI.formProductsAdmin', compact('product'));
    }

    public function updateEditProductAdmin(Request $request, Product $product) {
        $data = $request->validate([
            'ten_san_pham'      => 'required|string|max:255',
            'slug'              => 'required|string|max:255|unique:products,slug,' . $product->ma_san_pham . ',ma_san_pham',
            'ma_thuong_hieu'    => 'required|string|max:100',
            'mo_ta_ngan'        => 'nullable|string|max:500',
            'mo_ta_chi_tiet'    => 'nullable|string',
            'link_anh_dai_dien' => 'nullable|image|max:5120', 
            'trang_thai'        => 'required|in:active,inactive',
            'gia_thap_nhat'     => 'required|numeric|min:0',
            'thuoc_tinh_chung'  => 'nullable|array',
            'hinh_anh_phu.*'    => 'nullable|image|max:5120'
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

        if ($request->hasFile('hinh_anh_phu')) {
            $gallery = [];
            foreach ($request->file('hinh_anh_phu') as $file) {
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