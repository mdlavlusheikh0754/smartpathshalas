@php
    $schoolSettings = \App\Models\SchoolSetting::getSettings();
    $websiteSettings = \App\Models\WebsiteSetting::getSettings();

    if (!function_exists('en2bn')) {
        function en2bn($number) {
            $search_array = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "AM", "PM");
            $replace_array = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০", "এএম", "পিএম");
            return str_replace($search_array, $replace_array, $number);
        }
    }

    if (!function_exists('calculateGPA')) {
        function calculateGPA($percentage) {
            if ($percentage >= 80) return 5.00;
            elseif ($percentage >= 70) return 4.00;
            elseif ($percentage >= 60) return 3.50;
            elseif ($percentage >= 50) return 3.00;
            elseif ($percentage >= 40) return 2.00;
            elseif ($percentage >= 33) return 1.00;
            else return 0.00;
        }
    }
@endphp

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>একাডেমিক ট্রান্সক্রিপ্ট - {{ $student->name }}</title>
    <style>
        body {
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #000;
            direction: ltr;
            font-weight: normal;
        }
        
        * {
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
            font-weight: normal;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .header .logo {
            width: 80px;
            text-align: left;
        }
        
        .header .school-info {
            text-align: center;
        }
        
        .header .school-name {
            font-size: 55px !important;
            font-weight: bold;
            margin-bottom: 5px;
            color: #000;
            line-height: 1.2;
        }
        
        .header .school-address {
            font-size: 22px;
            font-weight: normal;
            margin-bottom: 8px;
            color: #333;
        }
        
        .header .exam-name {
            background: #f0f8ff;
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            display: inline-block;
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin-top: 10px;
        }
        
        .student-info {
            margin-bottom: 25px;
        }
        
        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .student-info td {
            padding: 6px 10px;
            border: none;
            font-weight: normal;
            white-space: nowrap;
            font-size: 19px;
        }
        
        .label {
            color: #444;
            font-weight: normal;
        }
        
        .value {
            font-weight: bold;
            color: #000;
        }
        
        .summary-cards {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        
        .summary-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 12px 8px;
            text-align: center;
            vertical-align: top;
            width: 20%;
        }
        
        .summary-card.total-marks {
            width: 20%;
        }
        
        .summary-card .card-label {
            font-size: 16px;
            color: #666;
            margin-bottom: 5px;
            font-weight: normal;
        }
        
        .summary-card .card-value {
            font-size: 24px;
            font-weight: bold;
            color: #000;
        }
        
        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .marks-table th,
        .marks-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
            font-size: 15px;
            font-weight: normal;
        }
        
        .marks-table th {
            background: #f0f0f0;
            font-weight: normal;
        }
        
        .marks-table .subject-name {
            text-align: left;
            font-weight: normal;
        }
        
        .pass {
            color: #16a34a;
            font-weight: normal;
        }
        
        .fail {
            color: #dc2626;
            font-weight: normal;
        }
        
        .monthly-average {
            background: #e0f2fe;
            font-weight: normal;
        }
        
        .signature {
            text-align: right;
            margin-top: 40px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            margin: 0 0 5px auto;
        }
    </style>
</head>
<body>
    <!-- School Header -->
    <div class="header">
        <table>
            <tr>
                <td class="logo">
                    @if(isset($logoPath) && file_exists($logoPath))
                        <img src="{{ $logoPath }}" alt="Logo" style="height: 60px; width: 60px; object-fit: contain;">
                    @endif
                </td>
                <td class="school-info">
                    <div class="school-name">{{ $schoolSettings->school_name_bn ?? $websiteSettings->school_name ?? tenant('id') }}</div>
                    <div class="school-address">{{ $schoolSettings->address ?? $websiteSettings->address ?? '' }}</div>
                    <div class="exam-name">
                        @php
                            $examTitle = $exam->name;
                            // Remove duplicate year if it appears twice
                            $examTitle = preg_replace('/(\d{4})\s*-\s*\1/', '$1', $examTitle);
                        @endphp
                        {{ $examTitle }}
                    </div>
                </td>
                <td class="logo"></td>
            </tr>
        </table>
    </div>

    <!-- Student Info -->
    <div class="student-info">
        <table>
            <tr>
                <td class="label">শিক্ষার্থীর নাম:</td>
                <td class="value">{{ $student->name_bn ?? $student->name }}</td>
                <td class="label">শ্রেণী:</td>
                <td class="value">{{ $class->name }}</td>
                <td class="label">সেকশন:</td>
                <td class="value">{{ $class->section }}</td>
                <td class="label">রোল নম্বর:</td>
                <td class="value">{{ en2bn($student->roll) }}</td>
            </tr>
            <tr>
                <td class="label">পিতার নাম:</td>
                <td class="value">{{ $student->father_name ?? '-' }}</td>
                <td class="label">মাতার নাম:</td>
                <td class="value">{{ $student->mother_name ?? '-' }}</td>
                <td class="label">স্টুডেন্ট আইডি:</td>
                <td class="value">{{ en2bn($student->student_id) }}</td>
                <td class="label">ফলাফল প্রকাশের তারিখ:</td>
                <td class="value">{{ en2bn(date('d/m/Y')) }}</td>
            </tr>
        </table>
    </div>

    <!-- Summary Cards -->
    <table class="summary-cards">
        <tr>
            <td class="summary-card total-marks">
                <div class="card-label">মোট নম্বর</div>
                <div class="card-value">
                    @php
                        $termTotal = $studentResult['total_marks'];
                        $monthlyTotal = $studentResult['monthly_marks'] ?? 0;
                        $finalTotal = $termTotal + $monthlyTotal;
                    @endphp
                    {{ en2bn(number_format($finalTotal, 2)) }} / {{ en2bn($studentResult['total_possible']) }}
                </div>
            </td>
            <td class="summary-card">
                <div class="card-label">GPA</div>
                <div class="card-value">{{ en2bn(number_format(calculateGPA($studentResult['percentage']), 2)) }}</div>
            </td>
            <td class="summary-card">
                <div class="card-label">গ্রেড</div>
                <div class="card-value">{{ $studentResult['overall_grade'] }}</div>
            </td>
            <td class="summary-card">
                <div class="card-label">ফলাফল</div>
                <div class="card-value">{{ $studentResult['overall_result'] }}</div>
            </td>
            <td class="summary-card">
                <div class="card-label">অবস্থান</div>
                <div class="card-value">{{ isset($rank) ? en2bn($rank) : '-' }}</div>
            </td>
        </tr>
    </table>

    <!-- Marks Table -->
    <table class="marks-table">
        <thead>
            <tr>
                <th style="width: 8%;">ক্রঃ নং</th>
                <th style="width: 35%;">বিষয়</th>
                <th style="width: 12%;">মোট নম্বর</th>
                <th style="width: 15%;">প্রাপ্ত নম্বর</th>
                <th style="width: 12%;">লেটার গ্রেড</th>
                <th style="width: 18%;">GPA Point</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studentResult['subjects'] as $index => $result)
                <tr>
                    <td>{{ en2bn($index + 1) }}</td>
                    <td class="subject-name">
                        {{ $result['subject_name'] }}
                        @if($result['is_group'] ?? false)
                            <span style="font-size: 9px; color: #1e40af;">(গ্রুপ)</span>
                        @endif
                    </td>
                    <td>{{ en2bn($result['total_marks']) }}</td>
                    <td class="{{ $result['status'] == 'fail' ? 'fail' : 'pass' }}">
                        {{ ($result['obtained_marks'] ?? 0) > 0 ? en2bn(number_format($result['obtained_marks'], 0)) : '-' }}
                    </td>
                    <td>{{ $result['grade'] ?? '-' }}</td>
                    <td>{{ isset($result['percentage']) ? en2bn(number_format(calculateGPA($result['percentage']), 2)) : '০.০০' }}</td>
                </tr>
            @endforeach
            @php
                $monthlyAverage = $studentResult['monthly_marks'] ?? 0;
            @endphp
            <tr class="monthly-average">
                <td colspan="3" style="text-align: right;">মাসিক পরীক্ষার গড় নম্বর:</td>
                <td colspan="3">{{ en2bn(number_format($monthlyAverage, 2)) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer Signature -->
    <div class="signature">
        <div class="signature-line"></div>
        <div>প্রধান শিক্ষকের স্বাক্ষর</div>
    </div>
</body>
</html>