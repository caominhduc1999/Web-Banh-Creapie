@extends('master')

@section('content')
    <div class="inner-header">
        <div class="container">
            <div class="pull-left">
                <h6 class="inner-title">Đăng kí</h6>
            </div>
            <div class="pull-right">
                <div class="beta-breadcrumb">
                    <a href="{{route('trangchu')}}">Home</a> / <span>Đăng kí</span>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="container">
        <div id="content">

            <form action="{{route('signin')}}" method="post" class="beta-form-checkout">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="row">
                    <div class="col-sm-3"></div>

                    <div class="col-sm-6">
                        <h4>Đăng kí</h4>
                        <div class="space20">&nbsp;</div>
                        @if(count($errors) > 0)
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $err)
                                    {{$err}}<br>
                                @endforeach
                            </div>
                        @endif

                        {{--cannot alert--}}
                        @if(Session::has('thongbao'))
                            <div class="alert alert-success">{{Session('thongbao')}}</div>
                        @endif
                        {{--cannot alert--}}


                        <div class="form-block">
                            <label>Email address*</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" >
                        </div>

                        <div class="form-block">
                            <label>Fullname*</label>
                            <input type="text" id="your_last_name" name="full_name" value="{{ old('full_name') }}" >
                        </div>

                        <div class="form-block">
                            <label>Address*</label>
                            <input type="text" id="adress" name="address" value="{{ old('address') }}">
                        </div>


                        <div class="form-block">
                            <label>Phone*</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" >
                        </div>
                        <div class="form-block">
                            <label>Password*</label>
                            <input type="password" id="phone" name="password" >
                        </div>
                        <div class="form-block">
                            <label>Re password*</label>
                            <input type="password" id="phone" name="re_password" >
                        </div>
                        <div class="form-block">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </div>
                    <div class="col-sm-3"></div>
                </div>
            </form>
        </div> <!-- #content -->
    </div> <!-- .container -->
@endsection