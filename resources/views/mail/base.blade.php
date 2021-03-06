<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-GB">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <style>
            @import url(https://fonts.googleapis.com/css?family=Nunito);
        </style>
        @yield('styling')
    </head>
    <body style="max-width: 600px;">
        <table role="presentation" style="background-color:rgb(0 0 0 / 2%);max-width: 600px;border-collapse: collapse;border: 1px solid rgb(206 206 206 / 80%);font-family: Nunito, sans-serif;box-shadow: 0px 0px 20px -10px #000000d1;" cellpadding="0" cellspacing="0" width="100%">
            <tr style="box-shadow: -1px 1px 6px 0px rgb(50 50 50 / 21%);">
                <td style="padding:20px;width:40%;text-align: center;">
                    <img src="{{ 'data:image/png;base64, '.base64_encode(file_get_contents(public_path('/images/mail-logo.png'))) }}" alt="eWater Logo" style="height: 70px;margin-right: 10px;">
                </td>
                <td style="padding:20px;">
                    <div style="width: 100%;text-align: center;">@Lang('mail.interruptions.warning')</div>
                    <br>
                    <div style="width:100%;text-align: left">@Lang($translationString)</div>
                </td>
            </tr>
            <tr>
                <td style="padding: 30px 20px;text-align: left;" colspan="2">
                    @yield('mail-content')
                </td>
            </tr>
            <tr class="footer">
                <td style="font-size: 11px; padding: 10px 20px 30px; text-align: justify" colspan="2">
                    {!! __('mail.interruptions.disclaimer', ['email' => $delegation->email]) !!}
                </td>
            </tr>
            {{-- <tr>
                <td style="color: gray; padding: 10px 0; font-size: 20px;box-shadow: 0px -1px 6px 0px rgb(50 50 50 / 21%);" colspan="2">
                    <b>{{ config('app.name') }}</b>
                </td>
            </tr> --}}
        </table>
    </body>
</html>
