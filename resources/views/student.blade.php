@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Student page</div>

                <div class="panel-body">
                    @if (isset($user)) 
                    Hello, {{ $user->name }}! <br> You are logged in! 
                    @else
                    Not logged in
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
