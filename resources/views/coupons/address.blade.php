@extends('layout')



@section('content')


 
<a id="btn" style="margin:10px" class="btn btn-outline-primary " href="{{route('home')}}">Back</a> <a id="btn"  class="btn btn-primary" href="{{route('address')}}">Refresh Addresses</a>
<center class="mt-5" ><h4 id="span">Email Adresses </h4></center>


<form id="select"  action="{{route('search_address')}}" method="get">
  

<select  name="used_coupons" class="form-select" aria-label="Default select example">
  <option selected disabled >Used times</option>
  <option value="desc">Most used</option>
  <option value="asc">Least used</option>
 
</select>



<input style="width:100%;" type="text" id="time" placeholder="from date" name="from" onfocus="(this.type='date')" onblur="(this.type='text')" >

<input style="width:100%;" type="text" id="time"  placeholder="to date"  name="to" onfocus="(this.type='date')" onblur="(this.type='text')" >

  <input type="text" class="form-group"  placeholder="email" name="email" >

<input type="submit" id="submit" value="Filter">
</form>


<table id="datatable" class="table table-bordered mt-5">
    <thead>
      <tr>
        <th scope="col">id</th>
        <th scope="col">email</th>
        <th scope="col">first coupon used</th>
        <th scope="col">last coupon used</th>
        <th scope="col">used times</th>
        <th scope="col">created at</th>
        
        
      </tr>
    </thead>
    <tbody>

      
      @if($addresses->isEmpty())

      <center><p id="paragraph" >No records found</p></center>
          
      @endif
      

     @foreach ($addresses as $address)

     <tr>
      <td>{{$address->email_address_id}}</td>
      <td>{{$address->email}}</td>

    @if(isset($address->first_coupon_used))

    <td>{{$address->first_coupon_used}}</td>

    @else

    <td id="color"><center>{{'/'}}</center></td>
        
    @endif
    
    @if(isset($address->last_coupon_used))

    <td>{{$address->last_coupon_used}}</td>

    @else
    <td id="color"><center>{{'/'}}</center></td>
        
    @endif

     
    @if(isset($address->coupon_used))

    <td>{{$address->coupon_used}}</td>

    @else

    <td id="color"><center>{{'/'}}</center></td>
        
    @endif

     



      <td>{{$address->created_at}}</td>
    </tr>

     @endforeach
     
    </tbody>
  </table>



    
@endsection