<?php

namespace App\Http\Controllers;

use App\Models\BannerImage;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BannerImagesController extends Controller
{
    public function viewBanner(Request $request) {
        $query = BannerImage::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('tieu_de', 'like', '%' . $search . '%');
        }

        $banner_images = $query->where('trang_thai', '!=', 'deleted')->latest()->get();
        return view('adminUI.bannerImagesAdmin', compact('banner_images'));
    }

    public function viewCreateBanner() {
        return view('adminUI.formBannerImagesAdmin');
    }

    public function storeCreateBanner(Request $request) {
        $data = $request->validate([
            'image'             => 'nullable|image',
            'tieu_de'           => 'nullable|string',
            'mo_ta'             => 'nullable|string',
            'lien_ket'          => 'nullable|string',
            'thu_tu_hien_thi'   => 'nullable|integer',
            'trang_thai'        => 'nullable|string'
        ]);

        $banner_images = BannerImage::create($data);
        $banner_images->ma_banner = $banner_images->_id;
        $banner_images->save();

        $filePath = $banner_images->ma_banner;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            try {
                $upload = Cloudinary::uploadApi()->upload($image->getRealPath(), [
                    'folder' => "vntech/banners/" . ($filePath)
                ]);
                $banner_images->update(['image_url' => $upload['secure_url']]);
            } catch (\Exception $e) {
                return back()->withErrors(['cloudinary' => 'Lỗi upload: ' . $e->getMessage()]);
            }
        }

        return redirect()->back()->with('success', 'Tạo banner thành công');
    }

    public function viewUpdateBanner(BannerImage $bannerImage) {
        return view('adminUI.formBannerImagesAdmin', compact('bannerImage'));
    }

    public function updateBanner(Request $request, BannerImage $bannerImage) {
        $data = $request->validate([
            'image'             => 'nullable|image',
            'tieu_de'           => 'nullable|string',
            'mo_ta'             => 'nullable|string',
            'lien_ket'          => 'nullable|string',
            'thu_tu_hien_thi'   => 'nullable|integer',
            'trang_thai'        => 'nullable|string'
        ]);

        $bannerImage->update($data);

        $filePath = $bannerImage->ma_banner;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            try {
                $upload = Cloudinary::uploadApi()->upload($image->getRealPath(), [
                    'folder' => "vntech/banners/" . ($filePath)
                ]);
                $bannerImage->update(['image_url' => $upload['secure_url']]);
            } catch (\Exception $e) {
                return back()->withErrors(['cloudinary' => 'Lỗi upload: ' . $e->getMessage()]);
            }
        }

        return redirect()->back()->with('success', 'Cập nhật banner thành công');
    }

    public function deleteBanner(BannerImage $bannerImage) {
       $bannerImage->trang_thai = 'deleted';
       $bannerImage->save();

        return redirect()->back()->with('success', 'Xoá banner thành công');
    }
}
