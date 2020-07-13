@extends('layouts.adminapp')

@section('content')  
		
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Product Type</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form" method="POST" id="admin_login" action="{{ url('/admin/products/edit-type/'.$editType->id) }}" enctype="multipart/form-data">
              {{ csrf_field() }}
                <!-- text input -->
                <div class="form-group{{ $errors->has('typename') ? ' has-error' : '' }}">
                  <label>Name</label>
                  <input type="text" class="form-control" name="typename" value="{{ $editType->title != '' ? $editType->title : old('typename') }}" placeholder="The name is how it appears on your site.">
                </div>
                <div class="form-group">
                  <label>Slug</label>
                  <input type="text" class="form-control" name="slug" value="{{ $editType->slug != '' ? $editType->slug : old('slug') }}" placeholder="The 'slug' is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.">
                </div>

                 <div class="form-group">
                  	<label>Product Categories</label><br/>
                    @php
                    	$cat = [];
                        if($editType->categories!=''){
                        	$cat = explode(',', $editType->categories);
                        }
                    	
                    @endphp
                    @foreach ($categories as $category)
                                    <input type="checkbox" name="categories[]" class="flat-red" value="{{ $category->id }}" {{ in_array($category->id,$cat) ? 'checked="checked"' : '' }}>{{ "&nbsp; ".$category->name }} <br/>
                                    
                    @endforeach
                </div>
                
                <div class="form-group">
                  <label>Description</label>
                  <textarea class="form-control" rows="3" name="description" placeholder="The description is not prominent by default; however, some themes may show it.">{{ $editType->description != '' ? $editType->description :  old('description') }}</textarea>
                </div>
                
                <div class="form-group">
                  <label>Thumbnail</label>
                  <br/><img src="{{ URL::asset($editType->thumbnail) }}" width="70">
                  <input type="file" id="exampleInputFile" name="thumbnail" accept="image/*">
                </div>
                
                <div class="form-group">
                  <label>Main Image</label>
                   <br/><img src="{{ URL::asset($editType->main_image) }}" width="70">
                  <input type="file" name="main_image" accept="image/*">
                </div>
                
                <div class="form-group">
                  <label>Featured Image</label>
                   <br/><img src="{{ URL::asset($editType->featured_image) }}" width="70">
                  <input type="file" name="featured_image" accept="image/*">
                </div>
                
                
                <hr />
                <div class="form-group">
                  <label>Page Title</label>
                  <textarea class="form-control" rows="3" name="page_title" placeholder="Page Title.">{{ $editType->page_title != '' ? $editType->page_title : old('page_title') }}</textarea>
                </div>
                
                <div class="form-group">
                  <label>Page Keyword</label>
                  <textarea class="form-control" rows="3" name="page_keyword" placeholder="Page Keyword.">{{ $editType->page_keyword != '' ? $editType->page_keyword : old('page_keyword') }}</textarea>
                </div>
                
                <div class="form-group">
                  <label>Page Description</label>
                  <textarea class="form-control" rows="3" name="page_description" placeholder="Page Description.">{{ $editType->page_description != '' ? $editType->page_description : old('page_description') }}</textarea>
                </div>
                
				<div class="box-footer">
                	<button type="submit" class="btn btn-info pull-right">Edit Product Type</button>
              	</div>
                

              </form>
            </div>
            <!-- /.box-body -->
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
<style>
span.text-block{width: 5% !important;display: inline-block;}
</style>
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/select2.min.css') }}">
@stop

@section('scripts')
<script src="{{ asset('assets/admin/plugins/select2/select2.full.min.js') }}"></script>
<script>	
	
  	$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
  	});
	
	$('input').on('ifChanged', function (event) { $(event.target).trigger('change'); });
	$(document).on('change','input[type="checkbox"].attr_check', function() { 
		var id =$(this).attr('data-id');
			if($(this).is(':checked')) 
			{
				$('.attr_values_'+id).show();
			}else{
				$('.attr_values_'+id).hide();
			}
	});
	
	 
	
</script>
@stop