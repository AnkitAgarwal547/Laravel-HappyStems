@extends('layouts.adminapp')

@section('content')

    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Product City</h3>
                    <a href="{{ url('/admin/products/cities') }}" class="btn btn-warning pull-right">Cancel</a>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form role="form" method="POST" id="admin_login" action="{{ url('/admin/product/city/update/'.$editCity->id) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <!-- text input -->
                        <div class="form-group{{ $errors->has('cityname') ? ' has-error' : '' }}">
                            <label>Name</label>
                            <input type="text" class="form-control" name="cityname" value="{{ $editCity->name != '' ? $editCity->name : old('cityname') }}" placeholder="The name is how it appears on your site.">
                        </div>
                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" class="form-control" name="slug" value="{{ $editCity->slug != '' ? $editCity->slug : old('slug') }}" placeholder="The 'slug' is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.">
                        </div>


                        <div class="form-group">
                            <label>Description</label>
                            <textarea id="editor1" name="description" rows="10" cols="80">{{ $editCity->description != '' ? $editCity->description :  old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Thumbnail</label>
                            <br/><img src="{{URL::asset($editCity->image)}}" width="70">
                            <input type="file" id="exampleInputFile" name="thumbnail" accept="image/*">
                        </div>
                        <hr />
                        <div class="form-group">
                            <label>Meta Title</label>
                            <textarea class="form-control" rows="3" name="page_title" placeholder="Meta Title.">{{ $editCity->page_title != '' ? $editCity->page_title : old('page_title') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Meta Keyword</label>
                            <textarea class="form-control" rows="3" name="page_keyword" placeholder="Meta Keyword.">{{ $editCity->page_keyword != '' ? $editCity->page_keyword : old('page_keyword') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Meta Description</label>
                            <textarea class="form-control" rows="3" name="page_description" placeholder="Meta Description.">{{ $editCity->page_description != '' ? $editCity->page_description : old('page_description') }}</textarea>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Update Product City</button>
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
    </script>
@stop