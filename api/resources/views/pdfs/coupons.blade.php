<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>


    <link href="{{public_path('/css/pdfs.css')}}" rel="stylesheet">
    {{--<link href="{{asset('/css/pdfs.css')}}" rel="stylesheet">--}}
    <style>
        @font-face {
            font-family: 'Nunito Sans', sans-serif;
            src: {{ storage_path('fonts/NunitoSans-Black.ttf') }} format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        @font-face {
            font-family: 'Nunito Sans', sans-serif;
            src: {{ storage_path('fonts/NunitoSans-ExtraLight.ttf') }} format('truetype');
            font-weight: 200;
            font-style: normal;
        }
        body {
            font-family: "Nunito Sans", sans-serif !important;
        }
        html{
            width: 94%;
            height: 94%;
            margin: auto;
            padding: 15px;
            margin: 10px;
        }
        table, tr{
            border: none !important;
        }
        table, th{
            border: none !important;
        }
        .table>tbody>tr>td{
            border-top: none;
        }
    </style>


</head>
<body>
<table class="table table-condensed">
    <col width="33%">
    <col width="33%">
    <col width="33%">
    @foreach($chunk as $element)
    <tr>
        @foreach($element as $code)
            <td colspan="1">
                <table style="height: 400px; max-height: 400px;">
                    <col width="50">
                    <col width="50">
                    <tr>
                        <td colspan="2">
                            <img style="max-width: 120px" src="{{storage_path('/app/public/foody.png')}}">
                            {{--<img src="{{url('/storage/foody.png')}}">--}}
                        </td>
                    </tr>
                </table>
            </td>
        @endforeach
    </tr>
    @endforeach
</table>
<table class="table table-condensed" style="width: 100%">
    <col width="33%">
    <col width="33%">
    <col width="33%">
    @foreach($chunk as $element)
        <tr>
            @foreach($element as $code)
                <td colspan="1">
                    <table style="height: 400px; max-height: 400px;">
                        <col width="50">
                        <col width="50">
                        <tr>
                            <td colspan="2" class="code-back">
                                <h2 class="discount-span">-50<span>%</span></h2>
                                <div class="code">
                                    QWR2231
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            @endforeach
        </tr>
    @endforeach
</table>
   {{-- <div class="row">
        @foreach($codes as $code)
        <div class="col-md-4">
            <div class="code-holder code-back">
                <img src="{{storage_path('/app/public/foody.png')}}">
            </div>
            {{$code->code}}
        </div>
            @endforeach
    </div>--}}
</body>
</html>
