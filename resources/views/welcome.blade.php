@extends ('layouts.layout')

@section('title')
    Blog
@endsection

@section('header')
    @if(Auth::guest())
        <div class="btn-group" role="group" aria-label="Basic example">
            <a class="btn btn-dark" href="{{'login'}}">Login</a>
            <a class="btn btn-dark" href="{{route('register')}}">Register</a>
        </div>
    @else
        <div class="dropleft">
            <a class="btn btn-secondary dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{\Auth::user()->name}}
            </a>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="{{'/home'}}">Home</a>
                <a class="dropdown-item" href="{{'create'}}">Create post</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{route('logout')}}">Logout</a>
            </div>
        </div>
    @endif
@endsection

@section('content')
    @if (count($posts) > 0)
        @foreach ($posts as $post)
            <div class="blog-post">
                <div class="row">
                    <div class="blog-post-title col-sm-11">
                        <h2 class="blog-post-title">
                            <a class="post-title text-dark" href="{{route('showPost',[$post->id])}}"><?php echo htmlspecialchars($post->title); ?></a>
                        </h2>
                    </div>
                    <div class="col-sm-1">
                        @if(!Auth::guest())
                        @if(Auth::user()->id==$post->user_id)
                            <div class="dropdown">
                                <a class="btn btn-light" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    &#926
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="{{route('edit',['id'=>$post->id])}}">Edit</a>
                                    <a class="dropdown-item" href="delete/{{ $post->id }}">Delete</a>
                                </div>
                            </div>
                        @endif
                        @endif
                    </div>
                </div>
                <p class="blog-post-meta">{{$post->created_at}}, <a href="{{route('user_posts',['user_id'=>$post->user_id])}}">{{DB::table('users')->where('id',$post->user_id)->value('name')}}</a></p>
                <p style="white-space: pre-wrap;"><?php echo htmlspecialchars($post->body); ?></p>
                <p class="blog-post-meta" href="{{route('showPost',[$post->id])}}>{{DB::table('comments')->where('post_id',$post->id)->count()}} comments</p>
            </div>
        @endforeach
    @endif
@endsection