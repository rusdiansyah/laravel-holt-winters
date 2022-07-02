@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Rice Type Import') }}
                </div>

                <div class="card-body">
                    <form action="{{ route('rice_by_stock_import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="form-control">
                        <br>
                        <button class="btn btn-success">Import User Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
