<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Grade Sheet</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    .header, .footer {
      text-align: center;
    }
    .header h2, .header h3 {
      margin: 0;
    }
    .student-info {
      margin: 20px 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }
    th, td {
      border: 1px solid #333;
      padding: 4px;
      text-align: center;
    }
    .footer {
      margin-top: 20px;
    }
    .footer .signatures {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }
    .footer .signatures div {
      text-align: left;
    }
    .method-grade {
      margin-top: 10px;
      text-align: left;
    }
    .header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    position: relative;
}

.header-text {
    text-align: center;
    flex-grow: 1; /* Make sure the text stays centered */
}

.header-image {
    width: 50px; /* Adjust size of images */
    height: auto;
    position: absolute;
}

.header-image.left {
    left: 0;
}

.header-image.right {
    right: 0;
}

  </style>
</head>
<body>

  <div class="header">
    <!-- Left Image -->
    <img src="{{ asset('img/logo.jpg') }}" class="header-image left" alt="Left Image">

    <div class="header-text">
        <h2>PROJAL UNIVERSITY COLLEGE</h2>
        <p>GSA Road, Morning Star Compound, Paynesville City<br>Montserrado County, Liberia</p>
        <h3 style="text-transform: uppercase;">GRADE SHEET {{ $studentEnrollment->Classes->class_name ?? '' }}</h3>
    </div>

    <!-- Right Image -->
    <img src="{{ asset('img/logo.jpg') }}" class="header-image right" alt="Right Image">
</div>


  <div class="student-info">
    <div style="font-size: 18px; margin-bottom: 5px;"><strong>Student's Name: {{ $studentDetail->first_name ?? '' }} {{ $studentDetail->other_name ?? '' }} {{ $studentDetail->last_name ?? '' }}</strong></div>
    <div style="font-size: 18px; margin-bottom: 5px;"><strong>Student ID# {{ $studentDetail->studen_id ?? '' }}</strong></div>
    <div style="font-size: 18px; margin-bottom: 5px;"><strong>College: {{ $studentEnrollment->College->college_name ?? '' }}</strong></div>
    <div style="font-size: 18px; margin-bottom: 5px;"><strong>Major: {{ $studentEnrollment->Major->major ?? '' }}</strong></div>
  </div>

 @if($gradesBySemester->isNotEmpty())
        @foreach($gradesBySemester as $semesterId => $grades)
            @php
    $firstGrade = $grades->first();
    $semesterName = $firstGrade && $firstGrade->semester
        ? $firstGrade->semester->semester
        : 'Semester ' . $semesterId;

                

                // Calculate average
                $validScores = $grades->pluck('point')->filter(fn($p) => is_numeric($p))->map(fn($p) => floatval($p));
                $average = $validScores->isNotEmpty() ? round($validScores->avg(), 2) : null;
                $avgColor = $average !== null && $average < 70 ? 'red' : 'blue';
            @endphp

            <h4 class="mt-4">{{ strtoupper($semesterName) }}</h4>

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Course</th>
                        <th>Code</th>
                        <th>Grade</th>
                        <th>Point</th>
                        <th>Observation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grades as $grade)
                        @php
                            $courseName = optional($grade->course)->course_name ?? 'Unnamed Course';
                            $courseCode = optional($grade->course)->course_code ?? 'Unnamed Code';
                            $gradeVal = $grade->grade;
                            $point = $grade->point ?? '-';
                            $observation = $grade->observation ?? '-';

                            $color = is_numeric($gradeVal) && floatval($gradeVal) < 70 ? 'red' : 'blue';
                        @endphp
                        <tr>
                            <td>{{ $courseName }}</td>
                            <td>{{ $courseCode }}</td>
                            <td style="color: {{ $color }};">{{ $gradeVal ?? '-' }}</td>
                            <td style="color: {{ is_numeric($point) && $point > 69.9 ? 'blue' : 'red' }};">{{ $point }}</td>
                            <td style="color: {{ $observation == 'Pass' ? 'blue' : 'red' }};">{{ $observation }}</td>
                        </tr>
                    @endforeach

                    {{-- Average row --}}
                    <tr class="fw-bold">
                        <td><strong>Average</strong></td>
                        <td></td>
                        <td></td>
                        <td colspan="2" style="color: {{ $avgColor }};"><strong>{{ $average ?? '-' }}</strong></td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @else
        <div class="alert alert-warning mt-4">No grades available for this academic year.</div>
    @endif





  <div class="footer">
    <!-- <div class="method-grade">
      <strong>METHOD OF GRADE:</strong><br/>
      A = Excellence (90-100), B = Good (80-89), C = Fair (70-79), F = Below 70 - Fail
    </div> -->

    <div class="signatures">
      <div><strong>Signed:</strong> _______________</div>
    </div>
  </div>



<script>
  window.print();
</script>
</body>
</html>
