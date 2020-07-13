@extends('layouts.adminapp')

@section('content')  
		
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Product Category</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form" method="POST" id="admin_login" action="{{ url('/admin/product-categories/edit/'.$editCategory->id) }}" enctype="multipart/form-data">
              {{ csrf_field() }}
                <!-- text input -->
                <div class="form-group{{ $errors->has('catname') ? ' has-error' : '' }}">
                  <label>Name</label>
                  <input type="text" class="form-control" name="catname" value="{{ $editCategory->name != '' ? $editCategory->name : old('catname') }}" placeholder="The name is how it appears on your site.">
                </div>
                <div class="form-group">
                  <label>Slug</label>
                  <input type="text" class="form-control" name="slug" value="{{ $editCategory->slug != '' ? $editCategory->slug : old('slug') }}" placeholder="The 'slug' is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.">
                </div>

                <div class="form-group">
                  <label>Parent</label>
                  <select class="form-control category-select-box" name="parent">
                    <option value="-1">None</option>
                          @foreach ($categories as $category)
                              <option value="{{ $category->id }}" {{ $category->id == $editCategory->parent ? "selected='selected'" : "" }}>{{ $category->name }}</option>
                              @if(count($category->childs)>0)
                                  @include('admin/manageChild',['childs' => $category->childs, 'selectCategory'=>$editCategory->parent])
                              @endif
                          @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label>Tag</label>
                  <select name="tag" id="tag" class="form-control">
                    <option value="0">None</option>
                    <option value="1" @if($editCategory->tag == 1) selected @endif>New</option>
                    <option value="2" @if($editCategory->tag == 2) selected @endif>Hot</option>
                    <option value="3" @if($editCategory->tag == 3) selected @endif>Popular</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="order">Order (Top most Parent)</label>
                  <input type="number" id="order" class="form-control" name="order" value="@if($editCategory->orders){{ $editCategory->orders }}@else{{ old('order') }}@endif">
                </div>

                <div class="form-group">
                  <label>Description</label>
{{--                  <textarea class="form-control" rows="3" name="description" placeholder="The description is not prominent by default; however, some themes may show it.">{{ $editCategory->description != '' ? $editCategory->description :  old('description') }}</textarea>--}}
                    <textarea id="editor1" name="description" rows="10" cols="80">@if($editCategory->description){{$editCategory->description}} @else{{ old('description') }}@endif</textarea>
                </div>
                
                <div class="form-group">
                  <label>Thumbnail</label>
                  <br/><img src="{{URL::asset($editCategory->image)}}" width="70">
                  <input type="file" id="exampleInputFile" name="thumbnail" accept="image/*">
                </div>
                <hr />
                <div class="form-group">
                  <label>Page Title</label>
                  <textarea class="form-control" rows="3" name="page_title" placeholder="Page Title.">{{ $editCategory->page_title != '' ? $editCategory->page_title : old('page_title') }}</textarea>
                </div>
                
                <div class="form-group">
                  <label>Page Keyword</label>
                  <textarea class="form-control" rows="3" name="page_keyword" placeholder="Page Keyword.">{{ $editCategory->page_keyword != '' ? $editCategory->page_keyword : old('page_keyword') }}</textarea>
                </div>
                
                <div class="form-group">
                  <label>Page Description</label>
                  <textarea class="form-control" rows="3" name="page_description" placeholder="Page Description.">{{ $editCategory->page_description != '' ? $editCategory->page_description : old('page_description') }}</textarea>
                </div>
                
				<div class="box-footer">
                	<button type="submit" class="btn btn-info pull-right">Edit Product Category</button>
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
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/select2.min.css') }}">
@stop
@section('scripts')
  <!-- CK Editor -->
  <script src="{{ asset('assets/admin/plugins/ckeditor/ckeditor.js') }}"></script>
  <!-- bootstrap datepicker -->
  <script src="{{ asset('assets/admin/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

  <script src="{{ asset('assets/admin/plugins/select2/select2.full.min.js') }}"></script>
  <script>

    $(function () {
      $(".select2").select2();
      // Replace the <textarea id="editor1"> with a CKEditor
      // instance, using default configuration.
      CKEDITOR.replace('editor1',{
        extraPlugins: 'uploadimage',

        filebrowserBrowseUrl: "{{ asset('assets/admin/plugins/ckfinder/ckfinder.html') }}",
        filebrowserImageBrowseUrl: "{{ asset('assets/admin/plugins/ckfinder/ckfinder.html?type=Images') }}",
        filebrowserUploadUrl: "{{ asset('assets/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}",
        filebrowserImageUploadUrl: "{{ asset('assets/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') }}"
      });
    });
    $(document).ready(function() {
      $('.category-select-box').select2();
    });
  </script>
@stop