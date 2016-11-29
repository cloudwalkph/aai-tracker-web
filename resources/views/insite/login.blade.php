@extends('layouts.insite')

@section('page-css')
    <style>
        .login {
            position: relative;
            top: 50%;
            transform: translateY(50%);
        }

        .btn {
            width: 100%;
        }

        .logo {
            width: 100%;
            margin-bottom: 20px;
            text-align: center;
        }

        .logo img {
            width: 100%;
            max-width: 400px;
        }
    </style>
@endsection

@section('content')
    <div class="container login">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="logo">
                    <img src="/images/insite-logo.png" alt="Activations Insite logo">
                </div>

                <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <div class="col-md-12">
                            <input id="email" type="email" class="form-control"
                                   placeholder="Email Address"
                                   name="email" value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                        <div class="col-md-12">
                            <input id="password" type="password"
                                   placeholder="Password"
                                   class="form-control" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-2">
                            <button type="submit" class="btn btn-primary">
                                Login
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
