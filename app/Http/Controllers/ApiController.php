<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Address;
use App\Models\CouponUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AddressCoupon;
use App\Notifications\CreateCoupon;
use App\Http\Requests\CouponRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;


class ApiController extends Controller
{
    
    public function store(Coupon $coupon, CouponRequest $request)                   //API STORE FUNCTION
    {   

        $coupon = new Coupon;
        $random = strtoupper (Str::random(6));
        $request->validated();
        $validate = $this->logic($request, $coupon);         //logic function in main Controller

            switch ($validate) {
                                                                //depending on what the function returns, response will be diferent 
            case 'value_error':
                return response()->json([
                    'status' => 'false',
                    'error' => 'For this coupon you need to put value'
                    
                ]);
        
                break;
                case 'value_err':
                    return response()->json([
                        'status' => 'false',
                        'error' => 'Max value for this subtype is 100 and minimum is 1'
                        
                    ]);
            
                    break;
                 case 'error_time':
                     return response()->json([

                            'status' => 'false',
                            'error' => 'Date format is not valid'
                            
                        ]);
                
                     break;
           
            case 'expiration_days':
                return response()->json([
                    'status' => 'false',
                    'error' => 'For this coupon you need to put expiration date'
                    
                ]);
        
                break;
            case 'email_error':
                return response()->json([
                    'status' => 'false',
                    'error' => 'For this coupon you need to put email'
                    
                ]);
        
                 break;
             case 'email_valid':
                return response()->json([
                        'status' => 'false',
                        'error' => 'Put valid email'
                        
                    ]);
            
                  break;
              case 'error_limit_value':
                return response()->json([

                         'status' => 'false',
                         'error' => 'Enter valid limit'
                            
                        ]);
                
                    break;
          case 'error_type':
                 return response()->json([
                         'status' => 'false',
                         'error' => 'Enter valid type'
                            
                     ]);
                
                  break;
         case 'error_subtype':
                 return response()->json([
                         'status' => 'false',
                         'error' => 'Enter valid subtype'
                               
                     ]);
                   
                 break;
          case 'limit_error':
                return response()->json([
                    'status' => 'false',
                    'error' => 'For this coupon you need to put limit'
                    
                ]);
        
                 break;

                 case 'error_type_subtype':
                    return response()->json([
                        'status' => 'false',
                        'error' => 'Error type or subtype value!'
                        
                    ]);
            
                    break;

            default:
                ;
            }
                                                    
        $coupon->fill($request->all());             //everything okay, add new coupon
        $coupon->code = $random;
        $coupon ->save();

        if($coupon->email) {                                                    //check for email 
            $email = Address::where('email', $coupon->email)->first();

            if(!$email) {
    
                $email = new Address;
                $email->email = $coupon->email;
                $email->save();
    
             }

        }

    //  $this->created_coupon($coupon);             //this is function for send notification email, when coupon is created

        return response()->json([
            'status' => 'true',
            'code' => $coupon->code,
            'email' => $coupon->email
        ]);

    }


public function index(Request $request){                   //FUNCTION FOR USE COUPON
 
    $coupons = Coupon::where('code', $request->code)->first();         //check for coupon code

    if($request->email) {

    
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)) { 
    
        return response()->json([
            
            'status' => 'false',
            'data_error' => 'Enter valid email address'                   //email validate and check
        
        ]);
    
        }
    
        } 
        
    if(!$request->email) {

        return response()->json([
            
            'status' => 'false',
            'data_error' => 'Enter email address'
        
        ]);
    }




    if ($coupons) { 

        if ($coupons->status == 'inactive') {
    
            return response()->json([
                'status' => 'false',
                'data_error' => 'Coupon is not active!'
            
            ]);
        }
                                                                    //coupon status check

        
        if ($coupons->status == 'used') {

            return response()->json([
                
                'status' => 'false',
                'data_error' => 'Coupon is already used!'
            ]);
        
        }


                
    if($coupons->type_id == 1) {
                                                                                            //single coupom check
            if($coupons->email != $request->email) {

                return response()->json([
                    'status' => 'false',
                    'data_error' => 'Wrong email address'
                
                ]);

            }

        }

    

    if($coupons->type_id == 2) {                                                     //multi-limit coupom check
    
        if($coupons->limit == $coupons->used_times){ 

            $coupons->status = 'used';
            $coupons->update();

            return response()->json([
                'status' => 'false',
                'data_error' => 'Limit has reached'
            
            ]);

        } }

  if($coupons->type_id == 2 or $coupons->type_id == 3){                                    //single-expires and multi-limit coupom check

    
            $used_email = AddressCoupon::where('email', $request->email)
                                        ->where('coupon_id', $coupons->coupon_id)->first();

            if($used_email) { 
    
                return response()->json([
    
                    'status' => 'false',
                    'data_error' => 'already used coupon with this email'
                ]);
    
            }
        
      
    }

    if($coupons->type_id == 3 or $coupons->type_id == 4) {                    //expiration date check for type 3 and 4, 

        $date = date('Y-m-d');
      
        if($coupons->expiration_date < $date) {

           $coupons->status = 'inactive';
           $coupons->update();

            return response()->json([
    
                'status' => 'false',
                'data_error' => 'Coupon expired'
            ]);

         }

    }
       
    $used_email = Address::where('email', $request->email)->first();                //check if request->email exist in table with all email


    if(!$used_email) { 

        $used_email = new Address;
        $used_email->email = $request->email;                               //if dont exist add email
        $used_email->first_coupon_used = now();
        $used_email->last_coupon_used = now();
        $used_email->coupon_used++;
        $used_email->save();
        

    }else{ 

        if(!$used_email->first_coupon_used){                               //if exist update it
        
            $used_email->first_coupon_used = now();
        
        }
        
            $used_email->last_coupon_used = now();
            $used_email->coupon_used++;
            $used_email->update();
        
    }

        

            $used_coupon = new AddressCoupon;                                   //add it
            $used_coupon->coupon_id = $coupons->coupon_id;
            $used_coupon->email = $used_email->email;
            $used_coupon->save();

       


       if($coupons->type_id == 1){                                          //this for single coupon
    
        $coupons->status = "used";
    
       }
    
        $coupons->used_times++;
        $coupons->update();


        if($coupons->used_times == $coupons->limit) {

            $coupons->status = 'used';
            $coupons->update();

        }

        return response()->json([
    
            'status' => 'true'
            
        ]);


}else { 
                                                    //if $coupons dont exist response
        return response()->json([

            'status' => 'false',
            'data_error' => 'there is no such coupon'
        ]);

    }
    
}




public function created_coupon($coupon) {                                                       //FUNCTION FOR NOTIFICATION EMAIL

    $coupon_email = Coupon::where('email', $coupon->email)->first();

    if($coupon_email) { 

        $expiration = "unlimited";

        if($coupon->type_id == 3 or $coupon->type_id == 4 ){ 

            $expiration = $coupon->expiration_date;

        }

        $createdCoupon = array(
            'body' => 'You  created coupon!',
            'text' => 'Go to home page! ',
            'url' => route('home'),
            'code' => 'your code is: ' . $coupon->code,
            'thanks' => "Expiration date is: $expiration . Thank you."
        );
    
        $coupon_email->notify(new CreateCoupon($createdCoupon));

    }

    return response()->json([
        'status' => 'true',
        'code' => $coupon->code,
        'email' => $coupon->email
    ]);

}




}
