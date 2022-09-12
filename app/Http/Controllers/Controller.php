<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Type;
use App\Models\Coupon;
use App\Models\Subtype;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


public function logic($request, $coupon){                       //LOGIC FUNCTION FOR VALIDATE STORE AND UPDATE COUPON

    $value = ltrim($request->input('value'), '0');
    $limit = ltrim($request->input('limit'), '0');
    $expiration =($request->input('expiration_date'));
    $type_id = intval($request->input('type_id'));
    $subtype_id =  intval($request->input('subtype_id'));
    $email   = $request->input('email');
    $types = Type::all();
    $subtypes = Subtype::all();
    $format = ('Y-m-d');
    $now = date('Y-m-d');

    if(!$type_id or !$subtype_id) {

        return 'error_type_subtype';

    }

    if($email) {

    if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {

        
        return 'email_valid';

    }

    } 
    

    //validate types and subtypes

    if($type_id > count($types) or $type_id < 1) {

        return 'error_type';

    }

    
    if($subtype_id > count($subtypes) or $subtype_id < 1) {

        return 'error_subtype';

    }


    //validate single

    if($type_id == 1) { 

        if(!$email){ 

            return "email_error";
        }

        $coupon->email = $email;
    }
    
    //validate multi-limit

    if($type_id == 2) { 

        if(!$limit) {

            return 'limit_error';
            
        }

        if($limit < 1) {

            return 'error_limit_value';

        }

        $coupon->limit = $limit;

    }

    //validate expires

    if ($type_id == 3 or $type_id == 4) { 

            if(!$expiration) {

                return 'expiration_days';
                
            }
     
     $d = DateTime::createFromFormat($format, $expiration);
     $res = $d && $d->format($format) == $expiration;
         
         if($res === false) {

                return 'error_time';

         }

        if($expiration < $now ){

            return 'error_time';

        }

         $coupon->expiration_date = $expiration;

    }

    //validate values from subtypes

    if ($subtype_id == 1 or $subtype_id == 2 ) {
        
        if(!$value) {

            return 'value_error';
           
          }

        if ($subtype_id ==  1) {

            if ($value > 100 or $value < 1) {
            
            return 'value_error';   
            }
        }

        

        $coupon->value = $value;

    }}



public function coupons(){         
                                                     //FUNCTION FOR COUPONS WITH JOINS

    $coupons = DB::table('coupons')
    ->join('types', 'coupons.type_id', '=', 'types.type_id' )
    ->join('subtypes', 'coupons.subtype_id', '=', 'subtypes.subtype_id')
    ->select( 'types.*', 'subtypes.*', 'coupons.*')
    ->get(); 

    return $coupons;

}


public function coupons_used() {                                //FUNCTION FOR USED COUPONS WITH JOINS

    $coupons = DB::table('coupons')
    ->join('types', 'coupons.type_id', '=', 'types.type_id', )
    ->join('subtypes', 'coupons.subtype_id', '=', 'subtypes.subtype_id')
    ->join('address_coupons', 'coupons.coupon_id', '=', 'address_coupons.coupon_id')
    ->select( 'types.*', 'subtypes.*', 'coupons.*', 'address_coupons.*')
    ->get(); 

    return $coupons;

}


public function filter($request) {                                            //FILTERING COUPON


    $type = request('type', false);
    $subtype = request('subtype', false);   
    $value = request('value', false);
    $status = request('status', false);                                 
    $used_times = request('used_times', false);
    $from = request('from', false);
    $to = request('to', false);
   
 
    $coupons = DB::table('coupons')
    ->join('types', 'coupons.type_id', '=', 'types.type_id', )
    ->join('subtypes', 'coupons.subtype_id', '=', 'subtypes.subtype_id')
    ->select('types.*', 'subtypes.*' ,'coupons.*')
    ->when($type, function ($query, $type) {
        $query->where('types.type', $type);
    })
    ->when($subtype, function ($query, $subtype) {
        $query->where('subtypes.subtype', $subtype);
    })
    ->when($value, function ($query, $value) {
        $query->where('coupons.value', $value);
    })
    ->when($status, function ($query, $status) {
        $query->where('coupons.status', $status);
    })
    ->when($used_times, function ($query, $used_times) {
        $query->orderBy('coupons.used_times', $used_times);
    })->when($from, function ($query, $from) {
        $query->where('coupons.created_at','>', $from);  
    })
    ->when($to, function ($query, $to) {
        $query->where('coupons.created_at','<', $to);  
    })->get(); 


    return $coupons;

}

public function filter_used(){                               //USED COUPON FILTER

    $type = request('type', false);
    $subtype = request('subtype', false);
    $value = request('value', false);
    $from_used = request('from_used', false);
    $to_used = request('to_used', false);

    $coupons = DB::table('coupons')
    ->join('types', 'coupons.type_id', '=', 'types.type_id', )
    ->join('subtypes', 'coupons.subtype_id', '=', 'subtypes.subtype_id')
    ->join('address_coupons', 'coupons.coupon_id', '=', 'address_coupons.coupon_id')
    ->where('coupons.status', '=', 'used')
    ->select( 'types.*', 'subtypes.*', 'coupons.*', 'address_coupons.*')
    ->when($type, function ($query, $type) {
        $query->where('types.type', $type);
    })
    ->when($subtype, function ($query, $subtype) {
        $query->where('subtypes.subtype', $subtype);
    })
    ->when($value, function ($query, $value) {
        $query->where('coupons.value', $value);
    }) 
    ->when($from_used, function ($query, $from_used) {
        $query->where('address_coupons.used_at','>', $from_used);
    })
    ->when($to_used, function ($query, $to_used) {
        $query->where('address_coupons.used_at','<', $to_used);
    })
    ->get(); 

    return $coupons;


}




}
