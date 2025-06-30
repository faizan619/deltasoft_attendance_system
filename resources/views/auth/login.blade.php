@extends('layouts.app')

@section('title','login Page')
@section('styleTag')
<style>
    /* .loginpage {
        height: 100vh;
    } */

    /* .loginbg {
        background-image: url("{{asset('img/white.jpg')}}");
        background-size: cover;
    } */
</style>
@endsection

@section('content')
<div class="container-fluid loginbg">
    <div class="row d-flex justify-content-center align-items-center loginpage mt-4 pt-3">
        <div class="col-md-5 mx-auto">
        {{-- @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif --}}
        @if (session('success'))
        <div class="alert alert-success w-100">
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger w-100">
            {{ session('error') }}
        </div>
        @endif
            <div class="card shadow">
                <div class="card-header bg-dark text-light">Login Form</div>
                <div class="card-body">
                    <form action="{{ route('LoginProceed') }}" method="POST" class="row">
                        @csrf
                        <div class="col-md-12">
                            <label for="email">Email : </label>
                            <input type="email" name="email" id="email"  placeholder="deltasoft" class="form-control mt-2">
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="password">Passowrd : </label>
                            <input type="password" name="password" id="password" placeholder="deltasoft@123" class="form-control mt-2">
                        </div>
                        <div class="col-12 mt-2">
                            <div class="form-check">
                                <!-- <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required> -->
                                <input class="form-check-input" type="checkbox" name="remember" id="invalidCheck">

                                <label class="form-check-label" for="invalidCheck">
                                    <!-- Agree to terms and conditions -->
                                     Remember Me
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