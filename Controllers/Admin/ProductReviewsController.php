<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ProductReviews;
use App\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProductReviewsController extends Controller
{
    //

    public function index(){
        $reviews = ProductReviews::all();
        $productDetail = Products::where('product_reviewed', '=', 1)->get();
        return view('admin.review.showAll', compact('reviews', 'productDetail'));
    }

    public function saveReview(Request $request){

        //dd($request->review_star);

        $validator = Validator::make($request->all(),[
            'name'          => 'required',
            'rating'        => 'required|numeric|max:5',
            'review'        => 'required',
            'location'      => 'required'
        ]);

        $valid['data'] = json_encode($validator->errors());

        if ($validator->fails()) {
            $valid['status'] = 'failed';
            return response()->json($valid);
        }

        $hadReviewed = ProductReviews::where('product_id', $request->input('product_id'))->where('user_id', $request->input('user_id'))->first();
//        dd($hadReviewed);

        if($hadReviewed){
            $valid['status'] = 'duplicate';
            $valid['data']   = 'You\'ve already reviewed this Product';
            return response()->json($valid);
        }

        $review = new ProductReviews();

        $review->product_id = $request->input('product_id');
        $review->user_id    = $request->input('user_id');
        $review->name       = $request->input('name');
        $review->stars      = $request->input('rating');
        $review->review     = $request->input('review');
        $review->location   = $request->input('location');


        if($review->save()){
            Products::where('id', $request->input('product_id'))->update(['product_reviewed' => 1]);
            $valid['status'] = 'success';
            return response()->json($valid);
        }
        else{
            $valid['status'] = 'failed';
            return response()->json($valid);
        }
    }

    public function editReview($id){
        $reviewData = ProductReviews::where('id', $id)->first();
        return view('admin.review.view',compact('reviewData'));
    }

    public function updateReview($id, Request $request){
        $updateReview = ProductReviews::findorfail($id);

        $updateReview->status = $request->input('status');
        if($updateReview->save()){
            Session::flash('message', "Review updated successfully.");
            return redirect('/admin/products/review');
        }
        else{
            Session::flash('message', "Something went wrong !!!");
            return back();
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */

    public function deleteReview($id){
        $deleteReview = ProductReviews::findorfail($id);
        if(!empty($deleteReview)){
            $deleteReview->delete();
            Session::flash('message', "Review deleted successfully.");
            return redirect('admin/products/review');
        }else{
            return redirect('admin/products/review');
        }
    }
}
