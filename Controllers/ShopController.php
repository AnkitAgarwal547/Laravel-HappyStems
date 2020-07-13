<?php

namespace App\Http\Controllers;

use App\DeliverySettings;
use App\DeliveryTimeSettings;
use App\LastSearch;
use App\ProductCities;
use App\ProductOptions;
use App\ProductReviews;
use App\ProductTags;
use App\ProductVariationGallery;
use App\Wishlist;
use Backpack\PageManager\app\Models\Page;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Products;
use App\ProductTypes;
use App\ProductImages;
use App\Categories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Redirect;
use Session;

class ShopController extends Controller
{
    protected $no_of_rows = 12;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Throwable
     */

    public function index(Request $request)
    {
        $page = page('shop');
        $page->pageName = 'shop';
        $filter = 0;
        $all_products = Products::with(['productImages', 'productReviews', 'productOptions' => function($q){
            $q->with('gallery');
        }])->where('status', 1)->where('add_on_product', 0)->paginate($this->no_of_rows);

        $topCats = Categories::where('slug', 'like', '%combos-fl%')->get();
//        $annCakes = Products::where('status', 1)->where('slug', 'like', '%cake%')->get();
        $annCakes = Products::with(['productImages', 'productReviews', 'productOptions' => function($qu){
            $qu->with('gallery');
        }])->where('status', 1)->where('slug', 'like', '%cake%')->get();
        //dd($annCakes);
        if ($request->ajax()) {
            $view = view('shop-scroll', compact('page', 'all_products', 'filter'))->render();
            $last_page = $all_products->lastPage();
            $current_page = $all_products->currentPage();
            return response()->json(['html' => $view, 'last_page' => $last_page, 'current_page' => $current_page]);
        }
        return view('shop', compact('page', 'all_products', 'topCats', 'filter', 'annCakes'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws \Throwable
     */
    public function filterShop(Request $request){

        $filter = $request->input('filter');
        $category_id = '';
        $page = page('shop');
        if($request->input('category')){
            $category = $request->input('category');
            $category_y = Categories::where('slug', $category)->first();
            $category_id = $category_y->id;
            $page->pageName = 'category';
            $page->slug = $category_y->slug;
        }
        $topCats = Categories::where('slug', 'like', '%combos-fl%')->get();
        if($filter == 0){
            return redirect('/shop');
        }

        if($filter == 1){

            $all_products = Products::with(['productImages', 'productReviews',])
                ->join('product_options', 'product_options.product_id', '=', 'products.id')
//                ->join('product_variation_galleries', 'product_variation_galleries.option_id', '=', 'product_options.id')
                ->orderBy('product_options.option_val', 'DESC')
                ->where('status', 1)->where('add_on_product', 0)->orderBy('regular_price', 'DESC')
                ->paginate($this->no_of_rows);

            if($category_id){
                $all_products = Products::with(['productImages', 'productReviews'])
                    ->where(DB::RAW('FIND_IN_SET(' . $category_id . ',cat_ids)'), '!=', '')
                    ->join('product_options', 'product_options.product_id', '=', 'products.id')
                    ->join('product_variation_galleries', 'product_variation_galleries.option_id', '=', 'product_options.id')
                    ->orderBy('product_options.option_val', 'DESC')
                    ->where('status', 1)->where('add_on_product', 0)
                    ->orderBy('regular_price', 'DESC')
                    ->paginate($this->no_of_rows);
            }

//            foreach($all_products as $key => $product){
//                $product->gallery = [];
//                $gallery = [];
//
//                    $productOptions = ProductOptions::with('gallery')->where('product_id', $product->product_id)->get();
//                    foreach($productOptions as $key2 => $productOption){
//                        if($productOption->gallery){
//                            array_push($gallery, $productOption->gallery->gallery_images);
//                        }
//                    }
//                    $product->gallery = $gallery;
//
//            }

            $annCakes = Products::with(['productImages', 'productReviews', 'productOptions' => function($qu){
                $qu->with('gallery');
            }])->where('status', 1)->where('slug', 'like', '%cake%')->get();
        }

        if($filter == 2){

            $all_products = Products::with(['productImages', 'productReviews', ])
                ->join('product_options', 'product_options.product_id', '=', 'products.id')->orderBy('product_options.option_val', 'ASC')

//                ->join('product_variation_galleries', 'product_variation_galleries.option_id', '=', 'product_options.id')

                ->where('status', 1)->where('add_on_product', 0)->orderBy('regular_price', 'ASC')->limit(2)->paginate($this->no_of_rows);


            if($category_id){
                $all_products = Products::with(['productImages', 'productReviews'])

                    ->where(DB::RAW('FIND_IN_SET(' . $category_id . ',cat_ids)'), '!=', '')

                    ->join('product_options', 'product_options.product_id', '=', 'products.id')
                    ->join('product_variation_galleries', 'product_variation_galleries.option_id', '=', 'product_options.id')
                    ->orderBy('product_options.option_val', 'ASC')
                    ->where('status', 1)->where('add_on_product', 0)

                    ->orderBy('regular_price', 'ASC')
                    ->paginate($this->no_of_rows);
            }


//            foreach($all_products as $key => $product){
//                $product->gallery = [];
//                $gallery = [];
//
//                $productOptions = ProductOptions::with('gallery')->where('product_id', $product->product_id)->get();
//                foreach($productOptions as $key2 => $productOption){
//                    if($productOption->gallery){
//                        array_push($gallery, $productOption->gallery->gallery_images);
//                    }
//                }
//                $product->gallery = $gallery;
//
//            }

            $annCakes = Products::with(['productImages', 'productReviews', 'productOptions' => function($qu){
                $qu->with('gallery');
            }])->where('status', 1)->where('slug', 'like', '%cake%')->get();
        }

        foreach($all_products as $key => $product){
            $product->gallery = [];
            $gallery = [];

            $productOptions = ProductOptions::with('gallery')->where('product_id', $product->product_id)->get();
            foreach($productOptions as $key2 => $productOption){
                if($productOption->gallery){
                    array_push($gallery, $productOption->gallery->gallery_images);
                }
            }
            $product->gallery = $gallery;

        }
        if ($request->ajax()) {
            $view = view('shop-scroll', compact('page', 'all_products', 'filter'))->render();
            $last_page = $all_products->lastPage();
            $current_page = $all_products->currentPage();
            return response()->json(['html' => $view, 'last_page' => $last_page, 'current_page' => $current_page]);
        }

        return view('shop', compact('page', 'all_products', 'topCats', 'filter', 'annCakes'));
    }

    /**
     * @param $type_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     *
     */
    public function ShopByType($type_slug)
    {

        $productType = ProductTypes::where('slug', '=', $type_slug)->first();
        if (!empty($productType)) {
            $page = page('shop');
            $all_products = Products::where('status', 1)->whereRaw('FIND_IN_SET(' . $productType->id . ',type_ids)')->paginate($this->no_of_rows);
            return view('shop', compact('page', 'all_products'));
        } else {
            return redirect('shop');
        }
    }

    /**
     * @param $type_slug
     * @param $cat_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     *
     */
    public function ShopByTypeAndCategory($type_slug, $cat_slug)
    {
        $productType = ProductTypes::where('slug', '=', $type_slug)->first();
        $productCat = Categories::where('slug', '=', $cat_slug)->first();
        if (!empty($productType) && !empty($productCat)) {
            $page = page('shop');
            $all_products = Products::where('status', 1)->whereRaw('FIND_IN_SET(' . $productType->id . ',type_ids)')->whereRaw('FIND_IN_SET(' . $productCat->id . ',cat_ids)')->paginate($this->no_of_rows);
            return view('shop', compact('page', 'all_products'));
        } else {
            return redirect('shop');
        }
    }

    /**
     * @param Request $request
     * @param $cat_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws \Throwable
     */

    public function shopByCategory(Request $request, $cat_slug){
        $filter = 0;
        $productCat = Categories::where('slug', '=', $cat_slug)->first();
//        dd($productCat);
        if (!empty($productCat)) {
            $page = $productCat;
            $page->pageName = 'category';

            if($cat_slug == 'happy-specials'){
                return view('happyspecials', compact('page'));
            }
            /*$all_products = Products::where('status', 1)->where('add_on_product', 0)->whereRaw('FIND_IN_SET(' . $productCat->id . ',cat_ids)')->paginate($this->no_of_rows);*/

            $all_products = Products::where('status', 1)->where('add_on_product', 0)->whereRaw('FIND_IN_SET(' . $productCat->id . ',cat_ids)')->paginate($this->no_of_rows);

            $topCats = Categories::where('slug', 'like', '%combos-fl%')->get();
            $annCakes = Products::with(['productImages', 'productReviews', 'productOptions' => function($qu){
                $qu->with('gallery');
            }])->where('status', 1)->where('slug', 'like', '%cake%')->get();

            foreach ($all_products as $ord){
                if(array_key_exists( $productCat->id, $ord->order_in_cat)){
                    $ord->categoryOrder = $ord->order_in_cat[$productCat->id];
                }
            }

            $all = collect($all_products->items())->sortBy('categoryOrder');
            $another = [];
            foreach($all as $k => $v){
                $another[] = $v;
            }

            $all_products->getCollection()->transform(function ($value, $key) use ($another){
                return $another[$key];
            });

            if ($request->ajax()) {
                $view = view('shop-scroll', compact('page', 'all_products', 'filter'))->render();
                $last_page = $all_products->lastPage();
                $current_page = $all_products->currentPage();
                return response()->json(['html' => $view, 'last_page' => $last_page, 'current_page' => $current_page]);
            }

            return view('shop', compact('page', 'all_products', 'topCats', 'filter', 'annCakes'));
        } else {
            return redirect()->route('pageredirect', ['slug' => $cat_slug]);
            return redirect('shop');
        }
    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function shopByCities($slug){
        $filter = 0;
        $page = ProductCities::where('slug', $slug)->first();
        if(!empty($page)){

            $all_products = Products::where('status', 1)->where('add_on_product', 0)->whereRaw('FIND_IN_SET(' . $page->id . ',city_ids)')->with(['productImages', 'productReviews', 'productOptions'])->paginate($this->no_of_rows);

            $annCakes = Products::with(['productImages', 'productReviews', 'productOptions' => function($qu){
                $qu->with('gallery');
            }])->where('status', 1)->where('slug', 'like', '%cake%')->get();

            return view('cities', compact('page', 'all_products', 'annCakes', 'filter'));
        }
        return redirect('shop');
    }
    /**
     * @param $productslug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     *
     */
    public function ProductDetail($productslug)
    {
        $product_detail = Products::where('status', 1)->where('slug', $productslug)->first();
        if (!empty($product_detail)) {
            $page = $product_detail;
            $related_products = Products::where('status', 1)->where('slug', '!=', $productslug)->where('related_product', 1)->whereIn('type_ids', explode(',', $product_detail->type_ids))->whereIn('cat_ids', explode(',', $product_detail->cat_ids))->inRandomOrder()->limit(6)->get();
            $product_images = ProductImages::where('product_id', $product_detail->id)->get();

            $productOptions = ProductOptions::with('gallery')->where('product_id', $product_detail->id)->orderBy('option_val', 'ASC')->get();

            //dd($productOptions[0]->gallery->gallery_images);

            $reviews = ProductReviews::where('status', '=', 2)->where('product_id', $product_detail->id)->get();

            $productCount = Products::findOrFail($product_detail->id);
            if ($productCount->product_views != 0) {
                $productCount->product_views = $productCount->product_views + 1;
                $productCount->save();
            } else {
                $productCount->product_views = 1;
                $productCount->save();
            }
            // Product Category
            $product_cat_id = explode(',', $product_detail->cat_ids);
            $product_cats = [];
            $similarProducts=[];
            foreach($product_cat_id as $id){
                $cats = Categories::where('id', $id)->first();
                if($cats){
                    $similarProducts = Products::with(['productReviews'])->whereRaw('FIND_IN_SET(' . $cats->id . ',cat_ids)')->get();
                    array_push($product_cats, $cats);
                }
            }

            // Product Tags
            $product_tag_id = explode(',', $product_detail->tags);
            $product_tags = [];
            foreach($product_tag_id as $id){
                $cats = ProductTags::where('id', $id)->first();
                array_push($product_tags, $cats);
            }
//            dd($product_tags[0] == null);

            $delivery_setting = DeliverySettings::where('status', 1)->get();
//            dd($delivery_setting);

            // For Review Modal location
            $productCities = ProductCities::all();

            // For Add-on Product Modal
            $add_on_product = Products::where('status', 1)->where('add_on_product', 1)->get();
            //dd($similarProducts);

            $wishlist = Wishlist::where('product_id', $product_detail->id)->first();

            $lastSearches = '';
            $user = Auth::user();
            if($user){
                $lastSearch = new LastSearch();

                $checkLast = LastSearch::where('user', $user->id)->where('product_id', $product_detail->id)->first();
                if(!$checkLast){
                    $lastSearch->user = $user->id;
                    $lastSearch->product_id = $product_detail->id;

                    $lastSearch->save();
                }
                $lastSearches = LastSearch::with('productLastSearch')->where('user', $user->id)->get();
            }

            return view('product-detail', compact('page', 'product_detail', 'related_products', 'product_images', 'productOptions', 'reviews', 'product_cats', 'product_tags', 'delivery_setting', 'productCities', 'add_on_product', 'similarProducts', 'wishlist', 'lastSearches'));
        } else {
            return redirect('shop');
        }
    }


    public function headerSearch(Request $request)
    {

        $input = $request->all();
        $page = page('shop');
        $topCats = Categories::where('slug', 'like', '%combos-fl%')->get();
        $filter = 0;

        if (array_key_exists('search', $input)) {

            $search = $input['search'];
            if (Session::has('search')) $request->session()->forget('search');

            $request->session()->put('search', $search);

            $annCakes = Products::with(['productImages', 'productReviews', 'productOptions' => function($qu){
                $qu->with('gallery');
            }])->where('status', 1)->where('slug', 'like', '%cake%')->get();

            if ($search == '') {
                $all_products = Products::where('status', 1)->where('add_on_product', 0)->paginate($this->no_of_rows);
            } else {
                $all_products = Products::where('status', 1)->where('add_on_product', 0)->where('sku', 'like', '%' . $search . '%')->orWhere('name', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%')->paginate($this->no_of_rows);
            }
            return view('shop', compact('page', 'all_products', 'topCats', 'filter', 'annCakes'));
        }

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function headerSearchwithSession()
    {

        $page = page('shop');
        $topCats = Categories::where('slug', 'like', '%combos-fl%')->get();
        $search = '';
        if (Session::has('search')) {
            $search = Session::get('search');
        }
        $annCakes = Products::with(['productImages', 'productReviews', 'productOptions' => function($qu){
            $qu->with('gallery');
        }])->where('status', 1)->where('slug', 'like', '%cake%')->get();

        if ($search == '') {
            $all_products = Products::where('status', 1)->where('add_on_product', 0)->paginate($this->no_of_rows);
        } else {
            $all_products = Products::where('status', 1)->where('add_on_product', 0)->where('sku', 'like', '%' . $search . '%')->orWhere('name', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%')->paginate($this->no_of_rows);
        }
        return view('shop', compact('page', 'all_products', 'topCats' ,'annCakes'));
    }

    /**
     * @param Request $request
     */
    public function getDeliverySetting(Request $request){
        $deliveryDate = $request->input('delivery_date');
        $today = Carbon::today()->toDateString();
        $deliveryDate = date('Y-m-d', strtotime($deliveryDate));
        $previousDate = date('Y-m-d', strtotime($deliveryDate)-1);
        $currentTime = Carbon::now()->toTimeString();
        $addThreeHour = Carbon::now()->addHour(3)->format('H:00:00');

    }
    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTimeDeliverySetting(Request $request){
        $id = $request->input('id');
        $deliveryDate = $request->input('delivery_date');

        $today = Carbon::today()->toDateString();
        $deliveryDate = date('Y-m-d', strtotime($deliveryDate));
        $previousDate = date('Y-m-d', strtotime($deliveryDate)-1);
        $currentTime = Carbon::now()->toTimeString();
        $addThreeHour = Carbon::now()->addHour(3)->format('H:00:00');


        if($today <= $deliveryDate && $id == 4){
            if($deliveryDate > $today) {
                $deliveryTime['data'] = DeliveryTimeSettings::where('setting_id', $id)->get();
            }
            else {
                $deliveryTime['data'] = DeliveryTimeSettings::where('setting_id', $id)->where('time_from', '>=', $addThreeHour)->get();
                //dd($deliveryTime['data']);
            }
            $deliveryTime['setting'] = DeliverySettings::where('id', $id)->first();
        }
        else if($today <= $deliveryDate && $id == 5){
            if($deliveryDate > $today) {
                $deliveryTime['data'] = DeliveryTimeSettings::where('setting_id', $id)->get();
            }
            else {
                $deliveryTime['data'] = DeliveryTimeSettings::where('setting_id', $id)->where('time_from', '>=', $addThreeHour)->get();
                //dd($deliveryTime['data']);
            }
            $deliveryTime['setting'] = DeliverySettings::where('id', $id)->first();
        }
        else if($today <= $deliveryDate && $id == 2){
            if($currentTime <= "19:00:00"){
                $deliveryTime['data'] = DeliveryTimeSettings::where('setting_id', $id)->get();
                $deliveryTime['setting'] = DeliverySettings::where('id', $id)->first();
            }
        }
        elseif($previousDate >= $today && $currentTime <= "19:00:00"){
            if($deliveryDate > $today){
                $deliveryTime['data'] = DeliveryTimeSettings::where('setting_id', $id)->get();
                $deliveryTime['setting'] = DeliverySettings::where('id', $id)->first();
            }
        }
        else{
            $deliveryTime['status'] = 'past-date';
            $deliveryTime['data'] = 'Please Select Another Delivery Option or Select Another Date';
            return response()->json($deliveryTime);
        }

        if(count($deliveryTime['data']) > 0){
            $deliveryTime['status'] = 'success';
            return response()->json($deliveryTime);
        }
        $deliveryTime['status'] = 'failed';
        $deliveryTime['data'] = 'No Timing Found, Please try again later';
        return response()->json($deliveryTime);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAjaxDetails(Request $request){
        $prodId     = $request->input('product_id');
        $option_id  = $request->input('option_id');
        $extra      = $request->input('extra');

        $product = Products::where('id', $prodId)->first();
        $option = ProductOptions::where('id', $option_id)->first();

        if(!empty($product->sale_price)){
            $resp['price'] = $product->sale_price;
        }
        else if(!empty($product->regular_price)){
            $resp['price'] = $product->regular_price;
        }
        else{
            $resp['price'] = $option->option_val;
        }

        $resp['message'] = 'success';
        $resp['action']  = $request->input('action');

        return response()->json($resp);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function getAddOnDetail(Request $request){
//        dd($request->all());

        $add_on_id    = $request->input('add_on_id');
        $quantity     = $request->input('quantity');
        $check        = $request->input('checked');
        $deliveryId   = $request->input('delivery');

        $doubleFlower = "";
        if($request->has('double_flower')){
            $doubleFlower = $request->input('double_flower');
        }

        $extraVase = "";
        if($request->has('extraVase')){
            $extraVase    = $request->input('extraVase');
        }

        $dFlowerPrice = 0;
        if($doubleFlower){
           $dFlowerPrice = 350;
        }

        $eVasePrice = 0;
        if($extraVase){
            $eVasePrice = 200;
        }

        $delivery = DeliverySettings::where('id', $deliveryId)->first();
        $deliveryCharge = $delivery->price;

        $quan = 0;
        foreach ($add_on_id as $key=>$addId){
            if($check[$key] == 'true'){
                $quan = $quan + $quantity[$key];
                $data = Products::where('id', $add_on_id[$key])->first();
                if(!empty($data)){
                    $total[]  = $data->regular_price * $quantity[$key];
                }
            }
            else{
                $total[] = 0;
            }
        }
        if(count($total) > 0){
            $sum = 0;
            foreach ($total as $key=>$price){
                $sum = $sum + $price;
            }
        }
        else{
            $sum = 0;
        }
        $resp['message'] = 'success';
        $resp['price']   = $sum;
        $resp['quantity'] = $quan;
        $resp['delivery_charge'] = $deliveryCharge;
        $resp['doubleFlower'] = $dFlowerPrice;
        $resp['vase_extra'] = $eVasePrice;
        return response()->json($resp);
    }

    /**
     *
     */
    public function getExtraPriceDetail(Request $request){
        if($request->has('extra_field')){
            $extraField = $request->input('extra_field');
        }

        if($extraField == 'double-flower'){
            $extraPrice = 350;
        }

        else if($extraField == 'vase-extra'){
            $extraPrice = 200;
        }
        else{
            $extraPrice = 0;
        }

        $resp['message'] = 'success';
        $resp['price']   = $extraPrice;

        return response()->json($resp);
    }
}