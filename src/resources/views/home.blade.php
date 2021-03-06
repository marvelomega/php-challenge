@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>

                <div class="card-body">
                <a class="btn btn-success btn-sm" href="{{ url('/people') }}">
                    People
                </a>
                <a class="btn btn-success btn-sm" href="{{ url('/ship-order') }}">
                    Ship Order
                </a>

                </div>

            </div>
        </div>

    </div>
</div>
@endsection


