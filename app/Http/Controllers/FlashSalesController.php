<?php

namespace App\Http\Controllers;

use App\Models\FlashSaleItem;
use App\Models\FlashSales;
use App\Models\Product;
use App\Models\ProductVariant;

use Illuminate\Http\Request;

class FlashSalesController extends Controller
{
    public function viewFlashSalesAdmin(Request $request) {
        $query = FlashSales::where('trang_thai', '!=', 'deleted');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('ten_flash_sales', 'like', '%' . $search . '%')
                  ->orWhere('_id', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status') && $request->input('status') !== '') {
            $status = strtoupper($request->input('status'));
            $now = now();
            if ($status === 'LIVE') {
                $query->where('trang_thai', 'active')
                      ->where('bat_dau', '<=', $now)
                      ->where('ket_thuc', '>=', $now);
            } elseif ($status === 'SCHEDULED') {
                $query->where('trang_thai', 'active')
                      ->where('bat_dau', '>', $now);
            } elseif ($status === 'ENDED') {
                $query->where(function ($q) use ($now) {
                    $q->where('trang_thai', 'finished')
                      ->orWhere(function ($sub) use ($now) {
                          $sub->where('trang_thai', 'active')
                              ->where('ket_thuc', '<', $now);
                      });
                });
            }
        }

        $flash_sales = $query->latest()->get();

        // Calculate global counts (unfiltered by status/search)
        $allCampaigns = FlashSales::where('trang_thai', '!=', 'deleted')->get();
        $now = now();
        $totalCampaigns = $allCampaigns->count();
        $liveCampaigns = $allCampaigns->filter(fn($c) => $c->trang_thai_hien_thi === 'live')->count();
        $scheduledCampaigns = $allCampaigns->filter(fn($c) => $c->trang_thai_hien_thi === 'scheduled')->count();
        $endedCampaigns = $allCampaigns->filter(fn($c) => $c->trang_thai_hien_thi === 'ended')->count();

        return view('adminUI.flashsaleAdmin', compact(
            'flash_sales', 
            'totalCampaigns', 
            'liveCampaigns', 
            'scheduledCampaigns', 
            'endedCampaigns'
        ));
    }

    public function viewCreateFlashSalesAdmin() {
        $productWithVariants = Product::with(['variants' => function ($query) {
            $query->where('trang_thai', '!=', 'delete');
        }])->get();
        return view('adminUI.formFlashSalesAdmin', compact('productWithVariants'));
    }

    public function viewEditFlashSalesAdmin(FlashSales $flash_sales) {
        $flash_sale_products = FlashSaleItem::with('variant.product')
            ->where('ma_flash_sales', $flash_sales->ma_flash_sales)
            ->where('trang_thai', '!=', 'deleted')
            ->get();
        $productWithVariants = Product::with(['variants' => function ($query) {
            $query->where('trang_thai', '!=', 'delete');
        }])->get();
        return view('adminUI.formFlashSalesAdmin', compact('flash_sales', 'flash_sale_products', 'productWithVariants'));
    }

    public function storeCreateFlashSalesAdmin(Request $request) {
        $data = $request->validate([
            'ten_flash_sales'   => 'required|string|max:255',
            'mo_ta'             => 'nullable|string',
            'bat_dau'           => 'required|date',
            'ket_thuc'          => 'required|date|after:bat_dau',
            'trang_thai'        => 'required|string|in:active,finished',
            'products'          => 'nullable|array'
        ]);

        $flash_sales = FlashSales::create($data);
        $flash_sales->ma_flash_sales = (string) $flash_sales->_id;
        $flash_sales->save();

        if ($request->has('products')) {
            foreach ($request->products as $product) {
                $flash_sale_items = FlashSaleItem::create([
                    'ma_flash_sales'        => $flash_sales->ma_flash_sales,
                    'ma_bien_the'           => $product['ma_bien_the'],
                    'gia_flash_sale'        => floatval($product['gia_flash_sale']),
                    'so_luong_gioi_han'     => intval($product['so_luong_gioi_han']),
                    'so_luong_da_ban'       => 0,
                    'gioi_han_moi_nguoi'    => intval($product['gioi_han_moi_nguoi']),
                    'trang_thai'            => $product['trang_thai'],
                ]);
                $flash_sale_items->ma_chi_tiet_flash_sales = (string) $flash_sale_items->_id;
                $flash_sale_items->save();
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
            'trang_thai'        => 'required|string|in:active,finished',
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
                            'gia_flash_sale'        => floatval($product['gia_flash_sale']),
                            'so_luong_gioi_han'     => intval($product['so_luong_gioi_han']),
                            'gioi_han_moi_nguoi'    => intval($product['gioi_han_moi_nguoi']),
                            'trang_thai'            => $product['trang_thai'],
                        ]);
                        $keptFlashSaleItems[] = $flash_sale_items->ma_chi_tiet_flash_sales;
                    }
                }
                else {
                    $flash_sale_items = FlashSaleItem::create([
                        'ma_flash_sales'        => $flash_sales->ma_flash_sales,
                        'ma_bien_the'           => $product['ma_bien_the'],
                        'gia_flash_sale'        => floatval($product['gia_flash_sale']),
                        'so_luong_gioi_han'     => intval($product['so_luong_gioi_han']),
                        'so_luong_da_ban'       => 0,
                        'gioi_han_moi_nguoi'    => intval($product['gioi_han_moi_nguoi']),
                        'trang_thai'            => $product['trang_thai'],
                    ]);
                    $flash_sale_items->ma_chi_tiet_flash_sales = (string) $flash_sale_items->_id;
                    $flash_sale_items->save();
                    $keptFlashSaleItems[] = $flash_sale_items->ma_chi_tiet_flash_sales;
                }
            } 
            foreach ($allFlashSaleItems as $existingItems) {
                if (!in_array($existingItems->ma_chi_tiet_flash_sales, $keptFlashSaleItems)) {
                    FlashSaleItem::where('ma_chi_tiet_flash_sales', $existingItems->ma_chi_tiet_flash_sales)->update(['trang_thai' => 'deleted']);
                }
            }
        }
        else {
            FlashSaleItem::where('ma_flash_sales', $flash_sales->ma_flash_sales)->update(['trang_thai' => 'deleted']);
        }

        return redirect()->back()->with('success', 'Cập nhật Flash Sales thành công');
    }

    public function deleteFlashSalesAdmin(FlashSales $flash_sales) {
        $flash_sales->update(['trang_thai' => 'deleted']);
        FlashSaleItem::where('ma_flash_sales', $flash_sales->ma_flash_sales)->update(['trang_thai' => 'deleted']);
        return redirect()->back()->with('success', 'Xóa chiến dịch Flash Sale thành công');
    }
}
