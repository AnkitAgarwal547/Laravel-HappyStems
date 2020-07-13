@extends('layouts.adminapp')

@section('content')

      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-plus-square-o"></i> Add Page</h3>
            </div>
            <!-- /.box-header -->
            
              <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/pages/add')}}" enctype="multipart/form-data">
              <div class="box-body">
              {{ csrf_field() }}
                <!-- text input -->            
                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                  <label for="link" class="col-sm-2 control-label">Title</label>
                  <div class="col-sm-10">
                  	<input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
                  </div>  
                </div>
                
                <div class="form-group">
                  <label for="link" class="col-sm-2 control-label">Description</label>
                  <div class="col-sm-10">
                  		 <textarea id="editor1" name="description" rows="10" cols="80">
                                          {{ old('description') }}
                    	</textarea>
                  </div>
                 </div>

                <div class="form-group">
                  <label for="header_image" class="col-sm-2 control-label">Header Image</label>
                  <div class="col-sm-10">
                      <input type="file" name="header_image" id="header_image" class="form-control" accept="image/*">
                  </div>
                </div>

                <div class="form-group">
                  <label for="map_iframe" class="col-sm-2 control-label">Map URL</label>
                  <div class="col-sm-10">
                      <input type="text" name="map" class="form-control" id="map_iframe">
                  </div>
                </div>
                <div class="form-group">
                      <label class="col-sm-2 control-label" for="inputEnableRelatedProduct">Footer Menu</label>
                      <div class="col-sm-10">  
                        <input type="checkbox" name="footer_menu" class="flat-red" value="1" {{ old('footer_menu') == 1 ? 'checked="checked"' : '' }}>{{ "&nbsp; Enable as a footer menu " }}
                	 </div>
                </div> 
                <hr/>
                <div class="form-group">
                  <label for="page_title" class="col-sm-2 control-label">Page Title</label>
                  <div class="col-sm-10">
                  	<textarea class="form-control" rows="3" id="page_title" name="page_title" placeholder="Page Title.">{{ old('page_title') }}</textarea>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="page_keyword" class="col-sm-2 control-label">Page Keyword</label>
                  <div class="col-sm-10">
                  	<textarea class="form-control" rows="3" id="page_keyword" name="page_keyword" placeholder="Page Keyword.">{{ old('page_keyword') }}</textarea>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="page_description" class="col-sm-2 control-label">Page Description</label>
                  <div class="col-sm-10">
                  	<textarea class="form-control" rows="3" id="page_description" name="page_description" placeholder="Page Description.">{{ old('page_description') }}</textarea>
                  </div>
                </div>
                
                
				<div class="box-footer">
                	<button type="submit" class="btn btn-info pull-right">Save</button>
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
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/select2.min.css') }}">
@stop
@section('scripts')
<!-- CK Editor -->
<script src="{{ asset('assets/admin/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/select2/select2.full.min.js') }}"></script>
<script>
  $(function () {
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
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
  });
</script>

@stop