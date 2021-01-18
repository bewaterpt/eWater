@extends('mail.base')

@section('styling')
<style>
    .button {
        height: 35px;
        padding: 0 10px;
        border: none;
        border-radius: 4px;
        background-color: rgb(9, 148, 255);
        margin: auto;
        margin-top: 30px;
        right: 0;
        left: 0;
        font-weight: 700;
        color: white;
        pointer-events: all;
    }

    .button-container {
        pointer-events: none;
    }

    .button:hover {
        cursor: pointer;
        background-color: rgb(9, 115, 196);
    }

    .button:active {
        box-shadow: inset 1px -3px 10px 0px rgb(0 0 0 / 50%)
    }
</style>
@endsection

@section('mail-content')
    {{-- @Lang('mail.interruptions.' . $scheduled . '.created')
    <br>
    <br> --}}
    <table style="text-align: left;border-collapse: collapse;" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>
                <b>@Lang('mail.interruptions.ref'):</b> {{ $interruption->work_id }}
            </td>
        </tr>
        <tr>
            <td>
                <b>@Lang('general.interruptions.start_date'):</b> {{ $carbon->parse($interruption->start_date)->format('Y-m-d H:i:s') }}
            </td>
        </tr>
        <tr>
            <td>
                <b>@Lang('general.interruptions.reinstatement_date'):</b> {{ $carbon->parse($interruption->reinstatement_date)->format('Y-m-d H:i:s') }}
            </td>
        </tr>
        <tr>
            <td>
                <br>
                <b>@Lang('general.interruptions.affected_area'): </b>
                {!! $interruption->affected_area !!}
            </td>
        </tr>
    </table>
@endsection
