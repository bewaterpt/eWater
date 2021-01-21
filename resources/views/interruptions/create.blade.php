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
                    <form method="POST" action={{Route('interruptions.store')}}>
                        @csrf
                        <input type="hidden" name="users" id="users" />
                        <div class="form-row mb-2 justify-content-center">
                            <div class="form-group col-md-4 pt-4 {{ $currentUser->countRoles(['ewater_interrupcoes_programadas_criacao', 'ewater_interrupcoes_nao_programadas']) == 2 || $currentUser->hasRoles(['admin']) ? '' : 'invisible' }}">
                                <input type="radio" name="scheduled" class="" id="inputScheduled1" value="true" {{ $scheduled ? 'checked' : '' }}>
                                <label for="inputScheduled1">@Lang('general.interruptions.is_scheduled')</label>
                                <br>
                                <input type="radio" name="scheduled" class="" id="inputScheduled2" value="false" {{ !$scheduled ? 'checked' : '' }}>
                                <label for="inputScheduled2">@Lang('general.interruptions.is_unscheduled')</label>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputWorkId">@Lang('forms.fields.work_number')</label>
                                <input type="number" name="work_id" class="form-control" id="inputWorkId" value="" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputDelegation">@Lang('forms.fields.delegation')</label>
                                <select name="delegation" id="inputDelegation" class="form-control">
                                    @foreach($delegations as $delegation)
                                        <option value="{{ $delegation->id }}">{{ $delegation->designation }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputStartDate">@Lang('forms.fields.start_date')</label>
                                <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" id="inputStartDate" value="" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputReinstatementDate">@Lang('forms.fields.reinstatement_date')</label>
                                <input type="datetime-local" name="reinstatement_date" class="form-control @error('reinstatement_date') is-invalid @enderror" id="inputReinstatementDate" value="" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="inputMotive">@Lang('forms.fields.motive')</label>
                                <select name="motive" id="inputMotive" class="form-control">
                                    @foreach($motives as $motive)
                                        <option value="{{ $motive->id }}">{{ $motive->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="inputAffectedArea">@Lang('forms.fields.affected_area')</label>
                                <textarea name="affected_area" id="inputAffectedArea" class="form-control text-editor"></textarea>
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

