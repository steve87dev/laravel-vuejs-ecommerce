<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\Cart;
use Auth;


class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Cart::where('carts.cart_status',1)->where('carts.user_id',Auth::id())->with('products')->orderBy('carts.id', 'desc')->get()->toArray(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $id = $request->input('id');
            $existingItems = Cart::where('carts.cart_status',1)->where('carts.user_id',Auth::id())->where('product_id',$id)->get()->toArray();
            if(count($existingItems) <= 0) {
                $product = Product::whereId($id)->first(array('id', 'price'))->toArray();
                $cartItem = new Cart();
                $cartItem->product_id = $id;
                $cartItem->user_id = Auth::id();
                $cartItem->cart_amount = $product['price'];
                $cartItem->cart_status = 1;
                $cartItem->save();
                return response()->json(array('code' => 1, 'message' => 'Product added to cart successfully'));
            } else {
                return response()->json(array('code' => 1, 'message' => 'Product is already in cart'));
            }
        }catch (Exception $e){
            return response()->json(array('code'=>0,'message'=>$e->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cart  = Cart::find($id);
        $cart->delete();
        return response()->json([
            'message' => 'Product removed successfully from cart!'
        ], 200);
    }

    public function placeOrder(){
        try{
            $cartItems = Cart::where('carts.cart_status',1)->where('carts.user_id',Auth::id())->update(array('cart_status'=>0));

            return response()->json(array('code' => 1, 'message' => 'Order place successfully'));
        }catch (Exception $e){
            return response()->json(array('code'=>0,'message'=>$e->getMessage()));
        }

    }

}
