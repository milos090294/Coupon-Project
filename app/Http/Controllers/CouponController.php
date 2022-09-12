<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Subtype;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\CouponCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\CouponRequest;
use Illuminate\Support\Facades\Event;


class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()                             // FUNCTION FOR ALL COUPONS VIEW, CHECK STATUS 
    {
       $now   =  now()->toDateTimeString();
       $coupons_check = Coupon::all();

        foreach($coupons_check as $coupon) { 

            if ($coupon->expiration_date < $now && $coupon->expiration_date != null) { 

                if($coupon->status != "used"){

                    $update =  DB::table ('coupons')
                    ->where ('coupon_id', $coupon->coupon_id)
                    ->update ([
                    'status'  => 'inactive'
                ]); }

            }

         if($coupon->used_times == $coupon->limit) {
               
                if($coupon->limit != null){

                    $update =  DB::table ('coupons')
                    ->where ('coupon_id', $coupon->coupon_id)
                    ->update ([
                    'status'  => 'used'
                ]); }

            }
        }

     //$coupons = $this->coupons();  //function for all coupons with joins in main Controller 

      Event::dispatch(new CouponCreated());                   //cache only for all coupons v
      $coupons = cache('coupons', function(){

      return $this->coupons();

      });

       $types = Type::all();
       $subtypes = Subtype::all();
      
        return view ('coupons/all', compact('coupons'), array(

            'types' => $types,
            'subtypes' => $subtypes

        ));
    }



    public function create()                                       //FUNCTION FOR CREATE VIEW, ADD NEW COUPONS ON ADMIN PANEL
    {
       $types = Type::all();
       $subtypes = Subtype::all();
       return view('coupons/create', compact('types', 'subtypes'));
    }



    public function store(Coupon $coupon, CouponRequest $request)          //STORE COUPON
    {
        $coupon = new Coupon;
        $random = strtoupper (Str::random(6));
        $request->validated();
        $validate = $this->logic($request, $coupon);           //logic function in main Controller

            switch ($validate) {
            
            case 'value_error':
                return redirect('/coupon/create')->with('delete', 'Please add valid value!');
                break;
            case 'email_valid':
                return redirect('/coupon/create')->with('delete', 'Please enter valid email!');
                 break;
             case 'error_time':
                 return redirect('/coupon/create')->with('delete', 'Please enter valid time!');
                 break; 
            case 'expiration_days':
                return redirect('/coupon/create')->with('delete', 'For this coupon, you need to put expiration days!');
                break;
            case 'email_error':
                 return redirect('/coupon/create')->with('delete', 'For this coupon, you need to put email!');
                 break;
            case 'limit_error':
                return redirect('/coupon/create')->with('delete', 'For this coupon, you need to put limit!');
                 break;
            default:
                ;
            }

        $coupon->fill($request->all());  
        $coupon->code = $random;
        $coupon ->save();

        if($coupon->email) {                                            //check for email, after store coupon add email in table Emails if didnt exist
            $email = Address::where('email', $coupon->email)->first();

            if(!$email) {
    
                $email = new Address;
                $email->email = $coupon->email;
                $email->save();
    
             }
    
        }

        $coupon_type = Type::where('type_id', $coupon->type_id)->first(); 
        $coupon_subtype = Subtype::where('subtype_id', $coupon->subtype_id)->first(); 

        return redirect ('/coupon/create')->with('message', "Coupon is created. Coupon type is: $coupon_type->type. Coupon subtype is: $coupon_subtype->subtype. Coupon code is: $coupon->code. ");
    }




    public function edit(Coupon $coupon, $id)                                       //FUNCTION FOR EDIT VIEW
    {
        $coupon = Coupon::findOrFail($id);

        $type = Type::where('type_id', $coupon->type_id)->first();
        $subtype = Subtype::where('subtype_id', $coupon->subtype_id)->first();
        
       $types = Type::all();
       $subtypes = Subtype::all();

        return view('coupons/edit', compact('coupon', 'types', 'subtypes', 'type', 'subtype'));
    }


    public function update(CouponRequest $request, Coupon $coupon, $id)    //UPDATE FUNCTION 
    {
        $coupon = Coupon::findOrFail($id);
        $request->validated();
        $validate = $this->logic($request, $coupon);        // same logic function 

            switch ($validate) {
            
            case 'value_error':
                return redirect("/coupon/edit/$coupon->coupon_id")->with('delete', 'Please add valid value!');
                break;
                
            case 'email_valid':
                return redirect("/coupon/edit/$coupon->coupon_id")->with('delete', 'Please enter valid email!');
                 break;
             case 'error_time':
                 return redirect("/coupon/edit/$coupon->coupon_id")->with('delete', 'Please enter valid time!');
                 break; 
            case 'expiration_days':
                return redirect("/coupon/edit/$coupon->coupon_id")->with('delete', 'For this coupon, you need to put expiration days!');
                break;
            case 'email_error':
                 return redirect("/coupon/edit/$coupon->coupon_id")->with('delete', 'For this coupon, you need to put email!');
                 break;
            case 'limit_error':
                return redirect("/coupon/edit/$coupon->coupon_id")->with('delete', 'For this coupon, you need to put limit!');
                 break;
            default:
                ;
            }

        $coupon->fill($request->all())->update();  
        

        if($coupon->email) {                                                        //check again email again because when update we can change email and i want to have 
                                                                                    //all emails that have ever entered the system
            $email = Address::where('email', $coupon->email)->first();

            if(!$email) {
    
                $email = new Address;
                $email->email = $coupon->email;
                $email->save();
    
             }
    
        }


        $coupons = $this->coupons();
        $types = Type::all();
        $subtypes = Subtype::all();

        return view('/coupons/all', compact('coupons', 'types', 'subtypes'), ['message' => 'Coupon is updated']);
    }


    public function destroy(Coupon $coupon, $id)                                    //DELETE FUNCTION
    {
        Coupon::where('coupon_id', $id)->delete();
        $coupons = $this->coupons();
        $types = Type::all();
        $subtypes = Subtype::all();
       
        return view('coupons/all', compact('coupons', 'types', 'subtypes'));
    }


    public function search(Request $request) {      //SEARCH FUNCTION 


    $coupons = $this->filter($request);                       //filter function in main Controller
    $types    = Type::all();
    $subtypes = Subtype::all();
    $currentURL = URL::current();
   
   
    if(str_contains($currentURL, 'search_active')){
        
        return view('coupons/active', compact('coupons', 'types', 'subtypes'));

    }

    if(str_contains($currentURL, 'search_non_used')){

        return view('coupons/non_used', compact('coupons','types', 'subtypes'));

    }
  
    return view('/coupons/all', compact('coupons', 'types', 'subtypes'));

} 



public function disable() {                                                     //DISABLE FUNCTION, in active view i have button for disable all active coupon

    $coupons = Coupon::where('status', 'active')->get();
    foreach($coupons as $coupon) {

       $coupon->status = "inactive";
      
       $coupon->update();
    }
    
    return view('/home', compact('coupons'));

}


public function disable_one($id) {                        //DISABLE SPECIFIC  COUPON

    $coupon = Coupon::find($id);
    $coupon->status = 'inactive';
    $coupon->update();
    
    $coupons = $this->coupons();
    $types = Type::all();
    $subtypes = Subtype::all();
   
    return view('coupons/all', compact('coupons', 'types', 'subtypes'));

}


public function active()                        //ACTIVE COUPON VIEW, ALSO SAME FUNCTION FOR NON_USED COUPON VIEW

{   

    $currentURL = URL::current();
    $coupons = $this->coupons();                    //function for all coupons in main controller
    $types    = Type::all();
    $subtypes = Subtype::all();

    if(str_contains($currentURL, 'active')){

        return view('coupons/active', compact('coupons', 'types', 'subtypes'));

    }elseif(str_contains($currentURL, 'non_used')){

        return view('coupons/non_used', compact('coupons','types', 'subtypes'));
    }

  
}

public function used() {                                        //USED VIEW FUNCTION

    $coupons = $this->coupons_used();                  //function for used coupons in main controller
    $types    = Type::all();
    $subtypes = Subtype::all();

    return view('coupons/used', compact('coupons','types', 'subtypes'));
    
}

public function search_used(Request $request){                 //FILTER USED FUNCTION

    $coupons = $this->filter_used($request);        //filter_used function in main Controller

    $types    = Type::all();
    $subtypes = Subtype::all();

    return view('coupons/used', compact('coupons','types', 'subtypes'));

}


}
