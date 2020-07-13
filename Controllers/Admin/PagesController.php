<?php

namespace App\Http\Controllers\Admin;
use App\ProductCities;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pages;
use App\Libraries\Slug;
use Validator;
use Session;
class PagesController extends Controller
{
    public function index(){
		$pages = Pages::orderBy('title', 'asc')->get();
		return view('admin.pages',compact('pages'));
	}
	
	//Add page form
	public function addPage(){
		return view('admin.add-page');
	}
	
	//Save page to database
	public function savePage(Request $request){
			$validator = Validator::make($request->all(),[
    			'title' => 'required',
                'header_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
    		]);
			
			if ($validator->fails()) {
            		return redirect('admin/pages/add/')
                        ->withErrors($validator)
                        ->withInput();
        	}

            $filename = '';

            if ($request->hasFile('header_image'))
            {
                $image = $request->file('header_image');

                $filename  = basename($image->getClientOriginalName(), '.'.$image->getClientOriginalExtension()).'-'.rand(11111,99999) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('assets/uploads/pages/'), $filename);
            }

			$title				= $request->input('title');
			$description		= $request->input('description');
			$footer_menu		= $request->input('footer_menu');
			$page_title     	= $request->input('page_title');
			$page_keyword   	= $request->input('page_keyword');
            $page_description 	= $request->input('page_description');
            $page_map           = $request->input('map');
			
			$slugClass = new Slug();
    		$pageSlug = $slugClass->make($title, 'pages', 'slug');
			
			$page  = new Pages();
			
			$page->title = $title;
			$page->description = $description;
			$page->footer_menu = ($footer_menu == 1) ? 1 : 2; 
			$page->page_title     		= $page_title;
			$page->page_keyword   		= $page_keyword;
			$page->page_description 	= $page_description;
            if(!empty($filename)) {
                $page->header_image = 'assets/uploads/pages/' . $filename;
            }
            else{
                $page->header_image = '';
            }
			$page->map 	                = $page_map;

			$page->slug					= $pageSlug;
			
			$page->created_at 			= date('Y-m-d H:i:s');
			$page->updated_at 			= date('Y-m-d H:i:s');
			if($page->save()){
				Session::flash('message', "Page created successfully.");
				return redirect()->action('Admin\PagesController@index');
			}else{
				return redirect()->action('Admin\PagesController@index');
			}
			
	}
	
	//Edit page form
	public function editPage($id){
		$editPage   = DB::table('pages')->find($id);
		if(!empty($editPage)){
        	return view('admin.edit-page',compact('editPage'));
		}else{
			return redirect()->action('Admin\PagesController@index');
		}
	}
	
	//Update page to database
	public function updatePage($id, Request $request){
		$editPage   = DB::table('pages')->find($id);

		if(!empty($editPage)){
        							
			$validator = Validator::make($request->all(),[
                'title'        => 'required',
                'header_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
    		]);	
			
			if ($validator->fails()) {
            		return redirect('admin/pages/edit/'.$id)
                        ->withErrors($validator)
                        ->withInput();
        	}

            $filename = '';

            if ($request->hasFile('header_image'))
            {
                $image = $request->file('header_image');

                $filename  = basename($image->getClientOriginalName(), '.'.$image->getClientOriginalExtension()).'-'.rand(11111,99999) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('assets/uploads/pages/'), $filename);
            }

			$title				= $request->input('title');
			$description		= $request->input('description');
			$footer_menu		= $request->input('footer_menu');	
			$page_title     	= $request->input('page_title');
			$page_keyword   	= $request->input('page_keyword');
			$page_description 	= $request->input('page_description');

			if(!empty($filename)){
                $header_image   = 'assets/uploads/pages/'.$filename;
            }
			else{
			    $header_image   = $editPage->header_image;
            }
            $map 	            = $request->input('map');
			
			DB::table('pages')
            		->where('id',$id)
            		->update(['title' => $title, 'description'=>$description, 'footer_menu' => ($footer_menu == 1) ? 1 : 2,  'page_title' => $page_title, 'page_keyword' => $page_keyword, 'page_description' => $page_description, 'header_image' => $header_image,'map' => $map, 'updated_at'=>date('Y-m-d H:i:s')]);
					
			Session::flash('message', "Page updated successfully.");
				return redirect()->action('Admin\PagesController@index');		
			
		}else{
			return redirect()->action('Admin\PagesController@index');
		}
	}

	
	//DElETE Page 
	public function deletePage($id){
		$deletePage   = Pages::find($id);
		if(!empty($deletePage)){	
				$deletePage->delete();
				Session::flash('message', "Page deleted successfully.");
				return redirect('admin/pages');
		}else{
			return redirect('admin/pages');
		}
	}
}
