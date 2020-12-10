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
        <table role="presentation" style="max-width: 600px;text-align: center;border-collapse: collapse;border: 1px solid rgb(206 206 206 / 80%);font-family: Nunito, sans-serif;box-shadow: 0px 0px 20px -10px #000000d1;" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td style="padding: 10px;box-shadow: 0px 2px 6px 0px rgb(50 50 50 / 21%);">
                    <img src="{{asset('/images/ewater-logo-mail.png') }}" alt="eWater Logo" style="height: 70px;margin-right: 10px;">
                </td>
            </tr>
            @yield('mail-content')
        </table>
    </body>
</html>
