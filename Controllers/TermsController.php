<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function index(){
				$settings 	= logo_with_title();
				$page 		= page('services');
				return view('contact',compact('settings','page'));
		}
}
