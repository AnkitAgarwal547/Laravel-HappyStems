@extends('layouts.adminapp')

@section('content')

      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-location-arrow"></i> Edit Attribute</h3>
            </div>
            <!-- /.box-header -->
            
              <form class="form-horizontal" role="form" method="POST" id="admin_login" action="{{ url('/admin/products/edit-attribute').'/'.$editAttribute->id }}" enctype="multipart/form-data">
              <div class="box-body">
              {{ csrf_field() }}
                <!-- text input -->            
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                  <label for="name" class="col-sm-2 control-label">Name</label>
                  <div class="col-sm-10">
                  	<input type="text" class="form-control" id="name" name="name" value="{{ $editAttribute->name != '' ? $editAttribute->name : old('name') }}">
                  </div>  
                </div>
                <div class="attributes_div">
                @php $a = 1; @endphp
                @foreach ($attrVal as $value)
                
                    <div class="form-group post clearfix">
                      <input type="hidden" name="attr_id[]" value="{{$value->id}}"/>
                      <label for="attr_val" class="col-sm-2 control-label">@if($a == 1)
                										Value
                                                      @endif</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" name="attr_val[{{ $value->id }}]" value="{{ $value->attribute_value}}">
                      </div>  
                      <div class="col-sm-2">
                      	@if($a == 1)
                        <a class="btn bg-maroon" href="javascript:;" onclick="add_more_attributes()">
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
                    @php $a++; @endphp
                @endforeach    
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
@section('scripts')

<script>
	function add_more_attributes(){
		$.ajax({
				type: "GET",
				data: {
					attributes: 'add-more',
				},
				url: base_url + '/admin/products/add-more-attributes',
				success: function(data) {
					if(data) {
						$('.attributes_div').append(data);
					}
				}
		});
	}
	
	$(document).on('click','a.remove_attr', function(){
		$( this ).parents('.form-group').remove();
	});
    
    </script>

@stop
