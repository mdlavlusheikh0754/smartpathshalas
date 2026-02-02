<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: 'solaimanlipi', sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
            font-family: 'solaimanlipi', sans-serif;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            font-family: 'solaimanlipi', sans-serif;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>শিক্ষার্থী তালিকা</h1>
        <p>তারিখ: {{ date('d M, Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>আইডি</th>
                <th>নাম</th>
                <th>রোল</th>
                <th>শ্রেণী</th>
                <th>শাখা</th>
                <th>লিঙ্গ</th>
                <th>মোবাইল</th>
                <th>পিতার নাম</th>
                <th>মাতার নাম</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->student_id }}</td>
                <td>{{ $student->name_bn ?? $student->name }}</td>
                <td>{{ $student->roll_number ?? $student->roll }}</td>
                <td>{{ $student->class }}</td>
                <td>{{ $student->section }}</td>
                <td>{{ $student->gender == 'male' ? 'ছেলে' : ($student->gender == 'female' ? 'মেয়ে' : $student->gender) }}</td>
                <td>{{ $student->phone }}</td>
                <td>{{ $student->father_name }}</td>
                <td>{{ $student->mother_name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
