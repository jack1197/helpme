@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Tutor page</div>

                <div class="panel-body">
                    @if (Auth::guest() )
                    Not logged in.{{ $ses }}
                    @else
                    Hello, {{ Auth::user()->name }}!
                    @endif
                    <h3>New class:</h3>
                    <div class="panel-body well">
                        <form class="form-horizontal">
                            <h4>Class information:</h4>
                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">name:</label>
                                <div class="col-sm-10">
                                    <input type="text" name="classname" id="name" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-8">
                                    <button type="submit" name="submit_type" value="update" class="btn btn-primary">
                                        Submit
                                    </button>
                                    <button type="submit" name="submit_type" value="cancel" class="btn btn-danger">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
