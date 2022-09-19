@extends('layout')


@if(!empty($message))
 
<div x-data ="{show: true}" x-init="setTimeout(() =>show = false, 3000 )" x-show="show" class="alert alert-success">
  {{ $message}}
</div>
@endif

@section('content')

<a id="btn" style="margin:10px;"  class="btn btn-outline-primary " href="{{route('home')}}">Back</a> <a id="btn"  class="btn btn-primary" href="{{route('coupon')}}">Refresh Coupons</a>
<center class="mt-5" ><h4 id="span">All Coupons</h4></center>


<div class="flex mt-5 mb-5">


<form id="select"  action="{{route('search')}}" method="get">
 

<select name="type"  class="form-select " aria-label="Default select example">
  <option value= " "  >Type</option>
  @foreach ($types as $type)
  <option value="{{$type->type}}" @foreach ($coupons as $coupon)
      

    {{($type->type == $coupon->type) ? 'selected' : ''}}  

  @endforeach >
  
  {{$type->type}}</option>


  @endforeach
</select>


<select name="subtype"   class="form-select " aria-label="Default select example">
  <option value= " " >Subtype</option>
  @foreach ($subtypes as $subtype)
  <option value="{{$subtype->subtype}}"   

    @foreach ($coupons as $coupon)
    {{($subtype->subtype == $coupon->subtype) ? 'selected' : ''}}    
    @endforeach
    
    >{{$subtype->subtype}}</option>
  @endforeach
</select>


<select name="value"  class="form-select " aria-label="Default select example">
  <option value= " " >Value</option>
    @for($i = 1; $i <= 100; $i++)
    @if ($i % 5 == 0)

    <option value="{{$i}}">{{$i}}</option>
        
    @endif
        
    @endfor

</select>


<select name="status"   class="form-select" aria-label="Default select example">
  <option value= " " >Status</option>
  
  <option value="active"   
  @foreach ($coupons as $coupon)
  {{($coupon->status) == 'active' ? 'selected' : ''}}    

  @endforeach 
 >Active</option>



<option value="inactive"  

@foreach ($coupons as $coupon)
{{($coupon->status) == 'inactive' ? 'selected' : ''}} 

@endforeach
>Inactive</option>




<option value="used"  

@foreach ($coupons as $coupon)
{{($coupon->status) == 'used' ? 'selected' : ''}} 
  
  @endforeach
  >Used</option>


  

   
</select>



<select  name="used_times" class="form-select" aria-label="Default select example">
  <option value= " ">Used times</option>
  <option value="desc">Most used</option>
  <option value="asc">Least used</option>
 
</select>

<input style="width:100%;" type="text" id="time" placeholder="from date" name="from" onfocus="(this.type='date')" onblur="(this.type='text')" >

<input  style="width:100%;" type="text" id="time"  placeholder="to date"  name="to" onfocus="(this.type='date')" onblur="(this.type='text')" >

<input type="submit" id="submit" value="Filter">
</form>
</div>


<table id="datatable" class="table table-bordered mt-5">
    <thead id="head" >
      <tr>
        <th scope="col">id</th>
        <th scope="col">Email</th>
        <th scope="col">Code</th>
        <th scope="col">type</th>
        <th scope="col">subtype</th>
        <th scope="col">value</th>
        <th scope="col">limit</th>
        <th scope="col">status</th>
        <th scope="col">valid_until</th>
        <th scope="col">used_times</th>
        <th scope="col">created_at</th>
        <th scope="col">updated_at</th>
        <th scope="col">disable</th>
       
        <th scope="col">options</th>
      </tr>
    </thead>
    <tbody>

      @if($coupons->isEmpty())

      <center><p id="paragraph" >No records found</p></center>
          
      @endif

      @foreach($coupons as $coupon)
      <tr>
      
        <td>{{$coupon->coupon_id}}</td>
        
        @if(isset($coupon->email))

        <td>{{$coupon->email}}</td>

        @else

        <td id="color"><center>{{'/'}}</center></td>
            
        @endif

        <td >{{$coupon->code}}</td>
        <td>{{$coupon->type}}</td>
        <td>{{$coupon->subtype}}</td>
        
        @if(isset($coupon->value))

        <td>{{$coupon->value}}
        
          @if ($coupon->subtype == 'X% OFF') 

          {{'%'}}
          @elseif($coupon->subtype == 'FLAT RATE OFF')

         {{'EUR'}}
              
          @endif
        
        </td>

        @else

        <td id="color"><center>{{'/'}}</center></td>
            
        @endif

        
        @if(isset($coupon->limit))

        <td>{{$coupon->limit}}</td>

        @else

        <td id="color"><center>{{'/'}}</center></td>
            
        @endif

        @if ($coupon->status == 'active')

        <td id="blue">{{$coupon->status}}</td>

        @elseif($coupon->status == 'used')

        <td id="orange">{{$coupon->status}}</td>

        @else 

        <td id="red">{{$coupon->status}}</td>
            
        @endif


         
        @if(isset($coupon->expiration_date))

        <td>{{$coupon->expiration_date}}</td>

        @else

        <td id="color"><center>{{'/'}}</center></td>
            
        @endif

        
        
        <td>

          @if(isset($coupon->used_times)) 

          {{$coupon->used_times}}  
   
   
           @else
   
           {{'0'}} 
   
                  
           @endif


        </td>
        <td>{{$coupon->created_at}}</td>
        <td>{{$coupon->updated_at}}</td>
        <td>
         
          @if ($coupon->status == 'active')
            
          <a class="btn btn-primary" href="{{route('disable_one', $coupon->coupon_id)}}">Disable</a>

          @else

          <center><img src="{{ asset('images/missing.png') }}" width="15px" alt="tag"></center>

          @endif


        </td>
       
      
        <td>
         
          @if ($coupon->status == "inactive" )
         

          <center><a  id="edit"  class="btn btn-info edit" href="{{route('coupon_edit', $coupon->coupon_id)}}">Edit</a></center>


          @elseif($coupon->status == "active" )
         <div>
          <a  id="edit"  class="btn btn-info edit" href="{{route('coupon_edit', $coupon->coupon_id)}}">Edit</a>
          <form style="display:inline-block;" action="{{route('coupon_delete', $coupon->coupon_id)}}" method="post">
            @csrf
            @method('DELETE')
          <button type="submit" id="delete" class="btn btn-danger">Delete</button>
        </form>

         </div>
          @else
          <center><img src="{{ asset('images/missing.png') }}" width="15px" alt="tag"></center>
        
        
          @endif
        </td>
       
      </tr>
      @endforeach
    </tbody>
  </table>



  
@endsection