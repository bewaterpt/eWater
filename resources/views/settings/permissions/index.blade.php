@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">{{array_shift($rolesHeader)}}</div>
                <div class="card-body">
                    <form action="{{Route('settings.permissions.edit')}}" method="POST">
                        @csrf
                        <table>
                            @foreach( $categorizedRoutes as $category => $categorizedRoute )
                                <?php
                                    $counter = 0;
                                ?>
                                <tr class="area">
                                    <th class="category-category" >
                                        <div class="header">
                                            {{ __('permissions.'.$category) }}
                                        </div>
                                    </th>
                                    @foreach( $rolesHeader as $role_header )
                                        <th class="role-name">
                                            <div class="header">
                                                {{ $role_header }}
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            {{-- @endforeach
                            @foreach( $categorizedRoutes as $category => $categorizedRoute ) --}}
                                @foreach( $categorizedRoute as $translation )
                                    <tr>
                                        <td class="extra-padding-left-25">@lang('permissions.'.$translation)</td>
                                        <?php
                                            $selectCount = 1;
                                        ?>
                                        @foreach( $roleData as $role )

                                            <td class="text-center permission-value">
                                                @if( isset( $role['permissions'][$category][$counter] ) )
                                                    @if( $role['permissions'][$category][$counter]['checked'] )
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
                            @endforeach
                        </table>
                        <input type="submit" class="btn btn-primary mg-t-30 mg-b-30" value="{{__('general.save')}}">
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
