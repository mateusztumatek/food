<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <style>
        html{
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
        }
        body{
            padding: 120px 90px;
            background: rgba(73,155,234,1);
            background: -moz-linear-gradient(left, rgba(73,155,234,1) 0%, rgba(32,124,229,1) 100%);
            background: -webkit-linear-gradient(left, rgba(73,155,234,1) 0%, rgba(32,124,229,1) 100%);
            background: -o-linear-gradient(left, rgba(73,155,234,1) 0%, rgba(32,124,229,1) 100%);
            background: -ms-linear-gradient(left, rgba(73,155,234,1) 0%, rgba(32,124,229,1) 100%);
            background: linear-gradient(to right, rgba(73,155,234,1) 0%, rgba(32,124,229,1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#499bea', endColorstr='#207ce5', GradientType=1 );
        }
        table > td, p, td{
            text-align: center;
        }
    </style>
</head>
<body>
<table style="width:100%; background-color: white" >
    <tr style="width: 100%">
        <td colspan="3" style="padding: 30px; text-align: center" style="width: 100%"><img style="max-width: 30px" src="{{storage_path('/app/public/ikona.png')}}"></td>
    </tr>
    <tr>
        <td colspan="3" style="padding: 30px;">
            <p style="text-align: center">{{$sale->name}}</p>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <img style="width: 600px" src="{{storage_path('/app/public/qrs/'.$sale->id.'.png')}}">
        </td>
    </tr>
</table>
</body>
</html>
