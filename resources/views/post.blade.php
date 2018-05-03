@extends ('layouts.layout')

@section('title')
    Post
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
    <div class="blog-post" style="margin-bottom: 0; border-bottom: none">
        <div class="row">
            <div class="col-sm-11">
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
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href=delete/{{ $post->id }}">Delete</a>
                        </div>
                    </div>
                @endif
                @endif
            </div>
        </div>
        <p class="blog-post-meta">{{$post->created_at}}, <a href="{{route('user_posts',['user_id'=>$post->user_id])}}">{{DB::table('users')->where('id',$post->user_id)->value('name')}}</a></p>
        <p><?php echo htmlspecialchars($post->body); ?></p>
    </div>
    @if(!Auth::guest())
        <form method="post">
            {!! csrf_field() !!}
            <div class="input-group mb-3" style="margin-bottom: 5px!important">
            <input type="text" class="form-control" placeholder="Add comment..." aria-label="Comment" aria-describedby="basic-addon2" name="body" style="border: none; border-radius: 0" required>
            <div class="input-group-append" >
                <button class="btn btn-outline-secondary" style="border-radius: 0;border: none; border-left: #e5e5e5 1px solid; background-color: white" type="submit">Add</button>
            </div>
            </div>
        </form>
    @endif

    @if (count($comments) > 0)
        <div style="margin-top: 10px">
        @foreach ($comments as $comment)
            <div class="blog-comments">
                <div class="row">
                    <div class="col-sm-11">
                <p class="blog-post-meta">{{$comment->created_at}}, <a href="{{route('user_posts',['user_id'=>$comment->user_id])}}">{{DB::table('users')->where('id',$comment->user_id)->value('name')}}</a></p>
                    </div>
                    <div class="col-sm-1">
                        @if(!Auth::guest())
                            @if(Auth::user()->id==$comment->user_id)
                                <div class="dropdown">
                                    <a class="btn btn-white" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        &#926
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" onclick="edit(this,{{$comment->id}})">Edit</a>
                                        <script>
                                            function edit(x,id){
                                                var commentDiv = x.parentNode.parentNode.parentNode.parentNode.parentNode;
                                                var commentTextDiv = commentDiv.getElementsByTagName('p')[1];
                                                var text = commentTextDiv.innerHTML;
                                                var commentEditForm = document.getElementsByTagName('form')[0].cloneNode(true);
                                                commentEditForm.getElementsByTagName('input')[1].value = text;
                                                commentEditForm.getElementsByTagName('button')[0].innerHTML = 'Edit';
                                                commentEditForm.getElementsByTagName('button')[0].setAttribute('href','');
                                                commentDiv.replaceChild(commentEditForm,commentTextDiv);
                                                commentEditForm.getElementsByTagName('input')[1].focus();
                                                var idInput = document.createElement('input');
                                                idInput.setAttribute('name','id');
                                                idInput.setAttribute('type','hidden');
                                                commentEditForm.appendChild(idInput);
                                                commentEditForm.getElementsByTagName('input')[2].value = id;
                                            }
                                        </script>
                                        <a class="dropdown-item" href="delete-comment-{{ $comment->id }}">Delete</a>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                <p style="margin-bottom: 0;white-space: pre-wrap;" name="commentBody"><?php echo htmlspecialchars($comment->body); ?></p>
            </div>
        @endforeach
        </div>
    @endif
@endsection