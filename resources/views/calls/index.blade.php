@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">
                    @Lang('calls.pbx.list')
                    <span class="float-right">
                        <a class="text-success" href="{{Route('calls.pbx.create')}}"><i class="fas fa-plus"></i></a>
                    </span>
                </div>
                <div class="card-body table-responsive">
                    <table id="datatable-calls" class="object-table table table-sm table-striped" style="width: 100%">
                        <thead class="thead-light">
                            <tr>
                                <th class="actions text-center px-0">
                                    <i class="fas fa-tools text-black sorting_disabled"></i>
                                </th>
                                <th>
                                    @Lang('general.designation')
                                </th>
                                <th>
                                    @Lang('general.t1')
                                </th>
                                <th>
                                    @Lang('pbx.t2')
                                </th>
                                <th>
                                    @Lang('general.t3')
                                </th>
                                <th>
                                    @Lang('general.t4')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    {{-- {{ $cdrs->links() }} --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

