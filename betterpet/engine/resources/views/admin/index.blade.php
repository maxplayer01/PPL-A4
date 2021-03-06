@extends('layout.template')

@section('content')
	<head>
 
 <style type="text/css">
/*<![CDATA[*/
#myInstance1 {
        border: 2px dashed #0000ff;
}
.nicEdit-selected {
        border: 2px solid #0000ff !important;
}
 
.nicEdit-main {
        background-color: #fff !important;
}
 
/*]]>*/
</style>
  <style>
  body {
      position: relative; 
  }
  #section1 {padding-top:50px;height:500px;color: #101010; background-color: #61abd8;}
  #section2 {padding-top:50px;height:500px;color: #101010; background-color: #f6f6f6;}
  #section3 {padding-top:50px;height:500px;color: #101010; background-color: #61abd8;}
  #section41 {padding-top:50px;height:500px;color: #101010; background-color: #f6f6f6;}
  #section42 {padding-top:50px;color: #101010; background-color:#61abd8;}
  .navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > li > a:focus {
   background-color: #5AC2FF;
}
  
  </style>
<script src="//cdn.ckeditor.com/4.5.8/full/ckeditor.js"></script>
</head>
<body data-spy="scroll" data-target=".navbar" data-offset="50">

<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <a href="{{URL::to('/')}}"><img src="{{URL::to('/image/logo.png')}}" width="150" height="60"></a>
    </div>
    <div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
          <li><a href="#section1">Shelter</a></li>
          <li><a href="#section2">Users</a></li>
          <li><a href="#section3">Adoption</a></li>
          <li><a href="#section5">Questions</a></li>
          <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">News<span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#section41">List News</a></li>
              <li><a href="#section42">Create News</a></li>
            </ul>
          </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
        	<li><a> Admin</a></li>
        	<li><a href="{{URL::to('/logout')}}"><span class="glyphicon glyphicon-user"></span> Sign Out</a></li>
     	</ul>

      </div>
    </div>
  </div>
</nav>    
<div id="section1" class="container-fluid">
    @include('common.error')
    @include('common.success')
    <h1 class="text-center">SHELTER</h1>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
          <table class="table table-responsive table-striped">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>View</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
              @foreach($shelters as $shelter)
                <tr>
                  <td>{{$shelter->shelterName}}</td>
                  <td>
                    <a class="btn btn-default" href="{{URL::to('/shelter')}}/{{$shelter->id}}">View</a>
                  </td>
                  <td>
                    <form method="POST" action="{{URL::to('/shelter')}}/remove/{{$shelter->id}}">
                    {!! csrf_field() !!}
                    <input onclick="return confirm('Are you sure to delete this shelter?')" type="submit" value="Delete" class="btn btn-danger">
                    </form>
                  </td>
                </tr>
              @endforeach
              </tbody>
          </table>
      </div>
    </div>
</div>
<div id="section2" class="container-fluid">
  <h1 class="text-center">USERS</h1>
  <div class="container">
  <h2>Daftar User</h2>
  <div class="table-responsive">          
  <table class="table">
    <thead>
      <tr>
        <th>Nama</th>
        <th>email</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
      <tr>
        <td>{{$user->name}}</td>
        <td>{{$user->email}}</td>
        <td>
          <a href="{{URL::to('/profile/view')}}/{{$user->id}}"><button class="btn btn-primary btn-sm">View</button></a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  </div>
</div>
  
</div>
<div id="section3" class="container-fluid">
  <h1 class="text-center">ADOPTION</h1>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <table class="table table-responsive table-striped">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>View</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
              @foreach($adoptions as $adoption)
                <tr>
                  <td>{{$adoption->name}}</td>
                  <td>
                    <a class="btn btn-default" href="{{URL::to('/adoption')}}/{{$adoption->id}}">View</a>
                  </td>
                  <td>
                    <form method="POST" action="{{URL::to('/adoption')}}/remove/{{$adoption->id}}">
                    {!! csrf_field() !!}
                    <input type="submit" value="Delete" onclick="return confirm('Are you sure to delete this adoption?')" class="btn btn-danger">
                    </form>
                  </td>
                </tr>
              @endforeach
              </tbody>
        </table>
    </div>
  </div>
</div>
<div id="section41" class="container-fluid">
  <h1 class="text-center">LIST NEWS</h1>
  <div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12">
      <table class="table table-responsive table-striped">
        <thead>
        <tr>
          <th>Title</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($allnews as $news)
        <tr>
          <td>{{$news->title}}</td>
          <td>
            <a href="{{URL::to('/admin/news/update')}}/{{$news->id}}"><button class="btn btn-primary btn-sm">Update</button></a>
            <a href="{{URL::to('/admin/news/delete')}}/{{$news->id}}"><button onclick="return confirm('Are you sure to delete this news?')" class="btn btn-primary btn-sm">Delete</button></a>
          </td>
        </tr>
        @endforeach
      </tbody>
      </table>
    </div>
  </div>

</div>
<div id="section42" class="container-fluid" style="min-height:700px">
  <h1 class="text-center">CREATE NEWS</h1>
  @include('common.error')
            {!! Form::open(array('url'=>'/admin/news/new','method'=>'POST', 'files'=>true)) !!}
            {!! csrf_field() !!}
            <script>
              function newspicture(){
                $("#photo").click();
              }
              function readURL(input) {
                  if (input.files && input.files[0]) {
                      var reader = new FileReader();

                      reader.onload = function (e) {
                          $('#image-choosen')
                              .attr('src', e.target.result)
                              .width(150)
                              .height(150);
                      };

                      reader.readAsDataURL(input.files[0]);
                  }
              }
            </script>
           <div class="form-group">
              <label class="in-form" for="title" style="display:block;">Title</label>
              <input type="text" name="title" id="title" class="form-control" placeholder="Title" required>
           </div>
           <div class="form-group">
              <div onclick="newspicture()" class="btn-primary btn">Upload Picture</div>
              {!! Form::file('newsimage',['id'=>'photo','onchange'=>'readURL(this)','style'=>'display:none','class'=>'form-control']) !!}
           </div>
           <div class="form-group">
            <img src="#" id="image-choosen" alt="choosen image">
           </div>
  
	    </br>
            <div class="form-group">
                  <label class="in-form" for="content"  style="display:block;">Content</label>
                  <textarea class="form-control" name="content" id="content" placeholder="Lorem ipsum dolor sit amet" required></textarea>
                  <script>
                      CKEDITOR.replace('content');
                  </script>

               </div>
            <div class="form-group">
               <button type="submit" class="btn btn-success">Submit</button>
            </div>
          {!! Form::close() !!}
</div>
<div id="section5" class="container">
  <h1 class="text-center">LIST QUESTIONS</h1>
  <div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12">
      <table class="table table-responsive table-striped">
        <thead>
          <tr>
            <td><b>Name</b></td>
            <td><b>Email</b></td>
            <td><b>Title</b></td>
            <td><b>Content</b></td>
          </tr>
        </thead>
        <tbody>
        @foreach($questions as $question)
        <tr>
          <td>{{$question->name}}</td>
          <td>{{$question->email}}</td>
          <td>{{$question->title}}</td>
          <td>{!! $question->content !!}</td>
        </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
<div id="section6" class="container-fluid">
  <h1 class="text-center">LIST Map markers</h1>
  <div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12">
      <table class="table table-responsive table-striped">
        <thead>
          <tr>
            <td><b>Name</b></td>
            <td><b>Latitude</b></td>
            <td><b>Longitude</b></td>
            <td><b>Action</b></td>
          </tr>
        </thead>
        <tbody>
        @foreach($maps as $map)
        <tr>
          <td>{{$map->name}}</td>
          <td>{{$map->longitude}}</td>
          <td>{{$map->latitude}}</td>
          <td>
            <form method="POST" action="{{URL::to('/admin')}}/maprm/{{$map->id}}">
              {!! csrf_field() !!}
              <input type="submit" onclick="return confirm('Are you sure to delete this marker?')" value="Delete" class="btn btn-danger">
            </form>
          </td>
        </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
<div id="section7" class="container" style="margin-bottom:20px;">
  <h1 class="text-center">Add New Map Marker</h1>
  <div class="row">
    <form method="POST" action="{{URL::to('/admin/newmap')}}">
    {!! csrf_field() !!}
      <div class="form-group">
        <label for="name">Nama Rumah Sakit / Dr:</label>
        <input type="name" class="form-control" id="name" name="name" required>
      </div>
      <div class="form-group">
        <label for="lat">Latitude</label>
        <input type="text" class="form-control" id="lat" name="lat" required>
      </div>
      <div class="form-group">
        <label for="long">Longitude</label>
        <input type="text" class="form-control" id="long" name="long" required>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
</div>
</body>
@endsection