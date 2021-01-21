@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">
                    @Lang('general.interruptions.interruptions')
                    <span class="float-right">
                        <a class="btn-link text-success" href="{{ route('settings.motives.create') }}"><i class="fas fa-plus"></i></a>
                    </span>
                </div>
                <div class="card-body table-responsive">
                    <table id="datatable-motives" class="object-table table table-sm table-striped" style="width: 100%">
                        <thead class="thead-light">
                            <tr>
                                <th class="actions text-center px-0">
                                    <i class="fas fa-tools text-black sorting_disabled"></i>
                                </th>
                                <th>
                                    @Lang('general.name')
                                </th>
                                <th>
                                    @Lang('general.scheduled')
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

