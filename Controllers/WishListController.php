<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AjaxController;
use Illuminate\Http\Request;
use Response;
use Cookie;
use Session;
use Anam\Phpcart\Cart;
use Illuminate\Support\Facades\URL;
use App\Libraries\Coupon;
use App\Newsletter;
use Validator;
use App\Coupons;
use App\Products;
use App\Countries;
use App\States;
use App\Libraries\Ups;
use App\Shipping;
use App\Wishlist;
use Auth;
use DB;


class WishListController extends Controller
{
    public function wishList(Request $request)
    {
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            if (Wishlist::where('product_id', $request->input('product_id'))->where('user_id', $user_id)->count() > 0) {
                print "This product already added in wish list";
            } else {
                $dataToSave = new Wishlist();
                $dataToSave->user_id = $user_id;
                $dataToSave->product_id = $request->input('product_id');
                if ($dataToSave->save()) {
                    print "Wishlist added successfully!";
                } else {
                    print "Oops! Something went wrong.";
                }
            }
        } else {
            echo "Please Login first";
        }
    }

    /**
     * @param $product_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function WishListDtl($product_id)
    {
        $user_id = Auth::user()->id;
        if (Wishlist::where('product_id', $product_id)->where('user_id', $user_id)->delete()) {
            return redirect()->back();
        }
    }

    /**
     * @param $product_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function WishListMoveToCart($product_id){
        $user_id = Auth::user()->id;
        $product = Products::where('id', $product_id)->first();
        if (Wishlist::where('product_id', $product_id)->where('user_id', $user_id)->delete()) {

            return redirect('/shop/'.$product->slug);
        }
    }


    /**
     * @param Request $request
     * @throws \Exception
     */
    public function WishListselectremove(Request $request)
    {
        $user_id = Auth::user()->id;
        $productIds = $request->input('product_id');
        foreach ($productIds as $key => $productId) {
            if (Wishlist::where([['product_id', $productId], ['user_id', $user_id]])->delete()) {
                $response = true;
            }
        }
        echo json_encode($response);
    }

    public function WishListremove(Request $request)
    {
        $user_id = Auth::user()->id;
        $productIds = $request->input('product_id');
        foreach ($productIds as $key => $productId) {
            if (Wishlist::select('wishlists.*', 'products.id as products_id', 'products.stock_status as stock_status')
                ->join('products', 'products.id', "=", 'wishlists.product_id')
                ->where('wishlists.user_id', $user_id)
                ->where('wishlists.product_id', $productId)
                ->where('products.stock_status', '1')->delete()) {
                $response = true;
            }
        }
        echo json_encode($response);
    }

    public function WishListData()
    {
        $wishlist = Wishlist::select('*')->with('product')->get();
        return view('wishlist', ['wishlist' => $wishlist]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function WishListOrder()
    {
        $userDetail = Wishlist::with(['product' => function($q){
            $q->with(['productImages', 'productReviews', 'productOptions' => function($qu){
                $qu->with('gallery');
            }]);
        }])->get();
        $page = page('home');

        //dd($userDetail);

        if (!empty($userDetail)) {
            return view('my-wishlist', compact('userDetail', 'page'));
        }
    }

    /**
     * @param Request $request
     */
    public static function WishListAllAdd(Request $request)
    {
        $user_id = Auth::user()->id;
        $productData = $request->input('product_id');

        if (count($productData) > 0) {
            $items = Wishlist::select('wishlists.*', 'products.id as products_id', 'products.stock_status as stock_status')
                ->join('products', 'products.id', "=", 'wishlists.product_id')
                ->where('wishlists.user_id', $user_id)
                ->where('products.stock_status', 1)
                ->whereIn('wishlists.product_id', $productData)
                ->get();

            $items_out_stock = Wishlist::select('wishlists.*', 'products.id as products_id', 'products.stock_status as stock_status')
                ->join('products', 'products.id', "=", 'wishlists.product_id')
                ->where('wishlists.user_id', $user_id)
                ->where('products.stock_status', 2)
                ->whereIn('wishlists.product_id', $productData)
                ->get();
        } else {
            $items = Wishlist::select('wishlists.*', 'products.id as products_id', 'products.stock_status as stock_status')
                ->join('products', 'products.id', "=", 'wishlists.product_id')
                ->where('wishlists.user_id', $user_id)
                ->where('products.stock_status', '1')
                ->get();

            $items_out_stock = Wishlist::select('wishlists.*', 'products.id as products_id', 'products.stock_status as stock_status')
                ->join('products', 'products.id', "=", 'wishlists.product_id')
                ->where('wishlists.user_id', $user_id)
                ->where('products.stock_status', 2)
                ->get();

        }
        /*print_r($items);
        exit();*/

        if (count($items) == 0 && count($items_out_stock) > 0) {
            echo "out_stock";
            exit;
        }

        if (count($items) > 0) {
            foreach ($items as $item) {
                $wishlist[] = $item->product_id;
            }
            foreach ($wishlist as $product_id) {
                $product_id = intval($product_id);
                $quantity = 1;
                $product_data = array();
                $product_cart_line_data = array();
                if ($product_id > 0) {
                    $Ajax = new AjaxController;
                    $product_data = $Ajax->get_product_data_by_product_id($product_id);
                }
                if (count($product_data) > 0) {
                    $product_cart_line_data = $product_data;
                }
                if ($quantity > 0) {
                    $product_cart_line_data['product_line_quantity'] = $quantity;
                } else {
                    $product_cart_line_data['product_line_quantity'] = 0;
                }
                $Ajax->set_cart_data($product_cart_line_data);
            }

        } else {
            echo "no data";
        }
    }

    public function WishListAllDlt(Request $request)
    {

        $user_id = Auth::user()->id;
        $post = Wishlist::select('wishlists.*', 'products.id as products_id', 'products.stock_status as stock_status')
            ->join('products', 'products.id', "=", 'wishlists.product_id')
            ->where('wishlists.user_id', $user_id)
            ->where('products.stock_status', '1')->delete();
        //$post= DB::table('wishlists')->where('user_id', $user_id)->delete();

        if (!empty($post)) {
            echo "YES";
        }
    }

    public function getMini(Request $request)
    {
        $user_id = Auth::user()->id;
        if ($wishlist = Wishlist::select('*')->where('user_id', $user_id)->get()) {
            $returnHTML = view('wishlist', ['wishlist' => $wishlist])->render();
            return response()->json(array('status' => 'success', 'type' => 'mini_cart_data', 'html' => $returnHTML));
        }
    }


}

  