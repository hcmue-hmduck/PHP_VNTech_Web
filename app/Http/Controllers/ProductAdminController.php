<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductAdminController extends Controller
{
    public function viewProductAdmin()
    {
        $products = Product::latest()->paginate(10);
        return view('adminUI.productsAdmin', compact('products'));
    }
}