@extends('layouts.app')

@section('contents_table')
<div class="container">
    <div class="card">
        <div class="card-header">Revenue</div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush