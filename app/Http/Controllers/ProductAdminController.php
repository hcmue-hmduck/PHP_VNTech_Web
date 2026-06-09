<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductAdminController extends Controller
{
    public function viewProductAdmin(Request $request)
    {
        $query = Product::where('trang_thai', '!=', 'deleted')->with(['variants' => function ($query) {
            $query->where('trang_thai', '!=', 'deleted');
        }]);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('ten_san_pham', 'like', '%' . $search . '%')
                  ->orWhere('ma_san_pham', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('ma_danh_muc', $request->input('category'));
        }

        $products = $query->latest()->paginate(10)->appends($request->all());
        
        $totalProducts = Product::where('trang_thai', '!=', 'deleted')->count();
        $activeProducts = Product::where('trang_thai', 'active')->count();
        
        $lowStockProducts = ProductVariant::where('trang_thai', '!=', 'deleted')
            ->where('so_luong_ton_kho', '<=', 20)
            ->count();
        
        $inventoryValue = 0;
        $activeVariants = ProductVariant::where('trang_thai', '!=', 'deleted')->get(['gia_ban', 'so_luong_ton_kho']);
        foreach ($activeVariants as $variant) {
            $inventoryValue += floatval($variant->gia_ban) * intval($variant->so_luong_ton_kho);
        }

        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        return view('adminUI.productsAdmin', compact(
            'products', 
            'totalProducts', 
            'activeProducts', 
            'lowStockProducts', 
            'inventoryValue', 
            'brands', 
            'categories'
        ));
    }

    public function viewCreateProductAdmin() 
    {   
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        $hasVariants = false;
        $simpleVariant = null;
        return view('adminUI.formProductsAdmin', compact('brands', 'categories', 'hasVariants', 'simpleVariant'));
    }

    public function storeCreateProductAdmin(Request $request) 
    {
        $data = $request->validate([
            'ten_san_pham'              => 'required|string|max:255',
            'ma_danh_muc'               => 'required|string|max:100',
            'ma_thuong_hieu'            => 'required|string|max:100',
            'mo_ta_ngan'                => 'nullable|string|max:500',
            'mo_ta_chi_tiet'            => 'nullable|string',
            'link_anh_dai_dien'         => 'nullable', 
            'trang_thai'                => 'required|in:active,inactive,delete',
            'hinh_anh'                  => 'nullable|array',
            'thong_so_ky_thuat_chung'   => 'nullable|array',
            'thong_tin_them'            => 'nullable|array',
            'kiem_tra_bien_the'         => 'required|boolean',
        ]);

        $product = Product::create($data);
        $product->ma_san_pham = $product->_id;
        $product->save();

        $filePath = $product->ma_san_pham;

        if ($request->hasFile('link_anh_dai_dien')) {
            $file = $request->file('link_anh_dai_dien');
            try {
                $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => "vntech/products/" . ($filePath)
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
                        'folder' => "vntech/products/" . ($filePath) . "/gallery"
                    ]);
                    $galleryImages[] = $upload['secure_url'];
                } catch (\Exception $e) {
                    return back()->withErrors(['cloudinary' => 'Lỗi upload gallery: ' . $e->getMessage()]);
                }
            }
            $product->update(['hinh_anh' => $galleryImages]);
        }
        if ($request->has('variants')) {
            $giaThapNhat = null;
            foreach ($request->variants as $index => $variant) {
                $giaBan = floatval($variant['gia_ban']);
                if ($giaThapNhat === null || $giaBan < $giaThapNhat) {
                    $giaThapNhat = $giaBan;
                }

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

                $fileVariantPath = $product_variant->ma_bien_the;
                if ($request->hasFile("variants.$index.link_anh_bien_the")) {
                    $file = $request->file("variants.$index.link_anh_bien_the");
                    try {
                        $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                            'folder' => "vntech/products/" . $filePath . "/variants/" . $fileVariantPath
                        ]);
                        $product_variant->update(['link_anh_bien_the' => $upload['secure_url']]);
                    } catch (\Exception $e) {
                        return back()->withErrors(['cloudinary' => 'Lỗi upload: ' . $e->getMessage()]);
                    }
                }
            }
            $product->setAttribute('gia_thap_nhat', $giaThapNhat ?? 0);
            $product->save();
        }

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function viewEditProductAdmin(Product $product) {
        $product_variant = ProductVariant::where('ma_san_pham', $product->ma_san_pham)
            ->where('trang_thai', '!=', 'deleted')
            ->get();
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();

        $hasVariants = $product->kiem_tra_bien_the;
        $simpleVariant = null;
        if (!$hasVariants && $product_variant->count() > 0) {
            $simpleVariant = $product_variant->first();
        }

        return view('adminUI.formProductsAdmin', compact('product', 'product_variant', 'brands', 'categories', 'hasVariants', 'simpleVariant'));
    }

    public function updateEditProductAdmin(Request $request, Product $product) {
        $data = $request->validate([
            'ten_san_pham'              => 'required|string|max:255',
            'ma_danh_muc'               => 'required|string|max:100',
            'ma_thuong_hieu'            => 'required|string|max:100',
            'mo_ta_ngan'                => 'nullable|string|max:500',
            'mo_ta_chi_tiet'            => 'nullable|string',
            'link_anh_dai_dien'         => 'nullable|image|max:5120', 
            'trang_thai'                => 'required|in:active,inactive,delete',
            'thong_so_ky_thuat_chung'   => 'nullable|array',
            'thong_tin_them'            => 'nullable|array',
            'hinh_anh.*'                => 'nullable|image|max:5120',
            'existing_hinh_anh'         => 'nullable|array',
            'kiem_tra_bien_the'         => 'required|boolean',
        ]);
        $existing_hinh_anh = $request->existing_hinh_anh ?? [];
        $filePath = $product->ma_san_pham;

        if ($request->hasFile('link_anh_dai_dien')) {
            $file = $request->file('link_anh_dai_dien');
            try {
                $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => "vntech/products/{$filePath}"
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
                    'folder' => "vntech/products/{$filePath}/gallery"
                ]);
                $gallery[] = $upload['secure_url'];
            }
            $data['hinh_anh'] = array_merge($existing_hinh_anh, $gallery);
        }
        else {
            $data['hinh_anh'] = $existing_hinh_anh;
        }

        $product->update($data);

        if ($request->has('variants')) {
            $allProductVariant = ProductVariant::where('ma_san_pham', $product->ma_san_pham)->get();
            $keptMaBienThe = [];
            $giaThapNhat = null;
            foreach ($request->variants as $index => $variantData) {
                $giaBan = floatval($variantData['gia_ban']);
                if ($giaThapNhat === null || $giaBan < $giaThapNhat) {
                    $giaThapNhat = $giaBan;
                }
                $thuocTinhRaw = $variantData['thong_so_ky_thuat_rieng'] ?? [];

                if (!empty($variantData['ma_bien_the'])) {
                    $product_variant = $allProductVariant->where('ma_bien_the', $variantData['ma_bien_the'])->first();
                    if ($product_variant) {
                        $product_variant->update([
                            'ten_bien_the' => $variantData['ten_bien_the'] ?? null,
                            'gia_ban' => $variantData['gia_ban'],
                            'gia_niem_yet' => $variantData['gia_niem_yet'],
                            'so_luong_ton_kho' => $variantData['so_luong_ton_kho'],
                            'trang_thai' => $variantData['trang_thai'],
                            'thong_so_ky_thuat_rieng' => $thuocTinhRaw,
                        ]);
                        $keptMaBienThe[] = $product_variant->ma_bien_the;
                    }
                } else {
                    $product_variant = $product->variants()->create([
                        'ma_san_pham' => $product->ma_san_pham,
                        'ten_bien_the' => $variantData['ten_bien_the'] ?? null,
                        'gia_ban' => $variantData['gia_ban'],
                        'gia_niem_yet' => $variantData['gia_niem_yet'],
                        'so_luong_ton_kho' => $variantData['so_luong_ton_kho'],
                        'trang_thai' => $variantData['trang_thai'],
                        'thong_so_ky_thuat_rieng' => $thuocTinhRaw,
                    ]);
                    $product_variant->ma_bien_the = $product_variant->_id;
                    $product_variant->save();
                    $keptMaBienThe[] = $product_variant->ma_bien_the;
                }

                if (isset($product_variant) && $request->hasFile("variants.$index.link_anh_bien_the")) {
                    $file = $request->file("variants.$index.link_anh_bien_the");
                    $fileVariantPath = $product_variant->ma_bien_the;
                    try {
                        $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                            'folder' => "vntech/products/" . $filePath . "/variants/" . $fileVariantPath
                        ]);
                        $product_variant->update(['link_anh_bien_the' => $upload['secure_url']]);
                    } catch (\Exception $e) {
                        return back()->withErrors(['cloudinary' => 'Lỗi upload: ' . $e->getMessage()]);
                    }
                }
            }
            foreach ($allProductVariant as $existingVariant) {
                if (!in_array($existingVariant->ma_bien_the, $keptMaBienThe)) {
                    ProductVariant::where('ma_bien_the', $existingVariant->ma_bien_the)->update(['trang_thai' => 'deleted']);
                }
            }
            $product->setAttribute('gia_thap_nhat', $giaThapNhat ?? 0);
            $product->save();
        }
        else {
            ProductVariant::where('ma_san_pham', $product->ma_san_pham)->update(['trang_thai' => 'deleted']);
        }

        
        return redirect()->back()->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function deleteProductAdmin(Product $product) {
        $product->trang_thai = 'deleted';
        $product->save();

        ProductVariant::where('ma_san_pham', $product->ma_san_pham)->update(['trang_thai' => 'deleted']);

        return redirect()->back()->with('success', 'Xoá sản phẩm thành công!');
    }
}