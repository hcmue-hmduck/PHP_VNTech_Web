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
        $flash_sale_products = FlashSaleItem::where('ma_flash_sales', $flash_sales->ma_flash_sales)->get();
        return view('adminUI.formFlashSales', compact('flash_sales', 'flash_sale_products'));
    }

    public function storeCreateFlashSalesAdmin(Request $request) {
        $data = $request->validate([
            'ten_flash_sales'   => 'required|string|max:255',
            'mo_ta'             => 'nullable|string',
            'bat_dau'           => 'required|date',
            'ket_thuc'          => 'required|date|after:bat_dau',
            'trang_thai'        => 'required|string|in:ACTIVE,DRAFT,FINISHED',
        ]);

        $flash_sales = FlashSales::create($data);
        $flash_sales->ma_flash_sales = $flash_sales->_id;
        $flash_sales->update();
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
        return redirect()->back()->with('success', 'Cập nhật Flash Sales thành công');
    }
}
