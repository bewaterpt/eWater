@extends('layouts.app')

@section('content')
    {{-- <a id="report-popover" href="#" data-placement="bottom" data-toggle="popover" data-trigger="focus">Hey</a>
    @include('layouts.partials.dynamic_form', ['form' => $form]) --}}
    {{-- <div id="map" style="height: 700px"></div> --}}

    @foreach($permissions as $permission)

        <div>{{$permission->roles()->get()}}</div>
        {{-- @foreach ($permission->roles()->get() as $role)

            <div>{{$permission->route." = ".$role->name}}</div>

        @endforeach --}}

    @endforeach



@endsection
