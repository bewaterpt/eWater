@extends('layouts.app')

@section('content')
    {{-- <a id="report-popover" href="#" data-placement="bottom" data-toggle="popover" data-trigger="focus">Hey</a> --}}
    @include('layouts.partials.dynamic_form', ['form' => $form])
@endsection
