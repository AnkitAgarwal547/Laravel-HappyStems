<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Session;

class SliderController extends Controller
{
    public function index(){
		$slider = DB::table('slider')->get();
		return view('admin.home-slider.slider',compact('slider'));
	}
	
	//Add slide form
	public function addSlide(){
		return view('admin.home-slider.add-slide');
	}
	
	//Save slide to database
	public function saveSlide(Request $request){
		
		$validator = Validator::make($request->all(),[
				'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
    		]);	
			
			if ($validator->fails()) {
            		return redirect('admin/home-slider/add-slide')
                        ->withErrors($validator)
                        ->withInput();
        	}
			
			$filename = '';
			
			if ($request->hasFile('image'))
			{
				$image = $request->file('image');
				
				$filename  = basename($image->getClientOriginalName(), '.'.$image->getClientOriginalExtension()).'-'.rand(11111,99999) . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('assets/uploads/slider/'), $filename);
			}
			
			
			$id = DB::table('slider')->insertGetId(
				['title' => $request->input('title'), 'description' => $request->input('description'), 'link' => $request->input('link'), 'image' => 'assets/uploads/slider/'.$filename, 'visibility' => $request->input('visibility'),  'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')]
			);
			
			if($id){
				Session::flash('message', "Slide created successfully.");
				return redirect()->action('Admin\SliderController@index');
			}else{
				Session::flash('error', "Slide creation fails. Please try again after some time.");
				return redirect()->action('Admin\SliderController@index');
			}	
	}
	
	//Edit slide form
	public function editSlide($id){
		$editSlide   = DB::table('slider')->find($id);
		if(!empty($editSlide)){
        	return view('admin.home-slider.edit-slide',compact('editSlide'));
		}else{
			return redirect()->action('Admin\SliderController@index');
		}
	}
	
	//Update slide to database
	public function updateSlide($id, Request $request){
		$editSlide   = DB::table('slider')->find($id);
		if(!empty($editSlide)){

			if ($request->hasFile('image'))
			{
				$image = $request->file('image');
				
				$filename  = basename($image->getClientOriginalName(), '.'.$image->getClientOriginalExtension()).'-'.rand(11111,99999) . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('assets/uploads/slider/'), $filename);
				DB::table('slider')
            		->where('id',$id)
            		->update(['title' => $request->input('title'), 'description' => $request->input('description'), 'link' => $request->input('link'), 'image' => 'assets/uploads/slider/'.$filename,  'visibility' => $request->input('visibility'), 'updated_at'=>date('Y-m-d H:i:s')]);
			}
			
			else{
				DB::table('slider')
            		->where('id',$id)
            		->update(['title' => $request->input('title'), 'description' => $request->input('description'), 'link' => $request->input('link'),  'visibility' => $request->input('visibility'), 'updated_at'=>date('Y-m-d H:i:s')]);
			}

			Session::flash('message', "Slide updated successfully.");
				return redirect()->action('Admin\SliderController@index');
			
		 }else{
			 return redirect()->action('Admin\SliderController@index');
		 }
	}
	
	//delete slide form
	public function deleteSlide($id)
    {
        $delSlide   = DB::table('slider')->find($id);
		if(!empty($delSlide)){
			
			DB::table('slider')->where('id', '=', $id)->delete();
			Session::flash('message', "Slide deleted successfully.");
				return redirect()->action('Admin\SliderController@index');
		}else{
			return redirect()->action('Admin\SliderController@index');
		}
    }
}
