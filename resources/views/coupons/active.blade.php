@extends('layout')



@section('content')

<a id="btn" style="margin:10px" class="btn btn-outline-primary " href="{{route('home')}}">Back</a><a id="btn"  class="btn btn-primary" href="{{route('active')}}">Refresh Coupons</a>
<center class="mt-5" ><h4 id="span">Active Coupons <span style="font-size:15px; color:green; letter-spacing:normal;">(all active coupons,except single type coupon)</span>  </h4></center>


<a id="btn" class="btn btn-primary" onclick="return confirm('Are you sure?')"  href="{{route('disable')}}">Disable All </a>


<form id="select"  action="{{route('search_active')}}" method="get">
  

<select name="type"   class="form-select " aria-label="Default select example">
  <option  selected disabled >Type</option>
  @foreach ($types as $type)
  <option value="{{$type->type}}">{{$type->type}}</option>
  @endforeach
</select>


<select name="subtype"   class="form-select " aria-label="Default select example">
  <option selected disabled >Subtype</option>
  @foreach ($subtypes as $subtype)
  <option value="{{$subtype->subtype}}">{{$subtype->subtype}}</option>
  @endforeach
</select>


<select name="value" class="form-select " aria-label="Default select example">
  <option selected disabled >Value</option>
    @for($i = 1; $i <= 100; $i++)
    @if ($i % 5 == 0)

    <option value="{{$i}}">{{$i}}</option>
        
    @endif
        
    @endfor

</select>

<input style="width:100%;" type="text" id="time" placeholder="from date"  name="from" onfocus="(this.type='date')" onblur="(this.type='text')" >

<input style="width:100%;" type="text" id="time"  placeholder="to date" name="to" onfocus="(this.type='date')" onblur="(this.type='text')" >

<input type="submit" id="submit" value="Filter">
</form>



<table  id="datatable" class="table table-bordered mt-5">
    <thead>
      <tr>
        <th scope="col">id</th>
        <th scope="col">type</th>
        <th scope="col">subtype</th>
        <th scope="col">value</th>
        <th scope="col">limit</th>
        <th scope="col">email</th>
        <th scope="col">created_at</th>
        <th scope="col">status</th>
        
        
      </tr>
    </thead>
    <tbody>

      @if($coupons->isEmpty())

      <center><p id="paragraph" >No records found</p></center>
          
      @endif
      
     
       @foreach ($coupons as $coupon)
        @if ($coupon->status == "active" && $coupon->type != "single" )
       <tr>
        <td>{{$coupon->coupon_id}}</td>
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

        
        @if(isset($coupon->limits))

        <td>{{$coupon->limits}}</td>

        @else
        <td id="color"><center>{{'/'}}</center></td>
            
        @endif
        <td>{{$coupon->email}}</td>
        <td>{{$coupon->created_at}}</td>
        <td>{{$coupon->status}}</td>
      
      </tr>
        @endif
       @endforeach
     
     
    </tbody>
  </table>

  


    
@endsection