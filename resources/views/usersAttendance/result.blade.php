@extends('layouts.app')

@section('content')
<div>
    @if(session('status'))
    <div class="alert alert-success my-4 col-md-8 mx-auto">
        {{ session('status') }}
    </div>
    @endif

    <div class="card col-md-8 mt-4 mx-auto">
        <div class="card-header bg-primary text-light text-capitalize">{{Auth::user()->username}} Attendance Records</div>
        <div class="card-body px-1 pb-0">
            <p>
                <form action="{{ route('reached') }}" class="row">
                    <div class="col-md-3">
                        <input type="date" name="start" id="start" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="end" id="end" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-sm btn-danger">Search</button>
                        <a class="btn btn-sm btn-secondary" href="{{ route('reached') }}" type="button">Reset</a>
                    </div>
                </form>
            </p>
            <table class="table table-sm table-striped table-bordered">
                <thead class="">
                    <tr>
                        <th>Date</th>
                        <th>CheckIn</th> 
                        <th>CheckOut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $att)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($att->created_at)->format('jS F Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($att->checkIn)->format('g:i A') }}</td>
                            <td>{{ $att->checkOut ? \Carbon\Carbon::parse($att->checkOut)->format('g:i A') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No Attendance Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $attendances->links() }}
        </div>
    </div>
</div>
@endsection