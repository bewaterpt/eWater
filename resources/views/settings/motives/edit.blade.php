@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form id="updateUser" method="POST" action={{Route('interruptions.motives.update', ['id' => $motive->id])}}>
                        @csrf
                        <input type="hidden" name="users" id="users" />
                        <div class="form-row mb-2 justify-content-center">
                            <div class="form-group col-md-12">
                                <label for="inputName">@Lang('forms.fields.motive')</label>
                                <input type="text" name="name" class="form-control" id="inputName" value="{{ $motive->name }}" required>
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

