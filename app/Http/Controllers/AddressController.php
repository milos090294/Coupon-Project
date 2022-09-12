<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()         //ALL ADDRESSES EVER CAME TO SYSTEM
    {
    
        $addresses = Address::all();
        return view('coupons/address', compact('addresses'));

    }


    public function search(Request $request) {            //FILTER FOR ADDRESSES


        $used_times = request('used_coupons', false);
        $from = request('from', false);
        $to = request('to', false);
        $email = request('email', false);

        $addresses = DB::table('addresses')
                ->when($used_times, function ($query, $used_times) {
                    $query->orderBy('coupon_used', $used_times);
                })
                ->when($from, function ($query, $from) {
                    $query->where('created_at', '>', $from);
                })
                ->when($to, function ($query, $to) {
                    $query->where('created_at', '<', $to);
                })
                ->when($email, function ($query, $email) {
                    $query->where('email', 'LIKE', "%{$email}%");
                })
                ->get(); 
 
      return view('coupons/address', compact('addresses'));


    }

   
    
    

}
