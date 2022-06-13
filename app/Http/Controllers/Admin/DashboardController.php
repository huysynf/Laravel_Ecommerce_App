<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{

    protected $category;
    protected $product;
    protected $user;
    protected $coupon;
    protected $order;

    public function __construct(Product $product, Category $category, User $user, Coupon $coupon, Order $order)
    {
        $this->product = $product;
        $this->category = $category;
        $this->user = $user;
        $this->coupon = $coupon;
        $this->order = $order;
    }

    public function index()
    {
        $userCount = $this->user->count();
        $productCount = $this->product->count();
        $couponCount = $this->coupon->count();
        $orderCount = $this->order->count();
        $categoryCount = $this->category->count();

        return view('admin.dashboard.index', compact('userCount', 'productCount', 'couponCount', 'orderCount', 'categoryCount'));
    }
}
