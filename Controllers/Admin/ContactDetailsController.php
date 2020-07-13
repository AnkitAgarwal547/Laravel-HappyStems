<?php

namespace App\Http\Controllers\admin;

use App\ContactDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ContactDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $contact_details = ContactDetails::orderBy('name', 'asc')->get();
        return view('admin.contact.contactDetails', compact('contact_details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createContact()
    {
        //
        return view('admin.contact.addContactDetails');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveContact(Request $request)
    {
        //
        $validator = Validator::make($request->all(),[
            'name'          => 'required',
            'address'       => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('admin/contact/add/')
                ->withErrors($validator)
                ->withInput();
        }

        $name               = $request->input('name');
        $phone              = $request->input('phone');
        $address            = $request->input('address');

        $c_details = new ContactDetails();

        $c_details->name    = $name;
        $c_details->address = $address;
        $c_details->phone   = $phone;

        if($c_details->save()){
            Session::flash('message', "Contact created successfully.");
            return redirect()->action('Admin\ContactDetailsController@index');
        }
        else{
            return redirect()->action('Admin\ContactDetailsController@createContact');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editContact($id)
    {
        //
        $editContact  = DB::table('contact_details')->find($id);
        return view('admin.contact.editContactDetails', compact('editContact'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateContact(Request $request, $id)
    {
        //
        $updateContact  = DB::table('contact_details')->find($id);

        if(!empty($updateContact)){
            $validator = Validator::make($request->all(),[
                'name'              => 'required',
                'address'           => 'required',
            ]);

            if ($validator->fails()) {
                return redirect('admin/contact/edit/'.$id)
                    ->withErrors($validator)
                    ->withInput();
            }

            $name               = $request->input('name');
            $phone              = $request->input('phone');
            $address            = $request->input('address');

            DB::table('contact_details')
                ->where('id',$id)
                ->update(['name' => $name, 'phone'=>$phone, 'address'=> $address, 'updated_at'=>date('Y-m-d H:i:s')]);

            Session::flash('message', "Contact Details updated successfully.");
            return redirect()->action('Admin\ContactDetailsController@index');
            }
            else{
                return redirect()->action('Admin\ContactDetailsController@createContact');
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteContact($id)
    {
        //
        $deleteProductTag   = ContactDetails::find($id);
        if(!empty($deleteProductTag)){
            $deleteProductTag->delete();
            Session::flash('message', "Contact deleted successfully.");
            return redirect('admin/contact');
        }else{
            return redirect('admin/contact');
        }
    }
}
