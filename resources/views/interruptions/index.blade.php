@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @Lang('general.interruptions.interruptions')
                    <span class="float-right">
                        <a class="btn-link text-success" href="{{ route('interruptions.create') }}"><i class="fas fa-plus"></i></a>
                    </span>
                </div>
                <div class="card-body table-responsive">
                    <table id="datatable-interruptions" class="table table-sm table-striped table-bordered order-column" style="width: 100%">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center px-3 sorting-disabled">
                                    <i class="fas fa-tools p-0 text-black"></i>
                                </th>
                                <th>
                                    @Lang('general.work_number')
                                </th>
                                <th>
                                    @Lang('general.start_date')
                                </th>
                                <th class="limit-w-45">
                                    @Lang('general.interruptions.affected_area')
                                </th>
                                <th class="limit-w-15">
                                    @Lang('general.interruptions.reinstatement_date')
                                </th>
                                {{-- <th>
                                    @Lang('general.coordinates')
                                </th> --}}
                                <th class="text-center ">
                                    @Lang('general.type')
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

