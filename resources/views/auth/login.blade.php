@extends('layouts.app')

@section('title','login Page')
@section('styleTag')
<style>
    .loginpage {
        height: 100vh;
    }

    .loginbg {
        background-image: url("{{asset('img/chair.jpg')}}");
        background-size: cover;
    }
</style>
@endsection

@section('content')
<div class="container-fluid loginbg">
    <div class="row d-flex justify-content-center align-items-center loginpage">
        <div class="col-md-5 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-dark text-light">Login Form</div>
                <div class="card-body">
                    <form action="{{ route('LoginProceed') }}" method="POST" class="row">
                        @csrf
                        <div class="col-md-12">
                            <label for="username">UserName : </label>
                            <input type="text" name="username" id="username"  placeholder="deltasoft" class="form-control mt-2">
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="password">Passowrd : </label>
                            <input type="password" name="password" id="password" placeholder="deltasoft@123" class="form-control mt-2">
                        </div>
                        <div class="col-12 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                <label class="form-check-label" for="invalidCheck">
                                    Agree to terms and conditions
                                </label>
                                <div class="invalid-feedback">
                                    You must agree before submitting.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4 d-flex justify-content-center">
                            <button class="btn btn-danger">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection