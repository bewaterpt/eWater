@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{array_shift($rolesHeader)}}</div>
                <div class="card-body overflow-x-auto">
                    <form id="settings-permissions" action="{{Route('settings.permissions.edit')}}" method="POST">
                        @csrf
                        <table class="table table-sm table-striped fix-first-col">
                            @foreach( $categorizedRoutes as $category => $route )
                                <thead class="">
                                    <?php
                                        $counter = 0;
                                    ?>
                                    <tr class="area pt-3">
                                        <th class="category-category mt-3" >
                                            <div class="header">
                                                {{ __('permissions.'.$category) }}
                                            </div>
                                        </th>
                                        @foreach( $rolesHeader as $role_header )
                                            <th class="role-name mt-3">
                                                <div class="header">
                                                    {{ $role_header }}
                                                </div>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach( $route as $translation )
                                        <tr>
                                            <td class="extra-padding-left-25">@lang('permissions.'.$translation)</td>
                                            <?php
                                                $selectCount = 1;
                                            ?>
                                            @foreach( $roleData as $role )

                                                <td class="text-center permission-value">
                                                    @if(isset( $role['permissions'][$category][$counter]))
                                                        @if($role['permissions'][$category][$counter]['checked'])
                                                            <input type="checkbox" class="square green" name="[{{ $role['role'] }}][{{ $role['permissions'][$category][$counter]['route'] }}]" value="1" checked>
                                                        @else
                                                            <input  type="checkbox" class="square green" name="[{{ $role['role'] }}][{{ $role['permissions'][$category][$counter]['route'] }}]" value="1">
                                                        @endif
                                                    @endif
                                                </td>
                                                <?php $selectCount++; ?>
                                            @endforeach
                                            <?php $counter++; ?>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @endforeach
                            </table>
                            <button type="submit" class="btn btn-primary">
                                <span class="btn-text">@Lang('forms.buttons.save')</span>
                                <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <span id="spinner-text" class="loading-text d-none">@Lang('general.loading')</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
