<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class Brands_CategoriesAdminController extends Controller
{
    public function viewBrandsCategories() {
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        return view('adminUI.brands_categories', compact('brands', 'categories'));
    }

    public function storeCreateBrand(Request $request) {
        $data = $request->validate([
            'ma_thuong_hieu'  => 'nullable|string',
            'ten_thuong_hieu' => 'required|string',
            'mo_ta'           => 'nullable|string',
            'logo_url'        => 'nullable|image|max:5120',
            'trang_thai'      => 'required|in:active,inactive,delete',
        ]);

        $brand = Brand::create($data);
        $brand->ma_thuong_hieu = $brand->_id;
        $brand->update();

        return redirect()->back()->with('success', 'Tạo brands thành công!');
    }

    public function storeCreateCategory(Request $request) {
        $data = $request->validate([
            'ma_danh_muc'       => 'nullable|string',
            'ma_danh_muc_cha'   => 'nullable|string',
            'ten_danh_muc'      => 'required|string',
            'logo_url'          => 'nullable|image|max:5120',
            'trang_thai'        => 'required|in:active,inactive,delete',
        ]);

        $category = Category::create($data);
        $category->ma_danh_muc = $category->_id;
        $category->update();

        return redirect()->back()->with('success', 'Tạo category thành công!');
    }
}
