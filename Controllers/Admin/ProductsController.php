<?php

namespace App\Http\Controllers\Admin;
use App\ProductCities;
use App\ProductOptions;
use App\ProductVariationGallery;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Slug;
use App\Categories;
use App\Products;
use App\Attributes;
use App\AttributeValues;
use App\ProductImages;
use App\ProductAttributes;
use App\ProductTypes;
use App\ProductTags;
use App\ProductColors;
use App\Shipping;
use App\Tax;
use Validator;
use Session;
use Excel;
use Redirect;

class ProductsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
		/*$products = DB::table('products')->leftJoin('categories', 'products.cat_id', '=', 'categories.id')->leftJoin('product_attributes', 'products.id', '=', 'product_attributes.product_id')->select('products.*', 'categories.name as category', 'categories.slug as catslug', 'categories.parent as catparent',\DB::raw('MAX(product_attributes.regular_price) AS max_price'),\DB::raw('MIN(product_attributes.regular_price) AS min_price'))->groupBy('products.id')->orderBy('products.id','desc')->get();*/
		$products = Products::orderBy('id','desc')->get();
		return view('admin.product.products',compact('products'));
	}

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	//Add Product Form
	public function addProduct(){
	    $allProducts = Products::all();
		$producttypes = ProductTypes::all();
		$categories = Categories::where('parent', '=', 0)->get();
		$attributes = Attributes::all();
		$tags 		= ProductTags::where('status', '=', 1)->get();
		$colors 	= ProductColors::where('status', '=', 1)->get();
        $productCities = ProductCities::all();
		return view('admin.product.add-product',compact('categories','attributes','tags','colors','producttypes', 'productCities', 'allProducts'));
	}

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	//Save Prdouct Info To Database
	public function saveProduct(Request $request){

			$rules = [
				'product_name'  => 'required|min:5',
                'feature_image' => 'file|mimes:webp,jpeg,png,jpg,gif,svg|max:5000',
				'sku'			=> 'required|unique:products,sku',
                'product_type'  => 'required',
			];
			if($request->input('images')){
			    $images = count($request->input('images'));

                foreach(range(0, $images) as $index) {
                    $rules['images.' . $index] = 'file|mimes:webp,jpeg,png,jpg,gif,svg|max:5000';
                }
            }

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) {
                return redirect('admin/products/add')
                    ->withErrors($validator)
                    ->withInput();
			}

            //----------- For Simple products -----------
			if($request->input('product_type') == 1){
                Validator::make($request->all(), [
                    'regular_price'  => 'required',
                ])->validate();

            }
            //---------- For variable products -----------
            if($request->input('product_type') == 2){
                Validator::make($request->all(), [
                    'option_text.*'  => 'required',
                    'option_val.*'   => 'required',
                ])->validate();
            }

            //--------------------------------------------------------
			$product_name 		= $request->input('product_name');
			$description 		= $request->input('description');
			$care_inst 		    = $request->input('care_instruction');
			$delivery_info 		= $request->input('delivery_info');
			$gift_contain 		= $request->input('gift_contain');

			$product_type		= $request->input('types');
			if($product_type != '') {
				$product_type   = implode(",", $product_type);
			}else{
				$product_type = '';
			}

			$product_cat		= $request->input('categories');
			if($product_cat != '') {
				$product_cat   = implode(",", $product_cat);
			}else{
				$product_cat = '';
			}

            $product_city		= $request->input('city');
            if($product_city != '') {
                $product_city   = implode(", ", $product_city);
            }else{
                $product_city = '';
            }

			$sku 				= $request->input('sku');
			$regular_price 		= $request->input('regular_price');
			$sale_price 		= $request->input('sale_price');
			$stock_qty 			= $request->input('stock_qty');
			$stock_status	 	= $request->input('stock_status');

			$product_attributes	= $request->input('product_attributes');
			if($product_attributes != '') {
				$product_attributes   = implode(",", $product_attributes);
			}else{
				$product_attributes = '';
			}


			$product_attr		= $request->input('attr');
			if($product_attr != '') {
				$product_attr   = implode(",", $product_attr);
			}else{
				$product_attr = '';
			}

			$product_tags		= $request->input('tags');
			if($product_tags != '') {
				$product_tags   = implode(",", $product_tags);
			}else{
				$product_tags = '';
			}

			$product_colors		= $request->input('colors');
			if($product_colors != '') {
				$product_colors   = implode(",", $product_colors);
			}else{
				$product_colors = '';
			}

			$status				= $request->input('status');

			$popular_product 	= $request->input('popular_product');
			$featured_product	= $request->input('featured_product');
			$related_product	= $request->input('related_product');
			$extra_special  	= $request->input('extra_special');
			$extra_special_vase = $request->input('extra_special_vase');
            $add_on_product     = $request->input('add_on_product');

			$product_weight 	= $request->input('product_weight');
			$product_length 	= $request->input('product_length');
			$product_width 		= $request->input('product_width');
			$product_height 	= $request->input('product_height');


			$page_title 		= $request->input('page_title');
			$page_keyword 		= $request->input('page_keyword');
			$page_description 	= $request->input('page_description');

			$productType       = $request->input('product_type');
			$option_text       = $request->input('option_text');
			$option_val        = $request->input('option_val');
			$option_description= $request->input('var_description');
			$option_care       = $request->input('var_care_instruction');
			$option_dinfo      = $request->input('var_delivery_info');
			$option_gift       = $request->input('var_gift_contain');
			$option_fimage     = $request->file('var_feature_image');
			$option_gallery    = $request->input('var_images');

            // Product Order in categories
            $pos = $request->input('position');
            $categories = $request->input('categories');

            $order = [];
            if($categories){
                for($i = 0; $i < count($categories); $i++){
                    if($pos[$categories[$i]]){
                        $order[$categories[$i]] = $pos[$categories[$i]];
                    }
                }
            }

            $order = json_encode($order);
            //-----------------------------

			// Product Save
			$slugClass = new Slug();
    		$productSlug = $slugClass->make($product_name, 'products', 'slug');

			$product  = new Products();

			$product->name 				= $product_name;
			$product->slug				= $productSlug;
			$product->description 		= $description;
			$product->gift_contain 		= $gift_contain;
			$product->type_ids			= $product_type;
			$product->cat_ids			= $product_cat;
			$product->order_in_cat      = $order;
			$product->city_ids          = $product_city;

			$product->sku 				= $sku;
			$product->regular_price 	= $regular_price;
			$product->sale_price 		= $sale_price;
			$product->stock_qty 		= $stock_qty;
			$product->available_qty		= $stock_qty;
			$product->stock_status 		= $stock_status;

			$product->attribute  		= $product_attributes;
			$product->attribute_values 	= $product_attr;

			$product->tags 				= $product_tags;
			$product->colors 			= $product_colors;

			$product->status 			= ($status == 1 ? 1 : 2);

			$product->popular_product   = ($popular_product == 1) ? 1 : 0;
			$product->featured_product	= ($featured_product == 1) ? 1 : 0;
			$product->related_product	= ($related_product == 1) ? 1 : 0;
			$product->extra_special	    = ($extra_special == 1) ? 1 : 0;
			$product->extra_special_vase= ($extra_special_vase == 1) ? 1 : 0;
			$product->add_on_product    = ($add_on_product == 1) ? 1 : 0;

			$product->product_weight 	= $product_weight;
			$product->product_length 	= $product_length;
			$product->product_width 	= $product_width;
			$product->product_height 	= $product_height;

			$product->page_title 		= $page_title;
			$product->page_keyword 		= $page_keyword;
			$product->page_description 	= $page_description;
			$product->care_instruction 	= $care_inst;
			$product->delivery_info 	= $delivery_info;
			$product->product_type      = $productType;

			if ($request->hasFile('feature_image'))
			{
				$image = $request->file('feature_image');

				$filename  = $productSlug.'-'.rand(11111,99999) . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('assets/uploads/products/'), $filename);
				$product->featured_image = 'assets/uploads/products/'.$filename;
			}

			$product->created_at 	= date('Y-m-d H:i:s');
			$product->updated_at 	= date('Y-m-d H:i:s');


			if($product->save()){
				$gallary_images = $request->file('images');
				if(!empty($gallary_images)){
						foreach($gallary_images as $ikey=>$index) {
							$image = $index;
							$filename  = $productSlug.'-'.$ikey.'-'.time(). '.'. $image->getClientOriginalExtension();
							$image->move(public_path('assets/uploads/products/'), $filename);

							$product_images  = new ProductImages();
							$product_images->image 			    = 'assets/uploads/products/'.$filename;
							$product_images->product_id 	    =  $product->id;
							$product_images->created_at 	    =  date('Y-m-d H:i:s');
							$product_images->updated_at 	    =  date('Y-m-d H:i:s');
							$product_images->save();
						}
				}

            // Product Variations Save
                //SAVE OPTIONS
                   if($request->input('option_text') && $productType == 2){
                        $i = 0;
                        $option_gimages     = $request->file('var_images');
                        foreach($request->input('option_text') as $kkey => $vval){
                            $productOptions  = new ProductOptions();
                            $productOptions->product_id 	= $product->id;
                            $productOptions->option_text 	= $vval;
                            $productOptions->option_val 	= $option_val[$kkey];

                            //--------------------------------------------------
                            $productOptions->var_description     = $option_description[$kkey];
                            $productOptions->var_care_inst       = $option_care[$kkey];
                            $productOptions->var_delivery_info   = $option_dinfo[$kkey];
                            $productOptions->var_gift_contain    = $option_gift[$kkey];
                            //$productOptions->var_featured_image  = $option_fimage;

//                            if ($request->hasFile('var_feature_image'))
//                            {
//                                $option_fimage     = $request->file('var_feature_image');
//                                $filename  = $productSlug.'-'.rand(11111,99999) . '.' . $option_fimage[$kkey]->getClientOriginalExtension();
//                                $option_fimage[$kkey]->move(public_path('assets/uploads/products/'), $filename);
//                                $productOptions->var_featured_image = 'assets/uploads/products/'.$filename;
//                            }

                            //$productOptions->var_gallery_image   = $option_gallery;
                            //--------------------------------------------------
                            $productOptions->created_at 	= date('Y-m-d H:i:s');
                            $productOptions->updated_at 	= date('Y-m-d H:i:s');
                            $productOptions->save();

                            // -------------- Gallery Images -------------

                            if ($option_gimages){
                                foreach($option_gimages as $key => $images) {
                                    $array_filename = [];
                                    foreach ($images as $image) {
                                        $filename = $productSlug . '-' . rand(11111, 99999) . '.' . $image->getClientOriginalExtension();
                                        $image->move(public_path('assets/uploads/products/variation/'), $filename);

                                        $gallery = new ProductVariationGallery();
                                        $gallery->option_id = $productOptions->id;
                                        $array_filename[] = 'assets/uploads/products/variation/' . $filename;
                                    }
                                    //var_dump($array_filename);
                                    $gallery->gallery_images = implode(", ", $array_filename);
                                    $gallery->save();
                                    unset($option_gimages[$key]);
                                    break;
//                                    $productOptions->var_gallery_image = implode(",", $array_filename);
                                }
                            }
                        }
                    }

                   Session::flash('message', "Product added successfully.");
				return redirect('admin/products');
			}else{
				Session::flash('error', "Product creation fails. Please try again after some time.");
				return redirect('admin/products');
			}
	}
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
	//EDIT PRODUCT FORM
	public function editProduct($id){

		$editProduct   = Products::find($id);

		if(!empty($editProduct)){
			$producttypes 	= ProductTypes::all();
			$categories 	= Categories::where('parent', '=', 0)->get();
			$productImages 	= ProductImages::where('product_id', $id)->get();

			$attributes = Attributes::all();
			$tags 		= ProductTags::where('status', '=', 1)->get();
			$colors 	= ProductColors::where('status', '=', 1)->get();
            $productOptions = ProductOptions::where('product_id', $id)->get();
            $variation_gallery = [];
            foreach ($productOptions as $option){
                $variation_gallery[] = ProductVariationGallery::where('option_id', $option->id)->first();
            }

            $productCities = ProductCities::all();

            $order_array = $editProduct->order_in_cat;


			return view('admin.product.edit-product',compact('editProduct','productImages','categories','attributes','tags','colors','producttypes', 'productOptions', 'productCities', 'order_array', 'variation_gallery'));
		}else{
			return redirect('admin/products');
		}
	}

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
	public function updateProduct($id,Request $request){

	    //dd($request->all());

		$editProduct   = Products::find($id);
		if(!empty($editProduct)){
			$rules = [
            	'product_name'  => 'required|min:5',
				'feature_image' => 'file|mimes:webp,jpeg,png,jpg,gif,svg|max:5000',
				'sku'			=> 'required|unique:products,sku,' . $editProduct->id,
                'option_text'   => 'required',
                'option_val'    => 'required',
				];
			if($request->input('images')){
                $images = count($request->input('images'));
                foreach(range(0, $images) as $index) {
                    $rules['images.' . $index] = 'file|mimes:webp,jpeg,png,jpg,gif,svg|max:5000';
                }
            }

			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
                return redirect('admin/products/edit/'.$id)
                    ->withErrors($validator)
                    ->withInput();
        	}

            //----------- For Simple products -----------
            if($request->input('product_type') == 1){
                Validator::make($request->all(), [
                    'regular_price'  => 'required',
                ])->validate();

            }
            //---------- For variable products -----------
            if($request->input('product_type') == 2){
                Validator::make($request->all(), [
                    'option_text.*'  => 'required',
                    'option_val.*'   => 'required',
                ])->validate();
            }

			$product_name 		= $request->input('product_name');
			$description 		= $request->input('description');
            $care_inst 		    = $request->input('care_instruction');
            $delivery_info 		= $request->input('delivery_info');
            $gift_contain 		= $request->input('gift_contain');

            $product_type		= $request->input('types');
			if($product_type != '') {
				$product_type   = implode(",", $product_type);
			}else{
				$product_type = '';
			}

			$product_cat		= $request->input('categories');
			if($product_cat != '') {
				$product_cat   = implode(",", $product_cat);
			}else{
				$product_cat = '';
			}

            $product_city		= $request->input('city');
            if($product_city != '') {
                $product_city   = implode(",", $product_city);
            }else{
                $product_city = '';
            }

			$sku 				= $request->input('sku');
			$regular_price 		= $request->input('regular_price');
			$sale_price 		= $request->input('sale_price');
			$available_qty 		= $request->input('available_qty');
			$stock_status	 	= $request->input('stock_status');

			$product_attributes	= $request->input('product_attributes');
			if($product_attributes != '') {
				$product_attributes   = implode(",", $product_attributes);
			}else{
				$product_attributes = '';
			}

			$product_attr		= $request->input('attr');
			if($product_attr != '') {
				$product_attr   = implode(",", $product_attr);
			}else{
				$product_attr = '';
			}

			$product_tags		= $request->input('tags');
			if($product_tags != '') {
				$product_tags   = implode(",", $product_tags);
			}else{
				$product_tags = '';
			}

			$product_colors		= $request->input('colors');
			if($product_colors != '') {
				$product_colors   = implode(",", $product_colors);
			}else{
				$product_colors = '';
			}

			$status				= $request->input('status');

			$popular_product 	= $request->input('popular_product');
			$featured_product	= $request->input('featured_product');
			$related_product	= $request->input('related_product');
			$extra_special  	= $request->input('extra_special');
            $extra_special_vase = $request->input('extra_special_vase');
            $add_on_product     = $request->input('add_on_product');

			$product_weight 	= $request->input('product_weight');
			$product_length 	= $request->input('product_length');
			$product_width 		= $request->input('product_width');
			$product_height 	= $request->input('product_height');

			$page_title 		= $request->input('page_title');
			$page_keyword 		= $request->input('page_keyword');
			$page_description 	= $request->input('page_description');

			$productType        = $request->input('product_type');

            // Product Order in categories
            $pos = $request->input('position');
            $categories = $request->input('categories');

            $order = [];
            if($categories){
                for($i = 0; $i < count($categories); $i++){
                    if($pos[$categories[$i]]){
                        $order[$categories[$i]] = $pos[$categories[$i]];
                    }
                }
            }

            $order = json_encode($order);
            //-----------------------------

			$editProduct->name 				= $product_name;
			$editProduct->description 		= $description;
			$editProduct->type_ids			= $product_type;
			$editProduct->cat_ids			= $product_cat;
			$editProduct->order_in_cat      = $order;
			$editProduct->city_ids          = $product_city;

			$editProduct->sku 				= $sku;
			$editProduct->regular_price 	= $regular_price;
			$editProduct->sale_price 		= $sale_price;

			if($editProduct->available_qty > $available_qty){
				//MINUS THE QTY
				$difference = $editProduct->available_qty - $available_qty;
				$difference = $editProduct->stock_qty - $difference;

				$editProduct->stock_qty = $difference;
			}else if($editProduct->available_qty < $available_qty){
				//PLUS THE QTY
				$difference = $available_qty - $editProduct->available_qty;
				$difference = $editProduct->stock_qty + $difference;
				$editProduct->stock_qty = $difference;
			}

			$editProduct->available_qty		= $available_qty;
			$editProduct->stock_status 		= $stock_status;

			$editProduct->product_type      = $productType;

			$editProduct->attribute  		= $product_attributes;
			$editProduct->attribute_values 	= $product_attr;
			$editProduct->status 			= ($status == 1 ? 1 : 2);
			$editProduct->tags 				= $product_tags;
			$editProduct->colors 			= $product_colors;

			$editProduct->popular_product = ($popular_product == 1) ? 1 : 0;
			$editProduct->featured_product	  = ($featured_product == 1) ? 1 : 0;
			$editProduct->related_product	  = ($related_product == 1) ? 1 : 0;
			$editProduct->extra_special	      = ($extra_special == 1) ? 1 : 0;
			$editProduct->extra_special_vase  = ($extra_special_vase == 1) ? 1 : 0;
			$editProduct->add_on_product      = ($add_on_product == 1) ? 1 : 0;

			$editProduct->product_weight 	= $product_weight;
			$editProduct->product_length 	= $product_length;
			$editProduct->product_width 	= $product_width;
			$editProduct->product_height 	= $product_height;

			$editProduct->page_title 		= $page_title;
			$editProduct->page_keyword 		= $page_keyword;
			$editProduct->page_description 	= $page_description;
            $editProduct->care_instruction 	= $care_inst;
            $editProduct->delivery_info 	= $delivery_info;
            $editProduct->gift_contain 	    = $gift_contain;


            if ($request->hasFile('feature_image'))
			{
				$image = $request->file('feature_image');

				$filename  = $editProduct->slug.'-'.rand(11111,99999) . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('assets/uploads/products/'), $filename);
				$editProduct->featured_image = 'assets/uploads/products/'.$filename;
			}

			$editProduct->updated_at 	= date('Y-m-d H:i:s');


			if($editProduct->save()){
				$gallary_images = $request->file('images');
				if(!empty($gallary_images)){
                    foreach($gallary_images as $ikey=>$index) {
                        $image = $index;
                        $filename  = $editProduct->slug.'-'.$ikey.'-'.time(). '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('assets/uploads/products/'), $filename);

                        $product_images  = new ProductImages();
                        $product_images->image 				= 'assets/uploads/products/'.$filename;
                        $product_images->product_id 		=  $editProduct->id;
                        $product_images->updated_at 		=  date('Y-m-d H:i:s');
                        $product_images->save();
                    }
				}

				// Product Variations Save

                $option_id 		    = $request->input('option_id');
                $option_text        = $request->input('option_text');
                $option_val         = $request->input('option_val');
                $option_var_desc    = $request->input('var_description');
                $option_var_care    = $request->input('var_care_instruction');
                $option_var_delivery= $request->input('var_delivery_info');
                $option_var_gift    = $request->input('var_gift_contain');
//				dd($option_id);

                if($productType == 1){
                    ProductOptions::where('product_id', $editProduct->id)->delete();
                }
                if(!empty($option_id) && $productType == 2){
                    //DB::enableQueryLog();
                    $optionId = ProductOptions::whereNotIn('id', $option_id)->where('product_id', '=', $editProduct->id)->get();
                    ProductOptions::whereNotIn('id', $option_id)->where('product_id', '=', $editProduct->id)->delete();
                    foreach ($optionId as $id){
                        ProductVariationGallery::where('option_id', $id->id)->delete();
                    }
                    //dd(DB::getQueryLog());
                }

                if(!empty($option_text) && $productType == 2){
                    $option_gimages     = $request->file('var_images');
                    foreach($option_text as $kkey => $vval){
                        if($vval){
                            $updateOption = ProductOptions::where('id', $kkey)->where('product_id', '=', $editProduct->id)->first();

                            if(!empty($updateOption)){
                                //Update Option

                                $updateOption->option_text 	= $vval;
                                $updateOption->option_val 	= $option_val[$kkey];

                                $updateOption->var_description      = $option_var_desc[$kkey];
                                $updateOption->var_care_inst        = $option_var_care[$kkey];
                                $updateOption->var_delivery_info    = $option_var_delivery[$kkey];
                                $updateOption->var_gift_contain     = $option_var_gift[$kkey];

                                // Gallery Variation----------------------------------

                                if ($option_gimages){
                                    foreach($option_gimages as $key => $images) {
                                        if($key == $updateOption->id) {
                                            $gallery = ProductVariationGallery::where('option_id', $updateOption->id)->first();
                                            $array_filename = [];
                                            foreach ($images as $image) {
                                                $filename = $editProduct->slug . '-' . rand(11111, 99999) . '.' . $image->getClientOriginalExtension();
                                                $image->move(public_path('assets/uploads/products/variation/'), $filename);

                                                $gallery->option_id = $updateOption->id;
                                                $array_filename[] = 'assets/uploads/products/variation/' . $filename;
                                            }
                                            var_dump($gallery);
                                            $gallery->gallery_images = implode(", ", $array_filename);
                                            $gallery->save();
                                            unset($option_gimages[$key]);
                                            break;
                                        }
                                    }
                                }
                                //----------------------------------------------------

                                $updateOption->updated_at 	= date('Y-m-d H:i:s');
                                $updateOption->save();
                            }else{
                                //Insert Option
                                $productOptions  = new ProductOptions();
                                $productOptions->product_id 	= $editProduct->id;
                                $productOptions->option_text 	= $vval;
                                $productOptions->option_val 	= $option_val[$kkey];

                                $productOptions->var_description  = $option_var_desc[$kkey];
                                $productOptions->var_care_inst    = $option_var_care[$kkey];
                                $productOptions->var_delivery_info= $option_var_delivery[$kkey];
                                $productOptions->var_gift_contain = $option_var_gift[$kkey];
                                $productOptions->updated_at 	= date('Y-m-d H:i:s');

                                if($productOptions->save()) {

                                    // Gallery Variation----------------------------------
                                    if ($option_gimages) {
                                        foreach ($option_gimages as $key => $images) {
                                            $array_filename = [];
                                            foreach ($images as $image) {
                                                $filename = $editProduct->slug . '-' . rand(11111, 99999) . '.' . $image->getClientOriginalExtension();
                                                $image->move(public_path('assets/uploads/products/variation/'), $filename);

                                                $gallery = new ProductVariationGallery();
                                                $gallery->option_id = $productOptions->id;
                                                $array_filename[] = 'assets/uploads/products/variation/' . $filename;
                                            }
                                            //var_dump($array_filename);
                                            $gallery->gallery_images = implode(", ", $array_filename);
                                            $gallery->save();
                                            unset($option_gimages[$key]);
                                            break;
//                                      $productOptions->var_gallery_image = implode(",", $array_filename);
                                        }
                                    }
                                    //----------------------------------------------------
                                }
                            }
                        }
                    }
                }

				Session::flash('message', "Product updated successfully.");
				return redirect('admin/products');
			}else{
				Session::flash('error', "Product updation fails. Please try again after some time.");
				return redirect('admin/products');
			}
		}else{
			return redirect('admin/products');
		}

	}
    /**
     * @param $id
     * @throws \Exception
     */
	//DELETE PRODUCT IMAGE
	public function deleteProductImage($id){
		$editProductImage  = ProductImages::find($id);
		if(!empty($editProductImage)){
				@unlink(public_path($editProductImage->image));
				$editProductImage->delete();
				echo "success";
		}else{
			echo "failed";
		}
	}

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
	//DElETE PRODUCT
	public function deleteProduct($id){
		$deleteProduct   = Products::find($id);
		if(!empty($deleteProduct)){
				ProductImages::where('product_id', '=', $id)->delete();
				$product_options = ProductOptions::where('product_id', $id)->get();
				foreach ($product_options as $option){
				    ProductVariationGallery::where('option_id', $option->id)->delete();
				    $option->delete();
                }
				$deleteProduct->delete();
				Session::flash('message', "Product deleted successfully.");
				return redirect('admin/products');
		}else{
			return redirect('admin/products');
		}
	}

    /**
     * @param $id
     * @return \Illuminate\Http\Response
     */
	//GET ATTRIBUTE VALUES
	public function getAttributeValues($id){
		$attributeValues = AttributeValues::where('attribute_id', $id)->get();

		if(!empty($attributeValues)){
			return response()->view('admin.product.get-attribute-values',compact('attributeValues'));
		}
	}
    /**
     * @return \Illuminate\Http\Response
     */
	//ADD More Attributes
	public function addMoreAttributes(){
        return response()->view('admin.product.add-more-attributes');
	}

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	//TYPES LISTING
	public function Types(){

			$types = ProductTypes::all();
			$categories = Categories::where('parent', '=', 0)->get();
          	return view('admin.product.types',compact('types','categories'));
	}

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function saveType(Request $request){
		    $validator = Validator::make($request->all(),[
    				'typename' => 'required|min:3',
				 	'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
					'main_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
					'featured_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
    		]);

			if ($validator->fails()) {
            		return redirect('admin/products/types')
                        ->withErrors($validator)
                        ->withInput();
        	}

			$typename 			= $request->input('typename');
			$typeslug 			= $request->input('slug');

			$type_cat		= $request->input('categories');
			if($type_cat != '') {
				$type_cat   = implode(",", $type_cat);
			}else{
				$type_cat = '';
			}

			$typeDescription 	= $request->input('description');
			$page_title     	= $request->input('page_title');
			$page_keyword   	= $request->input('page_keyword');
			$page_description 	= $request->input('page_description');


			if($typeslug == ''){
				$typeslug = str_slug($typename, '-');
			}

			$slugClass = new Slug();
    		$newslug = $slugClass->make($typeslug, 'product_types', 'slug');


			$type  = new ProductTypes();
			$type->title 			= $typename;
			$type->slug 			= $newslug;
			$type->categories 		= $type_cat;
			$type->description 		= $typeDescription;
			$type->page_title 		= $page_title;
			$type->page_keyword 	= $page_keyword;
			$type->page_description = $page_description;
			$type->visible_status 	= 1;
			$type->created_at 		= date('Y-m-d H:i:s');
			$type->updated_at 		= date('Y-m-d H:i:s');


			$filename = '';

			if ($request->hasFile('thumbnail'))
			{
				$image = $request->file('thumbnail');
				$filename  = $newslug .'-'.rand(11111,99999) . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('assets/uploads/products/types/'), $filename);
				$type->thumbnail 		= 'assets/uploads/products/types/'.$filename;
			}

			$main_image = '';
			if ($request->hasFile('main_image'))
			{
				$image = $request->file('main_image');
				$main_image  = $newslug .'-'.rand(11111,99999) . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('assets/uploads/products/types/'), $main_image);
				$type->main_image 		= 'assets/uploads/products/types/'.$main_image;
			}

			$featured_image = '';
			if ($request->hasFile('featured_image'))
			{
				$image = $request->file('featured_image');
				$featured_image  = $newslug .'-'.rand(11111,99999) . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('assets/uploads/products/types/'), $featured_image);
				$type->featured_image 		= 'assets/uploads/products/types/'.$featured_image;
			}

			if($type->save()){
				Session::flash('message', "Product Type created successfully.");
				return Redirect::back();
			}else{
				Session::flash('error', "Product Type creation fails. Please try again after some time.");
				return Redirect::back();
			}
	}

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
	public function editType($id)
    {
        $editType   = ProductTypes::find($id);
		if(!empty($editType)){
			$categories = Categories::where('parent', '=', 0)->get();
        	return view('admin.product.edit-type',compact('categories','editType'));
		}else{
			return redirect('admin/products/types');
		}
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function updateType($id, Request $request){
		 $editType   = ProductTypes::find($id);
		 if(!empty($editType)){
			 $validator = Validator::make($request->all(),[
    			 'typename' 		=> 'required|min:3',
				 'thumbnail' 		=> 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
				 'main_image' 		=> 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
				 'featured_image' 	=> 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
				 'slug'				=> 'unique:product_types,slug,'.$id,
    		]);

			if ($validator->fails()) {
            		return redirect('admin/products/edit-type/'.$id)
                        ->withErrors($validator)
                        ->withInput();
        	}
			$typename 			= $request->input('typename');
			$typeslug 			= $request->input('slug');

			$type_cat		= $request->input('categories');
			if($type_cat != '') {
				$type_cat   = implode(",", $type_cat);
			}else{
				$type_cat = '';
			}

			$typeDescription 	= $request->input('description');
			$page_title     	= $request->input('page_title');
			$page_keyword   	= $request->input('page_keyword');
			$page_description 	= $request->input('page_description');

			if($typeslug == ''){
				$typeslug = str_slug($typename, '-');
				$slugClass = new Slug();
    			$typeslug = $slugClass->make($typeslug, 'product_types', 'slug');
			}

			$filename = '';
			if($request->hasFile('thumbnail'))
			{
				$image = $request->file('thumbnail');
				$filename  = $typeslug .'-'.rand(11111,99999) . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('assets/uploads/products/types/'), $filename);
				$editType->thumbnail = 'assets/uploads/products/types/'.$filename;
			}

			$main_image = '';
			if($request->hasFile('main_image'))
			{
				$image = $request->file('main_image');
				$main_image  = $typeslug .'-'.rand(11111,99999) . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('assets/uploads/products/types/'), $main_image);
				$editType->main_image = 'assets/uploads/products/types/'.$main_image;
			}

			$featured_image = '';
			if($request->hasFile('featured_image'))
			{
				$image = $request->file('featured_image');
				$featured_image  = $typeslug .'-'.rand(11111,99999) . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('assets/uploads/products/types/'), $featured_image);
				$editType->featured_image 		= 'assets/uploads/products/types/'.$featured_image;
			}



			$editType->title 		= $typename;
			$editType->slug 		= $typeslug;
			$editType->categories 	= $type_cat;

			 $editType->description = $typeDescription;
			 $editType->page_title 	= $page_title;
			 $editType->page_keyword = $page_keyword;
			 $editType->page_description = $page_description;
			 $editType->visible_status = 1;
			 $editType->updated_at = date('Y-m-d H:i:s');
			 if($editType->save()){
				Session::flash('message', "Product Type updated successfully.");
				return redirect('admin/products/types');
			 }

		 }else{
			 return redirect('admin/products/types');
		 }

	}

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
	public function deleteType($id)
    {
        $delType  = ProductTypes::find($id);
		if(!empty($delType)){
		    if($delType->thumbnail){
			    unlink(public_path($delType->thumbnail));
            }
		    if($delType->main_image) {
                unlink(public_path($delType->main_image));
            }
			$delType->delete();
			Session::flash('message', "Product Type deleted successfully.");
				return redirect('admin/products/types');
		}else{
			return redirect('admin/products/types');
		}
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	//ATTRIBUTES LISTING
	public function attributes(){
		$attributes = Attributes::all();
        return view('admin.product.product-attributes',compact('attributes'));
	}

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	//ADD ATTRIBUTE FORM
	public function addAttribute(){
		return view('admin.product.add-product-attributes');
	}

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	//SAVE ATTRIBUTE IN DATABASE
	public function saveAttribute(Request $request){
		$validator = Validator::make($request->all(),[
    				'name' => 'required|unique:attributes',
    		]);

			if ($validator->fails()) {
            		return redirect('admin/products/add-attribute')
                        ->withErrors($validator)
                        ->withInput();
        	}

			$attribute_name 		= $request->input('name');
			$attr_val 				= $request->input('attr_val');


			$attibute  = new Attributes();

			$attibute->name 		= $attribute_name;
			$attibute->created_at 	= date('Y-m-d H:i:s');
			$attibute->updated_at 	= date('Y-m-d H:i:s');

			if($attibute->save()){
				if(!empty($attr_val)){
					foreach($attr_val as $value){
						if($value != ''){
							$attr = new AttributeValues();
							$attr->attribute_id 	= $attibute->id;
							$attr->attribute_value 	= $value;
							$attr->created_at		= date('Y-m-d H:i:s');
							$attr->updated_at		= date('Y-m-d H:i:s');
							$attr->save();
						}
					}
				}


				Session::flash('message', "Attribute added successfully.");
				return redirect('/admin/products/attributes');
			}else{
				Session::flash('error', "Attribute creation fails. Please try again after some time.");
				return redirect('/admin/products/attributes');
			}
	}

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */

	//EDIT ATTRIBUTE FORM
	public function editAttribute($id){
		$editAttribute   = Attributes::find($id);
		if(!empty($editAttribute)){
			$attrVal = AttributeValues::where('attribute_id',$id)->get();
			return view('admin.product.edit-product-attribute',compact('editAttribute','attrVal'));
		}else{
			return redirect('admin/products/attributes');
		}
	}
    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
	//Update ATTRIBUTE To Database
	public function updateAttribute($id, Request $request){
		$editAttribute   = Attributes::find($id);
		if(!empty($editAttribute)){
			$validator = Validator::make($request->all(),[
    				'name' => 'required|unique:attributes,name,'.$editAttribute->id,
    		]);

			if ($validator->fails()) {
            		return redirect('admin/products/edit-attribute/'.$editAttribute->id)
                        ->withErrors($validator)
                        ->withInput();
        	}

			$attribute_name 		= $request->input('name');
			$attr_id 				= $request->input('attr_id');
			$attr_val 				= $request->input('attr_val');

			$editAttribute->name 		= $attribute_name;
			$editAttribute->updated_at 	= date('Y-m-d H:i:s');

			if($editAttribute->save()){
				if(!empty($attr_id)){
						//DB::enableQueryLog();
						AttributeValues::whereNotIn('id', $attr_id)->where('attribute_id', '=', $editAttribute->id)->delete();
						//dd(DB::getQueryLog());

				}

				if(!empty($attr_val)){
					foreach($attr_val as $attr_key=>$attrt_val){
						if($attrt_val){
							if(isset($attr_id) && in_array($attr_key,$attr_id)){
								$updateAttribute = AttributeValues::where('attribute_id', $editAttribute->id)->where('id', '=', $attr_key)->first();
								if(!empty($updateAttribute)){
									//UPDATE ATTRIBUTE
									$updateAttribute->attribute_value 	= $attrt_val;
									$updateAttribute->updated_at		= date('Y-m-d H:i:s');
									$updateAttribute->save();
								}
							}else{
									//SAVE ATTRIBUTE
									$attr = new AttributeValues();
									$attr->attribute_id 	= $editAttribute->id;
									$attr->attribute_value 	= $attrt_val;
									$attr->created_at		= date('Y-m-d H:i:s');
									$attr->updated_at		= date('Y-m-d H:i:s');
									$attr->save();
							}
						}
					}
				}
				Session::flash('message', "Attribute updated successfully.");
				return redirect('admin/products/attributes');
			}else{
				Session::flash('error', "Attribute updation fails. Please try again after some time.");
				return redirect('admin/products/attributes');
			}

		}else{
			return redirect('admin/products/attributes');
		}
	}

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
	//DElETE ATTRIBUTE
	public function deleteAttribute($id){
		$deleteAttribute = Attributes::find($id);
		if(!empty($deleteAttribute)){
				$deleteAttribute->delete();
				AttributeValues::where('attribute_id','=',$id)->delete();
				Session::flash('message', "Attribute deleted successfully.");
				return redirect('admin/products/attributes');
		}else{
			return redirect('admin/products/attributes');
		}
	}

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	//PRODUCT TAGS
	public function productTags(){
		$tags = ProductTags::orderBy('id', 'desc')->get();
		return view('admin.product.tags',compact('tags'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
   	//ADD PRODUCT TAG FORM
	public function addProductTag(){
		return view('admin.product.add-tag');
	}
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	//SAVE NEW PRODUCT TAG
	public function saveProductTag(request $request){
			$validator = Validator::make($request->all(),[
    			 'title' => 'required',
    		]);

			if ($validator->fails()) {
            		return redirect('admin/products/tags/add')
                        ->withErrors($validator)
                        ->withInput();
        	}
			$title				= $request->input('title');
			$description		= $request->input('description');
			$status 			= $request->input('status');


			$tag  = new ProductTags();

			$tag->name 				= $title;
			$tag->description 		= $description;
			$tag->status			= $status;

			$tag->created_at 	= date('Y-m-d H:i:s');
			$tag->updated_at 	= date('Y-m-d H:i:s');

			if($tag->save()){
				Session::flash('message', "Tag added successfully.");
				return redirect('admin/products/tags');
			}else{
				Session::flash('error', "Tag creation fails. Please try again after some time.");
				return redirect('admin/products/tags');
			}
	}

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
	//EDIT PRODUCT TAG FORM
	public function editProductTag($id){
		$editTag  = ProductTags::find($id);
		if(!empty($editTag)){
			return view('admin.product.edit-tag', compact('editTag'));
		}else{
			return redirect('admin/products/tags');
		}
	}

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	//UPDATE PRODUCT TAG
	public function updateProductTag($id, Request $request){
		$editTag  = ProductTags::find($id);
		if(!empty($editTag)){
			$validator = Validator::make($request->all(),[
    			 'title' => 'required',
    		]);

			if ($validator->fails()) {
            		return redirect('admin/products/tags/add')
                        ->withErrors($validator)
                        ->withInput();
        	}
			$title				= $request->input('title');
			$description		= $request->input('description');
			$status 			= $request->input('status');


			$editTag->name 				= $title;
			$editTag->description 		= $description;
			$editTag->status			= $status;

			$editTag->updated_at 	= date('Y-m-d H:i:s');

			if($editTag->save()){
				Session::flash('message', "Tag updated successfully.");
				return redirect('admin/products/tags');
			}else{
				Session::flash('error', "Tag updation fails. Please try again after some time.");
				return redirect('admin/products/tags');
			}
		}else{
				return redirect('admin/products/tags');
		}
	}

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
  //DELETE PRODUCT TAG
   public function deleteProductTag($id){
	    $deleteProductTag   = ProductTags::find($id);
		if(!empty($deleteProductTag)){
				$deleteProductTag->delete();
				Session::flash('message', "Product Tag deleted successfully.");
				return redirect('admin/products/tags');
		}else{
				return redirect('admin/products/tags');
		}
   }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
   //PRODUCT COLORS
   public function productColors(){
	    $colors = ProductColors::orderBy('id', 'desc')->get();
		return view('admin.product.colors',compact('colors'));
   }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
   //ADD PRODUCT COLOR
   public function addProductColor(){
	   return view('admin.product.add-color');
   }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
   //SAVE NEW PRODUCT Color
	public function saveProductColor(request $request){
			$validator = Validator::make($request->all(),[
    			 'name' 		=> 'required',
				 'color_code' 	=> 'required',
    		]);

			if ($validator->fails()) {
            		return redirect('admin/products/colors/add')
                        ->withErrors($validator)
                        ->withInput();
        	}
			$name			= $request->input('name');
			$color_code		= $request->input('color_code');
			$status 		= $request->input('status');


			$color  = new ProductColors();

			$color->name 			= $name;
			$color->color_code 		= $color_code;
			$color->status			= $status;

			$color->created_at 	= date('Y-m-d H:i:s');
			$color->updated_at 	= date('Y-m-d H:i:s');

			if($color->save()){
				Session::flash('message', "Color added successfully.");
				return redirect('admin/products/colors');
			}else{
				Session::flash('error', "Color creation fails. Please try again after some time.");
				return redirect('admin/products/colors');
			}
	}

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
	//EDIT PRODUCT Color FORM
	public function editProductColor($id){
		$editColor  = ProductColors::find($id);
		if(!empty($editColor)){
			return view('admin.product.edit-color', compact('editColor'));
		}else{
			return redirect('admin/products/colors');
		}
	}

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	//UPDATE PRODUCT Color
	public function updateProductColor($id, Request $request){
		$editColor  = ProductColors::find($id);
		if(!empty($editColor)){
			$validator = Validator::make($request->all(),[
    			 'name' 		=> 'required',
				 'color_code' 	=> 'required',
    		]);

			if ($validator->fails()) {
            		return redirect('admin/products/colors/edit/'.$editColor->id)
                        ->withErrors($validator)
                        ->withInput();
        	}
			$name			= $request->input('name');
			$color_code		= $request->input('color_code');
			$status 		= $request->input('status');

			$editColor->name 			= $name;
			$editColor->color_code 		= $color_code;
			$editColor->status			= $status;

			$editColor->updated_at 	= date('Y-m-d H:i:s');

			if($editColor->save()){
				Session::flash('message', "Color updated successfully.");
				return redirect('admin/products/colors');
			}else{
				Session::flash('error', "Color updation fails. Please try again after some time.");
				return redirect('admin/products/colors');
			}
		}else{
				return redirect('admin/products/colors');
		}
	}

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	//Shipping Options
	public function productShippingOptions(){
		$shippingOptions   = Shipping::find(1);
		return view('admin.product.shipping.options',compact('shippingOptions'));
	}

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	//Update Shipping Options
	public function saveProductShippingOptions(Request $request){
		$shippingOptions   = Shipping::find(1);
		if(!empty($shippingOptions)){
			$enable_shipping	= $request->input('enable_shipping');
			$display_mode		= $request->input('display_mode');

			$shippingOptions->enable_shipping 	= $enable_shipping;
			$shippingOptions->display_mode 		= $display_mode;

			$shippingOptions->updated_at 	= date('Y-m-d H:i:s');

			if($shippingOptions->save()){
				Session::flash('message', "Shipping Options updated successfully.");
				return redirect('admin/products/shipping-options');
			}else{
				Session::flash('error', "Shipping Options updation fails. Please try again after some time.");
				return redirect('admin/products/shipping-options');
			}
		}
	}
    /**
     * @param $method
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	//Shipping Methods
	public function productShippingMethod($method){
		$shippingOptions   = Shipping::find(1);
		if($method == 'flat-rate'){
			return view('admin.product.shipping.flat-rate',compact('shippingOptions'));
		}
		if($method == 'free-shipping'){
			return view('admin.product.shipping.free-shipping',compact('shippingOptions'));
		}
		if($method == 'ups-usps'){
			return view('admin.product.shipping.ups-usps',compact('shippingOptions'));
		}
	}

    /**
     * @param $method
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	//Shipping Methods
	public function saveProductShippingMethod($method, Request $request){

			$shippingOptions   = Shipping::find(1);
			if(!empty($shippingOptions)){
				if($method == 'flat-rate'){
					$enable_flat_rate		= $request->input('enable_flat_rate');
					$flat_rate_title		= $request->input('flat_rate_title');
					$flat_rate_cost			= $request->input('flat_rate_cost');

					$shippingOptions->enable_flat_rate 		= $enable_flat_rate;
					$shippingOptions->flat_rate_title 		= $flat_rate_title;
					$shippingOptions->flat_rate_cost 		= $flat_rate_cost;
				}

				if($method == 'free-shipping'){
					$enable_free_shipping		= $request->input('enable_free_shipping');
					$free_shipping_title		= $request->input('free_shipping_title');
					$free_shipping_cost			= $request->input('free_shipping_cost');

					$shippingOptions->enable_free_shipping 		= $enable_free_shipping;
					$shippingOptions->free_shipping_title 		= $free_shipping_title;
					$shippingOptions->free_shipping_cost 		= $free_shipping_cost;
				}


				if($method == 'ups-usps'){
					$enable_ups					= $request->input('enable_ups');
					$ups_user_id				= $request->input('ups_user_id');
					$ups_password				= $request->input('ups_password');
					$ups_access_key				= $request->input('ups_access_key');
					$ups_account_number			= $request->input('ups_account_number');

					$enable_usps				= $request->input('enable_usps');
					$usps_user_id				= $request->input('usps_user_id');

					$shippingOptions->enable_ups 		 = $enable_ups;
					$shippingOptions->ups_user_id 		 = $ups_user_id;
					$shippingOptions->ups_password 		 = $ups_password;
					$shippingOptions->ups_access_key 	 = $ups_access_key;
					$shippingOptions->ups_account_number = $ups_account_number;

					$shippingOptions->enable_usps 		= $enable_usps;
					$shippingOptions->usps_user_id 		= $usps_user_id;
				}

				$shippingOptions->updated_at 	= date('Y-m-d H:i:s');

				if($shippingOptions->save()){
					Session::flash('message', "Shipping Method updated successfully.");
					return redirect('admin/products/shipping-method/'.$method);
				}else{
					Session::flash('error', "Shipping method updation fails. Please try again after some time.");
					return redirect('admin/products/shipping-method/'.$method);
				}
			}

	}

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
	public function getProductCategories(Request $request){
            dd($request);
			if($request->input('cats')){
				$selected_cats = $request->input('cats');
			}else{
				$selected_cats = '';
			}
			$types = $request->input('types');
			$array = [];
			$array2 = [];
			$product_types = explode('~~~', $types);
			$product_categories = ProductTypes::whereIn('id', $product_types)->select('categories')->get();
			foreach($product_categories as $cat){
					$array = explode(',', $cat->categories);
					foreach($array as $val){
							$array2[] = $val;
					}
			}

			$arr = array_unique($array2);
			$categories = Categories::where('parent', '=',0)->get();
//			dd($selected_cats);
			return response()->view('admin.product.get-product-cats',compact('arr', 'categories', 'selected_cats'));

	}


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function taxSettings(){
			$tax = Tax::find(1);
			if(!empty($tax)){
				return view('admin.tax-settings',compact('tax'));
			}
	}

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function updateTaxSettings(Request $request){
			$tax = Tax::find(1);
			if(!empty($tax)){
				if($request->input('tax_rate')){
					$tax_rate = $request->input('tax_rate');
				}else{
					$tax_rate = 0;
				}
				$tax->tax_rate	= $tax_rate;
				if($tax->save()){
					Session::flash('message', "Tax Rate updated successfully.");
					return redirect('admin/tax-settings');
				}else{
					Session::flash('error', "Tax Rate updation fails. Please try again after some time.");
					return redirect('admin/tax-settings');
				}

			}
	}
    /**
     * @return \Illuminate\Http\Response
     */
    ////ADD More Options
    public function addMoreOptions(){
        $allProducts = Products::all();
        return response()->view('admin.product.add-more-options', compact('allProducts'));
    }
}
