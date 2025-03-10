@extends('umkm::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('umkm.name') !!}
    </p>
@endsection
