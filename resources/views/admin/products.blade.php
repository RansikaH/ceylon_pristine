@extends('admin.layout')

@section('content')
<div class="container px-4">
    <h1 class="mt-4">Products</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Products</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Products List
        </div>
        <div class="card-body">
            <p>This is a simple products page. You can add your products listing here.</p>
        </div>
    </div>
</div>
@endsection
