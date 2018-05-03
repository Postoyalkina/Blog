@extends ('layouts.layout')

@section('header')
    <div class="dropdown">
        <a class="btn btn-light dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{\Auth::user()->name}}
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="{{'/home'}}">Home</a>
            <a class="dropdown-item" href="{{route('logout')}}">Logout</a>
        </div>
    </div>
@endsection

@section('content')
    <form method="post">
        {!! csrf_field() !!}
    <div class="form-group">
        <input class="form-control" id="exampleFormControlInput1" placeholder="Enter title" name="title">
    </div>
    <div class="form-group">
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="15" placeholder="Enter body" name="body"></textarea>
    </div>
        <div>
            <button class="btn btn-lg btn-primary" type="submit">Create</button>
        </div>
    </form>

@endsection