<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class Brands_CategoriesAdminController extends Controller
{
    public function viewBrandsCategories()
    {
        $brands = Brand::where('trang_thai', '!=', 'deleted')->latest()->paginate(10, ['*'], 'brands_page');
        $categories = Category::where('trang_thai', '!=', 'deleted')->latest()->paginate(10, ['*'], 'categories_page');

        $totalBrandsCount = Brand::where('trang_thai', '!=', 'deleted')->count();
        $activeBrandsCount = Brand::where('trang_thai', 'active')->count();

        $totalCategoriesCount = Category::where('trang_thai', '!=', 'deleted')->count();
        $activeCategoriesCount = Category::where('trang_thai', 'active')->count();

        return view('adminUI.brands_categories', compact(
            'brands',
            'categories',
            'totalBrandsCount',
            'activeBrandsCount',
            'totalCategoriesCount',
            'activeCategoriesCount'
        ));
    }

    public function storeCreateBrand(Request $request)
    {
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

        if ($request->hasFile('logo_url')) {
            $file = $request->file('logo_url');
            try {
                $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => "vntech/brands/" . ($brand->ma_thuong_hieu)
                ]);
                $brand->update(['logo_url' => $upload['secure_url']]);
            } catch (\Exception $e) {
                return back()->withErrors(['cloudinary' => 'Lỗi upload: ' . $e->getMessage()]);
            }
        }
        return redirect()->back()->with('success', 'Tạo brands thành công!');
    }

    public function updateEditBrand(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'ma_thuong_hieu'  => 'nullable|string',
            'ten_thuong_hieu' => 'required|string',
            'mo_ta'           => 'nullable|string',
            'logo_url'        => 'nullable|image|max:5120',
            'trang_thai'      => 'required|in:active,inactive,delete',
        ]);

        $brand->update($data);

        if ($request->hasFile('logo_url')) {
            $file = $request->file('logo_url');
            try {
                $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => "vntech/brands/" . ($brand->ma_thuong_hieu)
                ]);
                $brand->update(['logo_url' => $upload['secure_url']]);
            } catch (\Exception $e) {
                return back()->withErrors(['cloudinary' => 'Lỗi upload: ' . $e->getMessage()]);
            }
        }

        return redirect()->back()->with('success', 'Cập nhật brands thành công!');
    }

    public function storeCreateCategory(Request $request)
    {
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

        if ($request->hasFile('logo_url')) {
            try {
                $file = $request->file('logo_url');
                $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => "vntech/categories/" . ($category->ma_danh_muc)
                ]);
                $category->update(['logo_url' => $upload['secure_url']]);
            } catch (\Exception $e) {
                return back()->withErrors(['cloudinary' => 'Lỗi upload: ' . $e->getMessage()]);
            }
        }

        return redirect()->back()->with('success', 'Tạo category thành công!');
    }

    public function updateEditCategory(Request $request, Category $category)
    {
        $data = $request->validate([
            'ma_danh_muc'       => 'nullable|string',
            'ma_danh_muc_cha'   => 'nullable|string',
            'ten_danh_muc'      => 'required|string',
            'logo_url'          => 'nullable|image|max:5120',
            'trang_thai'        => 'required|in:active,inactive,delete',
        ]);

        $category->update($data);

        if ($request->hasFile('logo_url')) {
            try {
                $file = $request->file('logo_url');
                $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => "vntech/categories/" . ($category->ma_danh_muc)
                ]);
                $category->update(['logo_url' => $upload['secure_url']]);
            } catch (\Exception $e) {
                return back()->withErrors(['cloudinary' => 'Lỗi upload: ' . $e->getMessage()]);
            }
        }

        return redirect()->back()->with('success', 'Cập nhật category thành công!');
    }
}
