@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Users</div>
                <div class="card-body">
                    <table style="width: 100%">
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                Username
                            </th>
                            <th>
                                Roles
                            </th>
                        </tr>
                        @foreach( $users as $user)
                            <tr>
                                <td>
                                    {{$user['name']}}
                                </td>
                                <td>
                                    {{$user['username']}}
                                </td>
                                <td>
                                    @foreach($user['roles'] as $role)
                                        {{$role->name}}
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

