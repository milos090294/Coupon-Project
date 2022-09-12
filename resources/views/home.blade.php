@extends('layout')

@section('content')
    
        
        <center style="margin:25px;" > <p style="letter-spacing: 2px; color: rgb(165, 170, 179); font-family:monospace;" >"{{$citat}}"</p></center>
    

      <div class="section mt-5 mb-5">

        <div class="option">

            <a  id="btn"  class="btn btn-primary" href="{{route('coupon_create')}}">Create New Coupon</a>

        </div>
        
        <div class="option">

            <a id="btn"  class="btn btn-primary" href="{{route('active')}}">Active Coupons</a>
            
        </div>
        
        <div class="option">

            
            <a id="btn"  class="btn btn-primary" href="{{route('coupon')}}">All Coupons</a>
            
        </div>

        <div class="option">

            
            <a id="btn"  class="btn btn-primary" href="{{route('used')}}">Used Coupons</a>
            
        </div>

        <div class="option">

            
            <a id="btn"  class="btn btn-primary" href="{{route('non_used')}}
            ">Non-used Coupons</a>
            
        </div>
        <div class="option">

            
            <a id="btn" class="btn btn-primary" href="{{route('address')}}">Email Addresses</a>
            
        </div>

      </div>
      
      
      @endsection