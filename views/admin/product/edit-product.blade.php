@extends('layouts.adminapp')

@section('content')

    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-tasks"></i> Edit Product</h3>
                </div>
                <!-- /.box-header -->

                <form class="form-horizontal" role="form" method="POST" id="admin_login"
                      action="{{ url('/admin/products/edit').'/'.$editProduct->id }}" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                            {{ csrf_field() }}
                            <!-- text input -->
                                <div class="form-group{{ $errors->has('product_name') ? ' has-error' : '' }}">
                                    <label for="product_name" class="col-sm-2 control-label">Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="product_name" name="product_name"
                                               value="{{ $editProduct->name != '' ? $editProduct->name : old('product_name') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="sku" class="col-sm-2 control-label">Product Type<span
                                                style="color: red; background-position: right top;">*</span></label>
                                    <div class="col-sm-10">
                                        <label>
                                            <input type="radio" name="product_type" class="flat-red product_type"
                                                   value="1" {{ $editProduct->product_type == 1 ? 'checked' : ''  }}>
                                            Simple
                                        </label>
                                        <label>
                                            <input type="radio" name="product_type" class="flat-red product_type"
                                                   value="2" {{ $editProduct->product_type == 2 ? 'checked' : ''  }}>
                                            Variable
                                        </label>
                                    </div>
                                </div>

                                {{-- Simple Product Description --}}
                                <div class="simple" style="display:{{ $editProduct->product_type == 1 ? 'block' : 'none'  }}">
                                    <div class="form-group">
                                        <label for="regular_price" class="col-sm-2 control-label">Regular Price<span
                                                    style="color: red; background-position: right top;">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="number" min="0.01" step="0.01" name="regular_price"
                                                   class="form-control" id="regular_price"
                                                   value="{{ $editProduct->regular_price != '' ? $editProduct->regular_price : old('regular_price') }}"
                                                   placeholder="Regular Price">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="sale_price" class="col-sm-2 control-label">Sale Price</label>
                                        <div class="col-sm-10">
                                            <input type="number" min="0.01" step="0.01" name="sale_price"
                                                   class="form-control" id="sale_price"
                                                   value="{{ $editProduct->sale_price != '' ? $editProduct->sale_price : old('sale_price') }}"
                                                   placeholder="Sale Price">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="link" class="col-sm-2 control-label">Description</label>
                                        <i class="fa fa-plus expand-collapse" style=" width: 25px; border-radius: 50px; background: #05a081; color: #fff; font-size: 20px; height: 25px; margin-left: 10px; padding: 4px; "></i>
                                        <div class="col-sm-10" id="description" style="display: none;">
                                        <textarea id="editor1" name="description" rows="10" cols="80">
                                              {{ $editProduct->description != '' ? $editProduct->description : old('description') }}
                                        </textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="link" class="col-sm-2 control-label">Care Instructions</label>
                                        <i class="fa fa-plus expand-collapse-care" style=" width: 25px; border-radius: 50px; background: #05a081; color: #fff; font-size: 20px; height: 25px; margin-left: 10px; padding: 4px; "></i>
                                        <div class="col-sm-10" id="care_instruction" style="display: none;">
                                        <textarea id="editor2" name="care_instruction" rows="10" cols="80">
                                                  {{ $editProduct->care_instruction != '' ? $editProduct->care_instruction : old('care_instruction') }}
                                        </textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="link" class="col-sm-2 control-label">Delivery Information</label>
                                        <i class="fa fa-plus expand-collapse-delivery-info" style=" width: 25px; border-radius: 50px; background: #05a081; color: #fff; font-size: 20px; height: 25px; margin-left: 10px; padding: 4px; "></i>
                                        <div class="col-sm-10" id="delivery_info" style="display: none;">
                                         <textarea id="editor3" name="delivery_info" rows="10" cols="80">
                                                  {{ $editProduct->delivery_info != '' ? $editProduct->delivery_info : old('delivery_info') }}
                                         </textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="link" class="col-sm-2 control-label">Your Gift Contains</label>
                                        <i class="fa fa-plus expand-collapse-gift" style=" width: 25px; border-radius: 50px; background: #05a081; color: #fff; font-size: 20px; height: 25px; margin-left: 10px; padding: 4px; "></i>
                                        <div class="col-sm-10" id="gift_contain" style="display: none;">
                                         <textarea id="editor4" name="gift_contain" rows="10" cols="80">
                                                  {{ $editProduct->gift_contain != '' ? $editProduct->gift_contain : old('gift_contain') }}
                                         </textarea>
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('feature_image') ? ' has-error' : '' }}">
                                        <label for="feature_image" class="col-sm-2 control-label">Feature Image</label>
                                        <div class="col-sm-10">
                                            @if (file_exists(public_path($editProduct->featured_image)) && $editProduct->featured_image!='')
                                                <img src="{{URL::asset($editProduct->featured_image)}}" width="200"><br/>
                                            @endif
                                            <input type="file" id="feature_image" name="feature_image" accept="image/*">
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                        <label for="exampleInputFile" class="col-sm-2 control-label">Gallery Images</label>
                                        <div class="col-sm-10">
                                            <input type="file" id="exampleInputFile" name="images[]" accept="image/*"
                                                   multiple>
                                            <br/>

                                            @if(!empty($productImages))
                                                @foreach ($productImages as $p)
                                                    <div class="img-wrap">
                                                        <span class="close">&times;</span>
                                                        <img src="{{URL::asset($p->image)}}" width="200"
                                                             data-id="{{ $p->id }}">
                                                    </div>
                                                @endforeach
                                            @endif

                                        </div>
                                    </div>

                                </div>
                                {{-- Simple Product Description Ends --}}

                                {{-- Variable Product Description --}}
                                <div class="variation" style="display:{{ $editProduct->product_type == 2 ? 'block' : 'none'  }}">
                                    @if(count($productOptions))
                                        <div class="options_div edit-product-option">
                                            @php $a = 1; @endphp
                                            @foreach($productOptions as $options)
                                                <div class="option_append" style="background: rgb(237, 237, 237); padding: 10px 0px; margin-top: 10px;">
                                                    <div class="form-group post clearfix" style="border-bottom: none;">
                                                        <input type="hidden" name="option_id[]" value="{{$options->id}}"/>
                                                        <label for="attr_val" class="col-sm-2 control-label">
                                                            @if($a == 1)
                                                                Options {{ $a }}<span style="color: red; background-position: right top;">*</span>
                                                            @else
                                                                Option {{ $a }}
                                                            @endif
                                                        </label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control"
                                                                   name="option_text[{{ $options->id }}]"
                                                                   placeholder="Description"
                                                                   value="{{ $options->option_text }}" >
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <input type="number" min="0.01" step="0.01" class="form-control"
                                                                   name="option_val[{{ $options->id }}]"
                                                                   placeholder="Sale Price(RM)"
                                                                   value="{{ $options->option_val }}" >
                                                        </div>

                                                        <div class="col-sm-2">
                                                            @if($a == 1)
                                                                <a class="btn bg-maroon" href="javascript:;"
                                                                   onclick="add_more_options()">
                                                                    <i class="fa fa-plus-square"></i> ADD MORE
                                                                </a>
                                                            @endif
                                                            @if($a != 1)
                                                                <a class="btn bg-orange remove_attr">
                                                                    <i class="fa fa-remove"></i> Remove
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    {{---------------------------------------------}}

                                                    <div class="form-group">
                                                        <label for="" class="col-sm-2 control-label">Description </label>
                                                        <i class="fa fa-plus var-expand-collapse" style=" width: 25px; border-radius: 50px; background: #05a081; color: #fff; font-size: 20px; height: 25px; margin-left: 10px; padding: 4px; "></i>
                                                        <div class="col-sm-10 var_description" style="display: none;">
                                                             <textarea id="" name="var_description[{{ $options->id }}]" class="collapse" rows="10" cols="80" data-id="{{ $options->id }}">
                                                                  {{ $options->var_description }}
                                                             </textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="" class="col-sm-2 control-label">Care Instructions</label>
                                                        <i class="fa fa-plus var-expand-collapse-care" style=" width: 25px; border-radius: 50px; background: #05a081; color: #fff; font-size: 20px; height: 25px; margin-left: 10px; padding: 4px; "></i>
                                                        <div class="col-sm-10 var_care_instruction" style="display: none;">
                                                             <textarea id="" name="var_care_instruction[{{ $options->id }}]" rows="10" cols="80" data-id="{{ $options->id }}">
                                                                  {{ $options->var_care_inst }}
                                                             </textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="" class="col-sm-2 control-label">Delivery Information</label>
                                                        <i class="fa fa-plus var-expand-collapse-delivery-info" style=" width: 25px; border-radius: 50px; background: #05a081; color: #fff; font-size: 20px; height: 25px; margin-left: 10px; padding: 4px; "></i>
                                                        <div class="col-sm-10 var_delivery_info" id="delivery_info" style="display: none;">
                                                             <textarea id="" name="var_delivery_info[{{ $options->id }}]" rows="10" cols="80" data-id="{{ $options->id }}">
                                                                  {{ $options->var_delivery_info }}
                                                             </textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                    <label for="" class="col-sm-2 control-label">Your Gift Contains</label>
                                                    <i class="fa fa-plus var-expand-collapse-gift" style=" width: 25px; border-radius: 50px; background: #05a081; color: #fff; font-size: 20px; height: 25px; margin-left: 10px; padding: 4px; "></i>
                                                    <div class="col-sm-10 var_gift_contain" style="display: none;">
                                                         <textarea id="" name="var_gift_contain[{{ $options->id }}]" rows="10" cols="80" data-id="{{ $options->id }}">
                                                              {{ $options->var_gift_contain }}
                                                         </textarea>
                                                    </div>
                                                </div>

                                                    <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                                        <label for="var_exampleInputFile" class="col-sm-2 control-label">Gallery Images</label>
                                                        <div class="col-sm-10">
                                                            <input type="file" id="var_exampleInputFile" name="var_images[{{ $options->id }}][]" accept="image/*" multiple>

                                                            <br>
                                                            @if(count($variation_gallery) > 0 )
                                                                @foreach ($variation_gallery as $key=>$gallery)
                                                                    @if($gallery)
                                                                        @if($options->id == $gallery->option_id)
                                                                            @php $images = explode(", ", $gallery->gallery_images) @endphp
                                                                            @foreach($images as $image)
                                                                                <div class="img-wrap">
                                                                                    <img src="{{URL::asset($image)}}" width="100">
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>


                                                    {{---------------------------------------------}}
                                                </div>

                                                @php $a++; @endphp
                                            @endforeach

                                        </div>
                                    @else
                                        <div class="options_div">
                                            <div class="form-group  clearfix">
                                                <label for="attr_val" class="col-sm-2 control-label">Options<span
                                                            style="color: red; background-position: right top;">*</span></label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" name="option_text[]"
                                                           placeholder="Description" value="">
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="number" min="0.01" step="0.01" class="form-control"
                                                           name="option_val[]" placeholder="Sale Price(RM)" value="">
                                                </div>
                                                <div class="col-sm-2">
                                                    <a class="btn bg-maroon" href="javascript:;"
                                                       onclick="add_more_options()">
                                                        <i class="fa  fa-plus-square"></i> ADD MORE
                                                    </a>
                                                </div>
                                            </div>

                                                {{--  Variable Product Description and all --}}
                                                <div class="form-group">
                                                    <label for="editor1" class="col-sm-2 control-label">Description </label>
                                                    <i class="fa fa-plus hs-expand-collapse" id="" style=" width: 25px; border-radius: 50px; background: #05a081; color: #fff; font-size: 20px; height: 25px; margin-left: 10px; padding: 4px; "></i>
                                                    <div class="col-sm-10" style="display: none;">
                                                         <textarea id="" name="var_description[]" class="collapse" rows="10" cols="80">
                                                              {{ old('description') }}
                                                         </textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="editor2" class="col-sm-2 control-label">Care Instructions</label>
                                                    <i class="fa fa-plus hs-expand-collapse-care" id="" style=" width: 25px; border-radius: 50px; background: #05a081; color: #fff; font-size: 20px; height: 25px; margin-left: 10px; padding: 4px; "></i>
                                                    <div class="col-sm-10" style="display: none;">
                                                         <textarea id="" name="var_care_instruction[]" rows="10" cols="80">
                                                              {{ old('description') }}
                                                         </textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="editor3" class="col-sm-2 control-label">Delivery Information</label>
                                                    <i class="fa fa-plus hs-expand-collapse-delivery-info" id="" style=" width: 25px; border-radius: 50px; background: #05a081; color: #fff; font-size: 20px; height: 25px; margin-left: 10px; padding: 4px; "></i>
                                                    <div class="col-sm-10" style="display: none;">
                                                         <textarea id="" name="var_delivery_info[]" rows="10" cols="80">
                                                              {{ old('description') }}
                                                         </textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="editor4" class="col-sm-2 control-label">Your Gift Contains</label>
                                                    <i class="fa fa-plus hs-expand-collapse-gift" id="" style=" width: 25px; border-radius: 50px; background: #05a081; color: #fff; font-size: 20px; height: 25px; margin-left: 10px; padding: 4px; "></i>
                                                    <div class="col-sm-10" style="display: none;">
                                                         <textarea id="" name="var_gift_contain[]" rows="10" cols="80">
                                                              {{ old('gift_contain') }}
                                                         </textarea>
                                                    </div>
                                                </div>

                                                {{--<div class="form-group{{ $errors->has('feature_image') ? ' has-error' : '' }}">--}}
                                                {{--    <label for="var_feature_image" class="col-sm-2 control-label">Feature Image</label>--}}
                                                {{--    <div class="col-sm-10">--}}
                                                {{--        <input type="file" id="var_feature_image" name="var_feature_image[]" accept="image/*">--}}
                                                {{--    </div>--}}
                                                {{--</div>--}}

                                                <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                                    <label for="var_exampleInputFile" class="col-sm-2 control-label">Gallery Images</label>
                                                    <div class="col-sm-10">
                                                        <input type="file" id="var_exampleInputFile" name="var_images[{{mt_rand(1,99)}}][]" accept="image/*" multiple>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label"></label>
                                                    <div class="col-sm-10"><strong>Or</strong></div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Existing Products</label>
                                                    <div class="col-sm-10">
                                                        <select name="existing_product" id="" class="form-control select2">
                                                            <option value="">Select Existing Product as Variation</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                {{--  Variable Product Description and all Ends --}}
                                            </div>

                                            </div>
                                        </div>
                                    @endif

                                {{-- Variable Product Description Ends --}}

                                <hr/>

                                <div class="form-group cities">
                                    <label for="city" class="col-sm-2 control-label">Product Cities</label>
                                    <div class="col-sm-10 long-scroll cityEditSelect">
                                        @php
                                            $c = [];
                                            if($editProduct->city_ids!=''){
                                                $c = explode(',', $editProduct->city_ids);
                                            }
                                        @endphp
                                        @foreach ($productCities as $city)
                                            <input type="checkbox" name="city[]" class="flat-red"
                                                   value="{{ $city->id }}" {{ in_array($city->id,$c) ? 'checked="checked"' : '' }}>{{ "&nbsp; ".$city->name }}
                                            <br/>
                                        @endforeach
                                    </div>
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-10" style="margin-top: 15px;">
                                        <input type="button" onclick='selectAll()' class="btn btn-success" value="Select All"/>&nbsp;
                                        <input type="button" onclick='UnSelectAll()' class="btn btn-warning" value="Unselect All"/>
                                    </div>
                                </div>

                                <hr/>
{{--                                <div class="form-group">--}}
{{--                                    <label for="categories" class="col-sm-2 control-label">Product Types</label>--}}
{{--                                    <div class="col-sm-10">--}}
{{--                                        @php--}}
{{--                                            $types = [];--}}
{{--                                            if($editProduct->type_ids!=''){--}}
{{--                                                $types = explode(',', $editProduct->type_ids);--}}
{{--                                            }--}}

{{--                                        @endphp--}}
{{--                                        @foreach ($producttypes as $type)--}}
{{--                                            <input type="checkbox" name="types[]" class="flat-red checked_types"--}}
{{--                                                   data-id="{{ $type->id }}"--}}
{{--                                                   value="{{ $type->id }}" {{ in_array($type->id,$types) ? 'checked="checked"' : '' }}>{{ "&nbsp; ".$type->title }}--}}
{{--                                            <br/>--}}

{{--                                        @endforeach--}}
{{--                                    </div>--}}
{{--                                </div>--}}


{{--                                @if($editProduct->cat_ids) 'ads' @else 'sdf' @endif {{dd()}}--}}

                                <div class="form-group categories" style="display:block">
                                    <label for="categories" class="col-sm-2 control-label">Product Categories</label>
                                    <div class="col-sm-10 long-scroll">
                                        @php
                                            $cat = [];
                                            if($editProduct->cat_ids!=''){
                                                $cat = explode(',', $editProduct->cat_ids);
                                            }
                                        @endphp
{{--{{dd()}}--}}
                                        @foreach ($categories as $key=>$category)
                                            @if($category->name != 'Cities')
                                                <div class="col-md-6">
                                                    <input type="checkbox" name="categories[]" class="flat-red"
                                                           value="{{ $category->id }}" {{ in_array($category->id,$cat) ? 'checked="checked"' : '' }}>{{ "&nbsp; ".$category->name }}
                                                    <br/>
                                                </div>
                                                <div class="col-md-5 category-margin-2">
                                                    <label for="pos_{{ $category->id }}" style=" float: left; ">Order in {{ "&nbsp; ".$category->name }}</label>
                                                    <input type="number" name="position[{{ $category->id }}]" id="pos_{{ $category->id }}" class="form-control" value="@if($order_array){{ (array_key_exists($category->id, $order_array)) ? $order_array[$category->id] : '' }}@endif" style="width:20%; float: right;">
                                                </div>

                                                @if(count($category->childs) > 0)
    {{--                                                @include('admin/product/manageProdCategories',['childs' => $category->childs, 'selectCategory'=>$editProduct->cat_ids])--}}
                                                    @foreach($category->childs as $child)
                                                        @if(count($child->childs) > 0 )
                                                            @foreach($child->childs as $lastChild)
                                                                <div class="col-md-6 category-margin-2">
                                                                    <input type="checkbox" name="categories[]" class="flat-red"
                                                                           value="{{ $lastChild->id }}" {{ in_array($lastChild->id,$cat) ? 'checked="checked"' : '' }}>{{ "&nbsp; ". $child->name ." > &nbsp; ".$lastChild->name }}<br/>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label for="pos_{{ $lastChild->id }}" style=" float: left; ">Order in {{ "&nbsp; ". $child->name ." > &nbsp; ".$lastChild->name }}</label>
                                                                    <input type="number" name="position[{{$lastChild->id}}]" id="pos_{{ $lastChild->id }}" class="form-control" value="@if($order_array ){{ (array_key_exists($lastChild->id, $order_array)) ? $order_array[$lastChild->id] : '' }}@endif" style="width:20%; float: right;">
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="col-md-6 category-margin-2">
                                                                <input type="checkbox" name="categories[]" class="flat-red" value="{{ $child->id }}" }} {{ in_array($child->id,$cat) ? 'checked="checked"' : '' }}>{{ "&nbsp; ".$child->name }}<br/>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <label for="pos_{{ $child->id }}" style=" float: left; ">Order in {{ "&nbsp; ".$child->name }}</label>
                                                                <input type="number" name="position[{{$child->id}}]" id="pos_{{ $child->id }}" class="form-control" value="@if($order_array){{ (array_key_exists($child->id, $order_array)) ? $order_array[$child->id] : '' }}@endif" style="width:20%; float: right;">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <hr/>
                                <h3>General</h3>
                                <div class="form-group{{ $errors->has('sku') ? ' has-error' : '' }}">
                                    <label for="sku" class="col-sm-2 control-label">SKU</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="sku" class="form-control" id="sku"
                                               value="{{ $editProduct->sku !='' ? $editProduct->sku : old('sku') }}"
                                               placeholder="SKU"><span>It's unique field</span>
                                    </div>
                                </div>

{{--                                <div class="form-group">--}}
{{--                                    <label for="regular_price" class="col-sm-2 control-label">Regular Price</label>--}}
{{--                                    <div class="col-sm-10">--}}
{{--                                        <input type="number" min="0.01" step="0.01" name="regular_price"--}}
{{--                                               class="form-control" id="regular_price"--}}
{{--                                               value="{{ $editProduct->regular_price !='' ? $editProduct->regular_price : old('regular_price') }}"--}}
{{--                                               placeholder="Regular Price">--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="form-group">--}}
{{--                                    <label for="sale_price" class="col-sm-2 control-label">Sale Price</label>--}}
{{--                                    <div class="col-sm-10">--}}
{{--                                        <input type="number" min="0.01" step="0.01" name="sale_price"--}}
{{--                                               class="form-control" id="sale_price"--}}
{{--                                               value="{{ $editProduct->sale_price !='' ? $editProduct->sale_price : old('sale_price') }}"--}}
{{--                                               placeholder="Sale Price">--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <div class="form-group">
                                    <label for="stock_qty" class="col-sm-2 control-label">Stock Qty</label>
                                    <div class="col-sm-10">
                                        <input type="number" min="0" step="1" class="form-control number"
                                               name="stock_qty" id="stock_qty"
                                               value="{{ $editProduct->stock_qty !='' ? $editProduct->stock_qty : old('stock_qty') }}"
                                               readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="available_qty" class="col-sm-2 control-label">Available Qty</label>
                                    <div class="col-sm-10">
                                        <input type="number" min="0" step="1" class="form-control number"
                                               name="available_qty" id="available_qty"
                                               value="{{ $editProduct->available_qty !='' ? $editProduct->available_qty : old('available_qty') }}">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="stock_status" class="col-sm-2 control-label">Stock Availability</label>
                                    <div class="col-sm-10">
                                        <select name="stock_status" id="stock_status" class="form-control">
                                            <option value="1" {{ (old('stock_status') == 1 || $editProduct->stock_status == 1) ? 'selected="selected"' : '' }}>
                                                In Stock
                                            </option>
                                            <option value="2" {{ (old('stock_status') == 2 || $editProduct->stock_status == 2) ? 'selected="selected"' : '' }}>
                                                Out of Stock
                                            </option>
                                        </select>
                                    </div>
                                </div>


{{--                                <hr/>--}}
{{--                                <h3> Product Attributes</h3>--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="product_attributes" class="col-sm-2 control-label">Attributes</label>--}}

{{--                                    @php--}}
{{--                                        $product_attrs = [];--}}
{{--                                        if($editProduct->attribute!=''){--}}
{{--                                            $product_attrs = explode(',', $editProduct->attribute);--}}
{{--                                        }--}}
{{--                                        $a = 1 ;--}}
{{--                                    @endphp--}}
{{--                                    @foreach ($attributes as $attr)--}}
{{--                                        @if($a != 1)--}}
{{--                                            <div class="col-sm-2"><label for="product_attributes"></label></div>--}}
{{--                                        @endif--}}
{{--                                        <div class="col-sm-10">--}}
{{--                                            <input type="checkbox" name="product_attributes[]"--}}
{{--                                                   class="flat-red attr_check" data-id="{{ $attr->id }}"--}}
{{--                                                   value="{{ $attr->id }}" {{ in_array($attr->id,$product_attrs) ? 'checked="checked"' : '' }} >{{ "&nbsp; ".$attr->name }}--}}
{{--                                            <br/>--}}

{{--                                            {!! attr_values('attribute_values','attribute_id',$attr->id, in_array($attr->id,$product_attrs) ? json_encode($editProduct->attribute_values) : '' ) !!}--}}
{{--                                        </div>--}}
{{--                                        <div class="clearfix"></div>--}}
{{--                                        @php $a++ @endphp--}}
{{--                                    @endforeach--}}

{{--                                </div>--}}


                                <hr/>
                                <h3> Product Tags</h3>
                                <div class="form-group">
                                    <label for="stock_status" class="col-sm-2 control-label">Tags</label>
                                    <div class="col-sm-10">
                                        @php
                                            $product_tags = [];
                                            if($editProduct->tags!=''){
                                                $product_tags = explode(',', $editProduct->tags);
                                            }
                                        @endphp
                                        @foreach ($tags as $tag)
                                            <input type="checkbox" name="tags[]" class="flat-red"
                                                   value="{{ $tag->id }}" {{ in_array($tag->id,$product_tags) ? 'checked="checked"' : '' }} >{{ "&nbsp; ".$tag->name }}
                                            <br/>
                                        @endforeach
                                    </div>
                                </div>

{{--                                <hr/>--}}
{{--                                <h3> Product Colors</h3>--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="stock_status" class="col-sm-2 control-label">Colors</label>--}}
{{--                                    <div class="col-sm-10">--}}
{{--                                        @php--}}
{{--                                            $product_colors = [];--}}
{{--                                            if($editProduct->colors!=''){--}}
{{--                                                $product_colors = explode(',', $editProduct->colors);--}}
{{--                                            }--}}
{{--                                        @endphp--}}
{{--                                        @foreach ($colors as $color)--}}
{{--                                            <input type="checkbox" name="colors[]" class="flat-red"--}}
{{--                                                   value="{{ $color->id }}" {{ in_array($color->id,$product_colors) ? 'checked="checked"' : '' }} >--}}
{{--                                            <span class="text-block">{{ "&nbsp; ".$color->name }}</span> <span--}}
{{--                                                    style="display: inline-block;vertical-align: middle;width:40px;height:20px;background-color:{{$color->color_code}};"></span>--}}
{{--                                            <br/>--}}
{{--                                        @endforeach--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <hr/>
                                <div class="form-group">
                                    <label for="status" class="col-sm-2 control-label">Visibility</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="status">
                                            <option value="1" {{ (old('status') == 1 || $editProduct->status == 1) ? 'selected="selected"' : '' }}>
                                                Enable
                                            </option>
                                            <option value="2" {{ (old('status') == 2 || $editProduct->status == 2)  ? 'selected="selected"' : '' }}>
                                                Disable
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <hr/>
{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label" for="extra_special">Make This Extra Special</label>--}}
{{--                                    <div class="col-sm-10">--}}
{{--                                        <input type="checkbox" name="extra_special" id="extra_special" class="flat-red" value="1" {{ (old('extra_special') == 1 || $editProduct->extra_special == 1) ? 'checked="checked"' : '' }}>{{ "&nbsp; Enable to Make This Extra Special " }}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="extra_special">Make This Extra Special Double Flower</label>
                                    <div class="col-sm-10">
                                        <input type="checkbox" name="extra_special" id="extra_special" class="flat-red" value="1" {{ (old('extra_special') == 1 || $editProduct->extra_special == 1) ? 'checked="checked"' : '' }}>{{ "&nbsp; Enable to Make This Extra Special " }}>{{ "&nbsp; Enable to Make This Extra Special : Add Double Flower" }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="extra_special_vase">Make This Extra Special Vase</label>
                                    <div class="col-sm-10">
                                        <input type="checkbox" name="extra_special_vase" id="extra_special_vase" class="flat-red" value="1" {{ (old('extra_special_vase') == 1 || $editProduct->extra_special_vase == 1) ? 'checked="checked"' : '' }}>{{ "&nbsp; Enable to Make This Extra Special " }}>{{ "&nbsp; Enable to Make This Extra Special : Add Glass Vase" }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputEnableMostPopularProduct">Most
                                        Popular Product</label>
                                    <div class="col-sm-10">
                                        <input type="checkbox" name="popular_product" class="flat-red"
                                               value="1" {{ (old('popular_product') == 1 || $editProduct->popular_product == 1) ? 'checked="checked"' : '' }}>{{ "&nbsp; Enable as a most popular product " }}
                                    </div>
                                </div>
{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label" for="inputEnableFeaturedProduct">Featured--}}
{{--                                        Product</label>--}}
{{--                                    <div class="col-sm-10">--}}
{{--                                        <input type="checkbox" name="featured_product" class="flat-red"--}}
{{--                                               value="1" {{ (old('featured_product') == 1 || $editProduct->featured_product == 1) ? 'checked="checked"' : '' }}>{{ "&nbsp; Enable as a featured product " }}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputEnableRelatedProduct">Related
                                        Product</label>
                                    <div class="col-sm-10">
                                        <input type="checkbox" name="related_product" class="flat-red"
                                               value="1" {{ (old('related_product') == 1 || $editProduct->related_product == 1) ? 'checked="checked"' : '' }}>{{ "&nbsp; Enable as a related product " }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="add_on_product">Add-On
                                        Product</label>
                                    <div class="col-sm-10">
                                        <input type="checkbox" name="add_on_product" class="flat-red"
                                               value="1" id="add_on_product" {{ (old('add_on_product') == 1 || $editProduct->add_on_product == 1) ? 'checked="checked"' : '' }}>{{ "&nbsp; Enable as a Add-On product " }}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <hr/>
                        <h3> Shipping </h3>
                        <div class="form-group">
                            <label for="product_weight" class="col-sm-2 control-label">Weight (lbs)</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control number" name="product_weight" id="product_weight"
                                       value="{{ $editProduct->product_weight > 0 ? $editProduct->product_weight : old('product_weight') }}"
                                       placeholder="Weight">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="product_length" class="col-sm-2 control-label">Dimensions (in)</label>
                            <div class="col-sm-3">
                                <input id="product_length" placeholder="Length" class="form-control number" size="6"
                                       type="text" name="product_length"
                                       value="{{ $editProduct->product_length > 0 ? $editProduct->product_length : old('product_length') }}">
                            </div>
                            <div class="col-sm-3">
                                <input id="product_width" placeholder="Width" class="form-control number" size="6"
                                       type="text" name="product_width"
                                       value="{{ $editProduct->product_width > 0 ? $editProduct->product_width : old('product_width') }}">
                            </div>
                            <div class="col-sm-3">
                                <input id="product_height" placeholder="Height" class="form-control number" size="6"
                                       type="text" name="product_height"
                                       value="{{ $editProduct->product_height > 0 ? $editProduct->product_height : old('product_height') }}">
                            </div>
                        </div>

                        <hr/>
                        <div class="row">

                            <!-- /.col -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="page_title" class="col-sm-2 control-label">Page Title</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" rows="3" id="page_title" name="page_title"
                                                  placeholder="Page Title.">{{ $editProduct->page_title != '' ? $editProduct->page_title : old('page_title') }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="page_keyword" class="col-sm-2 control-label">Page Keyword</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" rows="3" id="page_keyword" name="page_keyword"
                                                  placeholder="Page Keyword.">{{ $editProduct->page_keyword != '' ? $editProduct->page_keyword : old('page_keyword') }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="page_description" class="col-sm-2 control-label">Page
                                        Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" rows="3" id="page_description"
                                                  name="page_description"
                                                  placeholder="Page Description.">{{ $editProduct->page_description != '' ? $editProduct->page_description : old('page_description') }}</textarea>
                                    </div>
                                </div>


                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info pull-right">Save</button>
                                </div>

                            </div>
                        </div>
                        <!-- /.box-body -->
                </form>

            </div>
            <!-- /.box -->
        </div>
        <!--/.col (left) -->
        <!-- right column -->

        <!--/.col (right) -->
    </div>
    <!-- /.row -->
@endsection

@section('styles')
    <!-- CK Editor -->
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/datepicker/datepicker3.css') }}">
    <style>
        span.text-block {
            width: 5% !important;
            display: inline-block;
        }

        .img-wrap {
            position: relative;
            display: inline-block;
            border: 1px red solid;
            font-size: 0;
        }

        .img-wrap .close {
            position: absolute;
            top: 2px;
            right: 2px;
            z-index: 100;
            background-color: #FFF;
            padding: 5px 2px 2px;
            color: #000;
            font-weight: bold;
            cursor: pointer;
            opacity: .2;
            text-align: center;
            font-size: 22px;
            line-height: 10px;
            border-radius: 50%;
        }

        .img-wrap:hover .close {
            opacity: 1;
        }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@stop

@section('scripts')
    <!-- CK Editor -->
    <script src="{{ asset('assets/admin/plugins/ckeditor/ckeditor.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets/admin/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/select2/select2.full.min.js') }}"></script>
    <script>

        $(function () {
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('editor1', {
                extraPlugins: 'uploadimage',

                filebrowserBrowseUrl: "{{ asset('assets/admin/plugins/ckfinder/ckfinder.html') }}",
                filebrowserImageBrowseUrl: "{{ asset('assets/admin/plugins/ckfinder/ckfinder.html?type=Images') }}",
                filebrowserUploadUrl: "{{ asset('assets/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}",
                filebrowserImageUploadUrl: "{{ asset('assets/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') }}"
            });
        });
        $(function () {
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('editor2', {
                extraPlugins: 'uploadimage',

                filebrowserBrowseUrl: "{{ asset('assets/admin/plugins/ckfinder/ckfinder.html') }}",
                filebrowserImageBrowseUrl: "{{ asset('assets/admin/plugins/ckfinder/ckfinder.html?type=Images') }}",
                filebrowserUploadUrl: "{{ asset('assets/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}",
                filebrowserImageUploadUrl: "{{ asset('assets/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') }}"
            });
        });
        $(function () {
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('editor3', {
                extraPlugins: 'uploadimage',

                filebrowserBrowseUrl: "{{ asset('assets/admin/plugins/ckfinder/ckfinder.html') }}",
                filebrowserImageBrowseUrl: "{{ asset('assets/admin/plugins/ckfinder/ckfinder.html?type=Images') }}",
                filebrowserUploadUrl: "{{ asset('assets/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}",
                filebrowserImageUploadUrl: "{{ asset('assets/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') }}"
            });
        });
        $(function () {
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('editor4', {
                extraPlugins: 'uploadimage',

                filebrowserBrowseUrl: "{{ asset('assets/admin/plugins/ckfinder/ckfinder.html') }}",
                filebrowserImageBrowseUrl: "{{ asset('assets/admin/plugins/ckfinder/ckfinder.html?type=Images') }}",
                filebrowserUploadUrl: "{{ asset('assets/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}",
                filebrowserImageUploadUrl: "{{ asset('assets/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') }}"
            });
        });

        //--------------------------------------------------------------------------------------------------------------
        function doSomethingBobTheBuilder(time){
            $(document).find('.option_append').css({'background':'#ededed', 'padding': '10px 0px', 'margin-top': '10px'});
            var i = $(document).find('.option_append').length;
            // $(document).find('.option_append').attr('data-content','Variable '+i);


            $(document).find('.option_append .form-group .hs-expand-collapse').each(function(key){
                if($(this).attr('id').length === 0){
                    var signoneid = 'deanI'+time+key;
                    $(this).attr("id", signoneid);
                    $(this).on('click', function(){
                        $(this).parent().find('.col-sm-10').toggle('slow');
                        $(this).toggleClass("fa-minus");
                    });
                }
            });
            $(document).find('.option_append .form-group .hs-expand-collapse-care').each(function(key){
                if($(this).attr('id').length === 0){
                    var signtwoid = 'deanIn'+time+key;
                    $(this).attr("id", signtwoid);
                    $(this).on('click', function(){
                        $(this).parent().find('.col-sm-10').toggle('slow');
                        $(this).toggleClass("fa-minus");
                    });
                }
            });
            $(document).find('.option_append .form-group .hs-expand-collapse-delivery-info').each(function(key){
                if($(this).attr('id').length === 0){
                    var signthreeid = 'deanInf'+time+key;
                    $(this).attr("id", signthreeid);
                    $(this).on('click', function(){
                        $(this).parent().find('.col-sm-10').toggle('slow');
                        $(this).toggleClass("fa-minus");
                    });
                }
            });
            $(document).find('.option_append .form-group .hs-expand-collapse-gift').each(function(key){
                if($(this).attr('id').length === 0){
                    var signFourId = 'deanInfo'+time+key;
                    $(this).attr("id", signFourId);
                    $(this).on('click', function(){
                        $(this).parent().find('.col-sm-10').toggle('slow');
                        $(this).toggleClass("fa-minus");
                    });
                }
            });
            //-------------------------------------------------------------
            $(document).find('.option_append .form-group textarea').each(function(key){
                if($(this).attr('id').length === 0){
                    var textId = 'editor'+time+key;
                    $(this).attr("id", textId);
                    $(function () {
                        $(".select2").select2();
                        // Replace the <textarea id="editor1"> with a CKEditor
                        // instance, using default configuration.
                        CKEDITOR.replace(textId,{
                            extraPlugins: 'uploadimage',
                            filebrowserBrowseUrl: "{{ asset('assets/admin/plugins/ckfinder/ckfinder.html') }}",
                            filebrowserImageBrowseUrl: "{{ asset('assets/admin/plugins/ckfinder/ckfinder.html?type=Images') }}",
                            filebrowserUploadUrl: "{{ asset('assets/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}",
                            filebrowserImageUploadUrl: "{{ asset('assets/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images')}}"
                        });
                    });
                }
            });
        }
        //--------------------------------------------------------------------------------------------------------------

        $(document).ready(function(){
            $(document).find('.edit-product-option').find('textarea').each(function(key){
                    key = key+$(this).attr('data-id');
                    console.log($(this).attr('id', 'editor'+key));
                    //  $('.edit-product-option').find('textarea').attr('id', 'editor'+key);
                    $(function () {
                        $(".select2").select2();
                        // Replace the <textarea id="editor1"> with a CKEditor
                        // instance, using default configuration.
                        CKEDITOR.replace('editor'+key,{
                            extraPlugins: 'uploadimage',
                            filebrowserBrowseUrl: "{{ asset('assets/admin/plugins/ckfinder/ckfinder.html') }}",
                            filebrowserImageBrowseUrl: "{{ asset('assets/admin/plugins/ckfinder/ckfinder.html?type=Images') }}",
                            filebrowserUploadUrl: "{{ asset('assets/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}",
                            filebrowserImageUploadUrl: "{{ asset('assets/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images')}}"
                        });
                    });
            });

            {{--$(function () {--}}
            {{--    // Replace the <textarea id="editor1"> with a CKEditor--}}
            {{--    // instance, using default configuration.--}}
            {{--    CKEDITOR.replace('', {--}}
            {{--        extraPlugins: 'uploadimage',--}}

            {{--        filebrowserBrowseUrl: "{{ asset('assets/admin/plugins/ckfinder/ckfinder.html') }}",--}}
            {{--        filebrowserImageBrowseUrl: "{{ asset('assets/admin/plugins/ckfinder/ckfinder.html?type=Images') }}",--}}
            {{--        filebrowserUploadUrl: "{{ asset('assets/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}",--}}
            {{--        filebrowserImageUploadUrl: "{{ asset('assets/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') }}"--}}
            {{--    });--}}
            {{--});--}}
        });



        //--------------------------------------------------------------------------------------------------------------

        //Date picker
        $('#datepicker').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
        });

        function add_more_attributes() {
            $.ajax({
                type: "GET",
                data: {
                    attributes: 'add-more',
                },
                url: base_url + '/admin/products/add-more-attributes',
                success: function (data) {
                    if (data) {
                        $('.attributes_div').append(data);
                    }
                }
            });
        }

        $(document).on('click', 'a.remove_attr', function () {
            //$(this).parents('.form-group').remove();
            $(this).parents('.option_append').remove();
        });

        $(document).on('keyup', '.number', function () {
            var $myInput = jQuery(this);
            if (/^[0-9.]+$/.test($myInput.val())) {
                //alert('correct');
                return true;
            } else {
                $myInput.val($myInput.val().slice(0, -1));
                return false;
            }

        });

        /*$(document).on('keydown','.number', function(e) {
               if(!((e.keyCode > 95 && e.keyCode < 106)
                 || (e.keyCode > 47 && e.keyCode < 58)
                 || e.keyCode == 8)) {
                   return false;
               }
       });*/
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });
        $('input').on('ifChanged', function (event) {
            $(event.target).trigger('change');
        });
        $(document).on('change', 'input[type="checkbox"].attr_check', function () {
            var id = $(this).attr('data-id');
            if ($(this).is(':checked')) {
                $('.attr_values_' + id).show();
            } else {
                $('.attr_values_' + id).hide();
            }
        });

        // $(document).on('change', 'input[type="checkbox"].checked_types', function () {
        //     $('.categories').hide();
        //     $('.categories').html('');
        //     var str = '';
        //     $(".checked_types").each(function () {
        //         if ($(this).is(':checked')) {
        //             var id = $(this).attr('data-id');
        //             str += id + '~~~';
        //         }
        //
        //     });
        //
        //     str = str.slice(0, -3);
        //
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         type: "POST",
        //         data: {
        //             types: str,
        //         },
        //         url: base_url + '/admin/products/get-product-categories',
        //         success: function (data) {
        //             if (data) {
        //                 $('.categories').html(data);
        //                 $('.categories').show();
        //                 $('input[type="checkbox"].flat-red-check').iCheck({
        //                     checkboxClass: 'icheckbox_flat-green',
        //                     radioClass: 'iradio_flat-green'
        //                 });
        //             }
        //         }
        //     });
        //
        // });
        //-------------------------------------------------------------
        // $(window).load(function () {
        //     $('input[type="checkbox"].flat-red-check').iCheck({
        //          checkboxClass: 'icheckbox_flat-green',
        //          radioClass: 'iradio_flat-green'
        //     });
        // });
        //--------------------------------------------------------------
        {{--$(window).load(function () {--}}
        {{--    $('.categories').hide();--}}
        {{--    $('.categories').html('');--}}
        {{--    var str = '';--}}
        {{--    $(".checked_types").each(function () {--}}
        {{--        if ($(this).is(':checked')) {--}}
        {{--            var id = $(this).attr('data-id');--}}
        {{--            str += id + '~~~';--}}
        {{--        }--}}

        {{--    });--}}

        {{--    str = str.slice(0, -3);--}}
        {{--    if (str.length > 0) {--}}
        {{--        $.ajax({--}}
        {{--            headers: {--}}
        {{--                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
        {{--            },--}}
        {{--            type: "POST",--}}
        {{--            data: {--}}
        {{--                types: str,--}}
        {{--                cats: '{{ $editProduct->cat_ids }}',--}}
        {{--            },--}}
        {{--            url: base_url + '/admin/products/get-product-categories',--}}
        {{--            success: function (data) {--}}
        {{--                if (data) {--}}
        {{--                    $('.categories').html(data);--}}
        {{--                    $('.categories').show();--}}
        {{--                    $('input[type="checkbox"].flat-red-check').iCheck({--}}
        {{--                        checkboxClass: 'icheckbox_flat-green',--}}
        {{--                        radioClass: 'iradio_flat-green'--}}
        {{--                    });--}}
        {{--                }--}}
        {{--            }--}}
        {{--        });--}}
        {{--    }--}}
        {{--})--}}

        $(document).on('change', 'input[type="radio"].product_type', function () {
            var sid = $(this).val();
            if (sid == 1) {
                $('div.variation').hide();
                $('div.simple').show();
            } else {
                $('div.simple').hide();
                $('div.variation').show();
            }
        });

        function add_more_options() {
            $.ajax({
                type: "GET",
                data: {
                    attributes: 'add-more',
                },
                url: base_url + '/admin/products/add-more-options',
                success: function (data) {
                    if (data) {
                        $('.options_div').append(data);
                        doSomethingBobTheBuilder($.now());
                    }
                }
            });
        }

        $(document).on('click', 'a.remove_option', function () {
            $(this).parents('.option_append').remove();
        });

    </script>
    <script>
        $(document).on('click', '.expand-collapse', function(){
            $("#description").toggle("slow");
            $(this).toggleClass("fa-minus");
            // $(this).addClass("fa-minus");
        })

        $(document).on('click', '.expand-collapse-care', function(){
            $("#care_instruction").toggle("slow");
            $(this).toggleClass("fa-minus");
            // $(this).addClass("fa-minus");
        })

        $(document).on('click', '.expand-collapse-delivery-info', function(){
            $("#delivery_info").toggle("slow");
            $(this).toggleClass("fa-minus");
            // $(this).addClass("fa-minus");
        })

        $(document).on('click', '.expand-collapse-gift', function(){
            $("#gift_contain").toggle("slow");
            $(this).toggleClass("fa-minus");
            // $(this).addClass("fa-minus");
        })

        //------------------------ Variation ------------------------

        $(document).on('click', '.var-expand-collapse', function(){
            $(this).parent('.form-group').children(".var_description").toggle("slow");
            $(this).toggleClass("fa-minus");
            // $(this).addClass("fa-minus");
        })

        $(document).on('click', '.var-expand-collapse-care', function(){
            $(this).parent('.form-group').children(".var_care_instruction").toggle("slow");
            $(this).toggleClass("fa-minus");
        })

        $(document).on('click', '.var-expand-collapse-delivery-info', function(){
            $(this).parent('.form-group').children(".var_delivery_info").toggle("slow");
            $(this).toggleClass("fa-minus");
        })

        $(document).on('click', '.var-expand-collapse-gift', function(){
            $(this).parent('.form-group').children(".var_gift_contain").toggle("slow");
            $(this).toggleClass("fa-minus");
        })
        //------------------------ Variation ------------------------
    </script>
    <script>

        function selectAll() {
            $(".cityEditSelect .icheckbox_flat-green").attr("aria-checked", "true");
            $(".cityEditSelect .icheckbox_flat-green").addClass("checked");
            $(".cityEditSelect input[type=checkbox]").attr("checked", "checked");
        }

        function UnSelectAll() {
            $(".cityEditSelect .icheckbox_flat-green").attr("aria-checked", "false");
            $(".cityEditSelect .icheckbox_flat-green").removeClass("checked");
            $(".cityEditSelect input[type=checkbox]").removeAttr("checked");
        }
    </script>
@stop