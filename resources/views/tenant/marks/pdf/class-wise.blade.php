<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Marks Backup - {{ $className }}</title>
    <style>
        body {
            font-family: 'kalpurush', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1, h2 {
            text-align: center;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $schoolName }}</h1>
        <h2>Class: {{ $className }}</h2>
        <p>Generated on: {{ date('d/m/Y H:i') }}</p>
    </div>

    @foreach($exams as $exam)
        <h3>Exam: {{ $exam->name }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    @foreach($exam->subjects as $subject)
                        <th>{{ $subject->name }}</th>
                    @endforeach
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exam->students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->name }}</td>
                    @foreach($exam->subjects as $subject)
                        @php
                            $mark = $student->marks->where('subject_id', $subject->id)->first();
                        @endphp
                        <td>
                            @if($mark)
                                {{ $mark->obtained_marks ?? 'Abs' }}
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                    <td>{{ $student->total_marks }}</td>
                    <td>{{ $student->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
