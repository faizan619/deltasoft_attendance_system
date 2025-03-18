@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        Welcome to addmission {{Auth::user()->username}}
    </div>
@endsection