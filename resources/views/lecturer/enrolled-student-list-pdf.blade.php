<!DOCTYPE html>
<html>
<head>
	<title>Enrolled Students</title>
	<style type="text/css">
        table{
      width: 100%;
    border-cobackground: #0a3a2d;llapse: collapse;
    border-spacing: 0;
    line-height: 20px;
    }
    th{
    border-bottom-width: 2px;
    text-align: center !important;
    font-size: 16px;
    font-family: system-ui;
    font-weight: 500;
    color: #ffffff;border-bottom-width: 2px;
    background: #035594!important;
    /*background: #0a3a2d;*/
    border: 0.5px solid #eee;
    padding: 2px;
    font-family: system-ui;
    font-weight: 500;
    }
    
    td {
    border: 0.5px solid #eee;
    line-height: 1.428571;
    text-align: center;
    font-family: sans-serif;
    color: #525252;
    font-size: 14px;
}
</style>
</head>
<body>
    <div style="width:100%; height:auto;background:#fff;margin-bottom: 20px;text-align: center;">
        <img src="{{ asset('img')}}/logo.jpg"  style="float:left;margin-top:-12px;position:relative;">
        <img src="{{ asset('img')}}/logo.jpg" style="float:right;margin-top:-12px;position:relative;">
                <h3 style="text-align:center;position:relative;margin-top:-5px;font-family:sans-serif;margin: 0;">PROJAL UNIVERSITY COLLEGE</h3>
        <b style="text-align:center;position: relative;left: -10px;display: block;">GSA Road, Morning Star Compund, Paynesville City</b>
        <b style="text-align:center;position: relative;left: -5px;display: block;">Montsrrado County, Republic of Liberia</b>
    </div>
  <p style="text-align: center;text-decoration:underline;">Enrolled Students</p>
    <p style="margin:0px 0 4px;">Lecturer: <b>{{ $lecturer ?? '' }}</b></p>
    <p style="margin:0px 0 4px;">Course: <b>{{ $course ?? '' }}</b></p>
    <p style="margin:0px 0 4px;">Academic Year: <b>{{ $year ?? '' }}</b></p>
    <p style="margin:0px 0 4px;">Semester: <b>{{ $semester ?? '' }}</b></p>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Student ID</th>
                    <th>College </th>
                </tr>
            </thead>
            <tbody style="background-color:#ffffff;">
                {{ $count = 0}}
                @foreach($records as $key => $card)
                    {{ $count++ }}
                    <tr>
                        <td>{{ $count }}</td>
                        <td>{{ $card['student_id'] }}</td>
                        <td>{{ $card['college'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>