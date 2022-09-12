<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="{{asset('css/styles.css')}}" type="text/css"> 
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">


</head>
<body>





  <div  class="navbar navbar-light bg-light justify-content-between">
    
          <div id="navbar" class="col-md-4 "> 
            
            <a href="{{route('home')}}" id="admin" class="navbar-brand m-5 "><span id="span-wel">Welcome</span> {{auth()->user()->name}}</a>
          
          </div>
          
          <div id="navbar" class="col-md-4 ">    
            <img src="{{ asset('images/image2.png') }}" width="200px" alt="tag">
          </div>


         <div id="navbar" class="col-md-4 ">
          
            <form  class="form-inline">
              
              <button id="btn-logout" class="btn" type="submit"><a id="logout" href="{{route('logout')}}">Logout</a></button>

            </form>

        </div>
</div>


@if (session('message'))

<div x-data ="{show: true}" x-init="setTimeout(() =>show = false, 8000 )" x-show="show" class="alert alert-success">
    {{ session('message') }}
</div>

@elseif(session('delete'))

<div x-data ="{show: true}" x-init="setTimeout(() =>show = false,  4000 )" x-show="show" class="alert alert-danger">
  {{ session('delete') }}
</div>

@endif


  

@yield('content') 




<br><br><br>
  


 
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>

 

 
 <script src="{{url('js/main.js')}}"></script>

</body>
</html>