@extends('layout')


@section('content')

 
<a id="btn" style="margin:10px;"  class="btn btn-outline-primary " href="{{route('home')}}">Back</a> 
<center class="mt-5" ><h4 id="span">Edit Coupon</h4></center>
<div class="container h-100">
  <div class="row d-flex justify-content-center align-items-center h-100">
    <div class="col-lg-12 col-xl-11">
      <div class="card text-black" style="border-radius: 25px;">
        <div class="card-body p-md-5">
          <div class="row justify-content-center">
            <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
              

              <form method="post" action="{{route('coupon_update', $coupon->coupon_id)}}" class="mx-1 mx-md-4" >
                 @csrf
                @method('PUT')
                <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                      <label class="form-label" for="form3Example4cd">Coupon Type*</label>
                      <select name="type_id" id="">
                        <option value="{{$type->type_id}}">{{$type->type}}</option>
                       

                      </select>
                     
                    </div>
                  </div>
                @error('type_id')
                <p style="color:red;"> {{$message}} </p>
                @enderror

                
                <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                      <label class="form-label" for="form3Example4cd">Coupon Subtype*</label>
                      <select id="selects"  name="subtype_id" id="">
                        <option value="{{$subtype->subtype_id}}">{{$subtype->subtype}}</option>

                        
                      </select>
                     
                    </div>
                  </div>
                @error('subtype_id')
                <p style="color:red;"> {{$message}} </p>
                @enderror

                
                <div class="d-flex flex-row align-items-center mb-4">
                  <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                  <div class="form-outline flex-fill mb-0">
                    
                    <input type="number" value="{{$coupon->value}}" min="0" name="value" id="form3Example1c" class="form-control" />
                    <label class="form-label" for="form3Example1c">Coupon Value</label>
                  </div>
                </div>
                @error('value')
                    <p style="color:red;"> {{$message}} </p>
                @enderror

                <div class="d-flex flex-row align-items-center mb-4">
                  <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                  <div class="form-outline flex-fill mb-0">
                    
                    <input type="number" value="{{$coupon->limit}}" min="0" name="limit" id="form3Example1c" class="form-control" />
                    <label class="form-label" for="form3Example1c">Limit</label>
                  </div>
                </div>
                @error('limit')
                    <p style="color:red;"> {{$message}} </p>
                @enderror


                
                <div class="d-flex flex-row align-items-center mb-4">
                  <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                  <div class="form-outline flex-fill mb-0">
                    
                    <input type="date" value="{{$coupon->expiration_date}}" name="expiration_date" id="form3Example1c" class="form-control" />
                    <label class="form-label" for="form3Example1c">Expiration Days</label>
                  </div>
                </div>
                @error('expiration_date')
                    <p style="color:red;"> {{$message}} </p>
                @enderror



                <div class="d-flex flex-row align-items-center mb-4">
                  <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                  <div class="form-outline flex-fill mb-0">
                    <input type="hidden" name="code" value ="0">
                    <input type="text" value="{{$coupon->email}}" name="email" id="form3Example1c" class="form-control" />
                    <label class="form-label" for="form3Example1c">Email Address</label>
                  </div>
                </div>
                @error('email')
                    <p style="color:red;"> {{$message}} </p>
                @enderror


                <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                    <button type="submit" id="btn" class="btn btn-primary btn-lg">Update</button>
                  </div>

              </form>

            </div>
          
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
    
@endsection