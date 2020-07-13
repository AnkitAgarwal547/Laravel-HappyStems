@extends('layouts.adminapp')

@section('content')

      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-plus-square-o"></i> Edit Tag</h3>
            </div>
            <!-- /.box-header -->
            
              <form class="form-horizontal" role="form" method="POST" action="{{ url('admin/products/tags/edit').'/'.$editTag->id}}" enctype="multipart/form-data">
              <div class="box-body">
              {{ csrf_field() }}
                <!-- text input -->            
                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                  <label for="link" class="col-sm-2 control-label">Title</label>
                  <div class="col-sm-10">
                  	<input type="text" class="form-control" id="title" name="title" value="{{ $editTag->name !='' ? $editTag->name : old('title') }}">
                  </div>  
                </div>
                
                <div class="form-group">
                  <label for="link" class="col-sm-2 control-label">Description</label>
                  <div class="col-sm-10">
                  		 <textarea name="description"  class="form-control">{{ $editTag->description !='' ? $editTag->description : old('description') }}</textarea>
                  </div>
                </div>
                  
               <div class="form-group">
                  <label for="status" class="col-sm-2 control-label">Status</label>
                   <div class="col-sm-10">
                   		<select class="form-control" name="status">
                  			<option value="1" {{ ( old('status') == 1 || $editTag->status ==1 ) ? 'selected="selected"' : '' }}>Enable</option>
                            <option value="2" {{ ( old('status') == 2 || $editTag->status ==2 ) ? 'selected="selected"' : '' }}>Disable</option>
                  		</select>
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
