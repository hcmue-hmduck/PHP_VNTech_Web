<?php

namespace App\Http\Controllers;

use App\Models\FlashSaleItem;
use App\Models\FlashSales;
use App\Models\Product;
use App\Models\ProductVariant;

use Illuminate\Http\Request;

class FlashSalesController extends Controller
{
    public function viewFlashSalesAdmin() {
        $flash_sales = FlashSales::latest()->get();
        return view('adminUi.flashsales', compact('flash_sales'));
    }

    public function viewCreateFlashSalesAdmin() {
        $productWithVariants = Product::with(['variants' => function ($query) {
            $query->where('trang_thai', '!=', 'delete');
        }])->get();
        return view('adminUI.formFlashSales', compact('productWithVariants'));
    }

    public function viewEditFlashSalesAdmin(FlashSales $flash_sales) {
        $flash_sale_products = FlashSaleItem::where('ma_flash_sales', $flash_sales->ma_flash_sales)->where('trang_thai', '!=', 'delete')->get();
        $productWithVariants = Product::with(['variants' => function ($query) {
            $query->where('trang_thai', '!=', 'delete');
        }])->get();
        return view('adminUI.formFlashSales', compact('flash_sales', 'flash_sale_products', 'productWithVariants'));
    }

    public function storeCreateFlashSalesAdmin(Request $request) {
        $data = $request->validate([
            'ten_flash_sales'   => 'required|string|max:255',
            'mo_ta'             => 'nullable|string',
            'bat_dau'           => 'required|date',
            'ket_thuc'          => 'required|date|after:bat_dau',
            'trang_thai'        => 'required|string|in:ACTIVE,DRAFT,FINISHED',
            'products'          => 'nullable|array'
        ]);

        $flash_sales = FlashSales::create($data);
        $flash_sales->ma_flash_sales = $flash_sales->_id;
        $flash_sales->update();

        if ($request->has('products')) {
            foreach ($request->products as $product) {
                $flash_sale_items = FlashSaleItem::create([
                    'ma_flash_sales'        => $flash_sales->ma_flash_sales,
                    'ma_bien_the'           => $product['ma_bien_the'],
                    'gia_flash_sale'        => $product['gia_flash_sale'],
                    'so_luong_gioi_han'     => $product['so_luong_gioi_han'],
                    'so_luong_da_ban'       => 0,
                    'gioi_han_moi_nguoi'    => $product['gioi_han_moi_nguoi'],
                    'trang_thai'            => $product['trang_thai'],
                ]);
                $flash_sale_items->ma_chi_tiet_flash_sales = $flash_sale_items->_id;
                $flash_sale_items->update();
            }
        }

        return redirect(route('admin.flashsales.index'))->with('success', 'Tạo Flash Sales thành công');
    }

    public function updateEditFlashSalesAdmin(Request $request, FlashSales $flash_sales) {
        $data = $request->validate([
            'ten_flash_sales'   => 'required|string|max:255',
            'mo_ta'             => 'nullable|string',
            'bat_dau'           => 'required|date',
            'ket_thuc'          => 'required|date|after:bat_dau',
            'trang_thai'        => 'required|string|in:ACTIVE,DRAFT,FINISHED',
        ]);
        $flash_sales->update($data);

        if ($request->has('products')) {
            $allFlashSaleItems = FlashSaleItem::where('ma_flash_sales', $flash_sales->ma_flash_sales)->get();
            $keptFlashSaleItems = [];
            foreach ($request->products as $product) {
                if (!empty($product['ma_chi_tiet_flash_sales'])) {
                    $flash_sale_items = $allFlashSaleItems->where('ma_chi_tiet_flash_sales', $product['ma_chi_tiet_flash_sales'])->first();
                    if ($flash_sale_items) {
                        $flash_sale_items->update([
                            'gia_flash_sale'        => $product['gia_flash_sale'],
                            'so_luong_gioi_han'     => $product['so_luong_gioi_han'],
                            'gioi_han_moi_nguoi'    => $product['gioi_han_moi_nguoi'],
                            'trang_thai'            => $product['trang_thai'],
                        ]);
                        $keptFlashSaleItems[] = $flash_sale_items->ma_chi_tiet_flash_sales;
                    }
                }
                else {
                    $flash_sale_items = FlashSaleItem::create([
                        'ma_flash_sales'        => $flash_sales->ma_flash_sales,
                        'ma_bien_the'           => $product['ma_bien_the'],
                        'gia_flash_sale'        => $product['gia_flash_sale'],
                        'so_luong_gioi_han'     => $product['so_luong_gioi_han'],
                        'gioi_han_moi_nguoi'    => $product['gioi_han_moi_nguoi'],
                        'trang_thai'            => $product['trang_thai'],
                    ]);
                    $flash_sale_items->ma_chi_tiet_flash_sales = $flash_sale_items->_id;
                    $flash_sale_items->update();
                    $keptFlashSaleItems[] = $flash_sale_items->ma_chi_tiet_flash_sales;
                }
            } 
            foreach ($allFlashSaleItems as $existingItems) {
                if (!in_array($existingItems->ma_chi_tiet_flash_sales, $keptFlashSaleItems)) {
                    FlashSaleItem::where('ma_chi_tiet_flash_sales', $existingItems->ma_chi_tiet_flash_sales)->update(['trang_thai' => 'delete']);
                }
            }
        }
        else {
            FlashSaleItem::where('ma_flash_sales', $flash_sales->ma_flash_sales)->update(['trang_thai' => 'delete']);
        }

        return redirect()->back()->with('success', 'Cập nhật Flash Sales thành công');
    }
}
