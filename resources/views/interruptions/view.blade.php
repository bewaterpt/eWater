@extends('layouts.app')

@section('content')
<div id="calls-pbx-create" class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @Lang('general.interruptions.create') {{ $type }}
                </div>
                <div class="card-body">
                    <div></div>
                    <div></div>
                    <button type="submit" class="btn btn-primary float-right">@Lang('general.save')</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

