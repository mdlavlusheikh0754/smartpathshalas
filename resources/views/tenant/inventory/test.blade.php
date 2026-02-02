@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <h1>Inventory Test Page</h1>
    <p>This is a test to see if the view is working.</p>
    <p>Items count: {{ count($items) }}</p>
</div>
@endsection