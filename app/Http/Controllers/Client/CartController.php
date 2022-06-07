<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\CreateOrderRequest;
use App\Http\Resources\Carts\CartResource;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;


class CartController extends Controller
{

    protected $cart;
    protected $product;
    protected $cartProduct;
    protected $coupon;
    protected $order;

    public function __construct(Product $product, Cart $cart, CartProduct $cartProduct, Coupon $coupon, Order $order)
    {
        $this->product = $product;
        $this->cart = $cart;
        $this->cartProduct = $cartProduct;
        $this->coupon = $coupon;
        $this->order = $order;
    }

   public function addToCart (Request $request)
   {
       if($request->product_size) {

        $product = $this->product->findOrFail($request->product_id);
        $cart = $this->cart->firtOrCreateBy(auth()->user()->id);

        $cartProduct = $this->cartProduct->getBy($cart->id, $product->id, $request->product_size);

        if($cartProduct) {
            $quantity = $cartProduct->product_quantity;
            $cartProduct->update(['product_quantity' => ($quantity + $request->product_quantity)]);
        } else {
            $dataCreate['cart_id'] = $cart->id;
            $dataCreate['product_size'] = $request->product_size;
            $dataCreate['product_quantity'] = $request->product_quantity ?? 1;
            $dataCreate['product_price'] = $product->price;
            $dataCreate['product_id'] = $request->product_id;
            $this->cartProduct->create($dataCreate);
        }
        return back()->with(['message' => 'Thêm thành công']);

       } else {
        return back()->with(['message' => 'Bạn chưa chọn size']);
       }
   }

   public function index()
   {
        $cart = $this->cart->firtOrCreateBy(auth()->user()->id)->load('products');

        return view('client.carts.index', compact('cart'));
   }

   public function removeProductInCart($id)
   {
        $cartProduct =  $this->cartProduct->find($id);
        $cartProduct->delete();
        $cart =  $cartProduct->cart;
        return response()->json([
            'product_cart_id' => $id,
            'cart' => new CartResource($cart)
        ], Response::HTTP_OK);
   }

   public function updateQuantityProduct(Request $request, $id)
   {
        $cartProduct =  $this->cartProduct->find($id);
        $dataUpdate = $request->all();
        if($dataUpdate['product_quantity'] < 1 ) {
            $cartProduct->delete();
        } else {
            $cartProduct->update($dataUpdate);
        }
        $cart =  $cartProduct->cart;

        return response()->json([
            'product_cart_id' => $id,
            'cart' => new CartResource($cart),
            'remove_product' => $dataUpdate['product_quantity'] < 1
        ], Response::HTTP_OK);
   }

   public function applyCoupon(Request $request, $id)
   {

    $couponCode = $request->input('coupon_code');

    $totalAmount = $request->input('total_price');

    $coupon = $this->coupon->fistHasExperyDateBy($couponCode, auth()->user()->id);

    if ($coupon) {
        if ($coupon->type == 'percent') {
            $discountAmountPrice = ($totalAmount * ($coupon->value / 100));
        } else {
            $discountAmountPrice = $coupon->value;
        }
        Session::put('coupon_id', $coupon->id);
        Session::put('discount_amount_price', $discountAmountPrice);
        session(['coupon_code' =>  $coupon->name]);

       $message = 'Áp Mã giảm giá thành công !';
    } else {
        Session::forget(['coupon_id', 'discount_amount_price', 'coupon_code']);
        $message = 'Mã giảm giá không tồn tại hoặc hết hạn!';
   }


        return redirect()->route('client.carts.index')->with([
            'message' => $message,
        ]);
    }

    public function checkout()
    {
        $cart = $this->cart->firtOrCreateBy(auth()->user()->id)->load('products');

        return view('client.carts.checkout', compact('cart'));
    }

    public function processCheckout(CreateOrderRequest $request)
    {

        $dataCreate = $request->all();
        $dataCreate['user_id'] = auth()->user()->id;
        $dataCreate['status'] = 'pending';
        $this->order->create($dataCreate);
        Session::forget(['coupon_id', 'discount_amount_price', 'coupon_code']);

        return redirect()->route('client.orders.index')->with(['message' => 'success']);
    }



}
