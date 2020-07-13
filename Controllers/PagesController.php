<?php

namespace App\Http\Controllers;

use App\ProductCities;
use App\Products;
use App\Teams;
use Illuminate\Http\Request;
use App\Pages;
use App\Faqs;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{

    protected $no_of_rows = 12;

    /**
     * @param $page_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */

    public function index($page_slug){
		
		$page = Pages::where('slug', $page_slug)->first();

		$teams = Teams::orderBy('name', 'asc')->get();

		if(!empty($page)){
			return view('pages',compact('page','teams'));
		}
		return redirect('/');
	}

	public function faq(){
		
		$page = Pages::where('slug', 'faq')->first();
		if(!empty($page)){
			$faqs = Faqs::where('visibility',1)->get();
			return view('faq',compact('page','faqs'));
		}else{
			return redirect('/');
		}
	}

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allCitiesList(){
        $page = Pages::where('slug', 'home')->first();
        $allCities = ProductCities::all();
        return view('all-cities', compact('allCities', 'page'));
    }

    public function categories(){
        dd('cat');
    }
}
