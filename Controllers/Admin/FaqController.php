<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Faqs;
use Session;
use Redirect;
use Auth;
use Validator;

class FaqController extends Controller
{
   public function index(){
		$faqs = Faqs::orderBy('id', 'desc')->get();
		return view('admin.faq.faqs',compact('faqs'));
   }
   
   //Add Faq Form
	public function addFaq(){		
		return view('admin.faq.add-faq');
	}
	
	//Save Faq Form
	public function saveFaq(request $request){
			$validator = Validator::make($request->all(),[
    			 'title' => 'required|min:5'
    		]);
			
			if ($validator->fails()) {
            		return redirect('admin/faq/add')
                        ->withErrors($validator)
                        ->withInput();
        	}
			
			$title				= $request->input('title');
			$description		= $request->input('description');
			$visibility			= $request->input('status');
			
			$faq  = new Faqs();
			
			$faq->title				= $title;
			$faq->description		= $description;
			$faq->visibility		= $visibility;
						
			$faq->created_at 	= date('Y-m-d H:i:s');
			$faq->updated_at 	= date('Y-m-d H:i:s');
			
			if($faq->save()){
				Session::flash('message', "FAQ added successfully.");
				return redirect('admin/faq');
			}else{
				Session::flash('error', "FAQ creation fails. Please try again after some time.");
				return redirect('admin/faq');
			}		
	}
	
	//EDIT FAQ
	public function editFaq($id){
		$editFaq   = Faqs::find($id);
		if(!empty($editFaq)){
        	return view('admin.faq.edit-faq',compact('editFaq'));
		}else{
			return redirect('admin/faq');
		}
	}
	
	//UPDATE FAQ
	public function updateFaq($id, request $request){
		$editFaq   = Faqs::find($id);
		if(!empty($editFaq)){
			$validator = Validator::make($request->all(),[
    			 'title' => 'required|min:5'
    		]);
			
			if ($validator->fails()) {
            		return redirect('admin/faq/edit/'.$id)
                        ->withErrors($validator)
                        ->withInput();
        	}
			
			$title				= $request->input('title');
			$description		= $request->input('description');
			$visibility			= $request->input('status');
			
						
			$editFaq->title				= $title;
			$editFaq->description		= $description;
			$editFaq->visibility		= $visibility;
			
			$editFaq->updated_at 	= date('Y-m-d H:i:s');
			
			if($editFaq->save()){
				Session::flash('message', "FAQ updated successfully.");
				return redirect('admin/faq');
			}else{
				Session::flash('error', "FAQ updation fails. Please try again after some time.");
				return redirect('admin/faq');
			}	
        	
		}else{
			return redirect('admin/faq');
		}
	}
	
	//Delete FAQ
	public function deleteFaq($id){
		$deleteFaq   = Faqs::find($id);
		if(!empty($deleteFaq)){
				$deleteFaq->delete();
				Session::flash('message', "FAQ deleted successfully.");
				return redirect('admin/faq');
		}else{
			return redirect('admin/faq');
		}
	}
   
}
