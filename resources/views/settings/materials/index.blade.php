@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">
                    @Lang('settings.materials.list')
                    <span class="float-right">
                        <a href="{{Route('settings.materials.create')}}"><i class="fas fa-plus"></i></a>
                    </span>
                </div>
                <div class="card-body">
                    <table id="datatable-users" class="object-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>
                                    @Lang('general.designation')
                                </th>
                                <th>
                                    @Lang('general.failure_type')
                                </th>
                                {{-- <th>
                                    @Lang('general.state')
                                </th> --}}
                                <th>
                                    @Lang('general.actions')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materials as $material)
                                <tr>
                                    <td>
                                        {{$material->designation}}
                                    </td>
                                    <td>
                                        {{$material->failureType()->first()->designation}}
                                    </td>
                                    {{-- <td style="text-align: center">
                                        @if($type->enabled)
                                            <i class="fas fa-check" style="color: limegreen"></i>
                                        @else
                                            <i class="fas fa-times" style="color: red"></i>
                                        @endif
                                    </td> --}}
                                    <td class="actions">
                                        <a class="" href="{{Route('settings.materials.edit', ['id' => $material->id])}}" title="@Lang('general.action_edit')"><i class="fas fa-edit"></i></a>
                                        <a href="{{Route('settings.materials.delete', ['id' => $material->id])}}" class="delete" title="{{__('general.action_delete')}}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

