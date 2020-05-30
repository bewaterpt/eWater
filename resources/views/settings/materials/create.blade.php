@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @Lang('settings.materials_create')
                </div>
                <div class="card-body">
                    <form method="POST" action={{Route('settings.materials.create')}}>
                        @csrf
                        <div class="form-row mb-2 justify-content-center">
                            <div class="form-group col-md-12">
                                <label for="inputDesignation">@Lang('forms.fields.designation')</label>
                                <input type="text" name="designation" class="form-control @error('designation') is-invalid @enderror" id="inputDesignation" value="" placeholder="{{__('forms.placeholders.designation')}}" required>
                                @error('designation')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="inputFailureType">@Lang('forms.fields.failure_type')</label>
                                <select type="text" name="failureType" class="form-control @error('failureType') is-invalid @enderror" id="inputFailureType" required>
                                    @foreach($failureTypes as $failureType)
                                        <option value="{{$failureType->id}}">{{$failureType->designation}}</option>
                                    @endforeach
                                </select>
                                @error('FailureType')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-right">@Lang('general.save')</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

