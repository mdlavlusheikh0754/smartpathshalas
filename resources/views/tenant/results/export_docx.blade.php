<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>পরীক্ষার ফলাফল - {{ $exam->name }} - {{ $class->name }} {{ $class->section }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .school-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #1e40af;
        }
        
        .school-address {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .exam-info {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        
        .class-info {
            font-size: 16px;
            color: #666;
        }
        
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
        }
        
        .results-table th,
        .results-table td {
            border: 1px solid #333;
            padding: 8px 4px;
            text-align: center;
            vertical-align: middle;
        }
        
        .results-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
        }
        
        .student-info {
            text-align: left;
            padding-left: 8px;
        }
        
        .student-name {
            font-weight: bold;
            font-size: 11px;
        }
        
        .student-reg {
            font-size: 9px;
            color: #666;
        }
        
        .group-subject {
            background-color: #f3e8ff;
            color: #7c3aed;
            font-weight: bold;
        }
        
        .pass-mark {
            background-color: #dcfce7;
            color: #166534;
            font-weight: bold;
        }
        
        .fail-mark {
            background-color: #fecaca;
            color: #dc2626;
            font-weight: bold;
        }
        
        .absent-mark {
            background-color: #f3f4f6;
            color: #6b7280;
            font-size: 9px;
        }
        
        .total-column {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .result-pass {
            color: #166534;
            font-weight: bold;
        }
        
        .result-fail {
            color: #dc2626;
            font-weight: bold;
        }
        
        .summary {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border: 1px solid #ddd;
        }
        
        .summary-item {
            display: inline-block;
            margin-right: 30px;
            text-align: center;
        }
        
        .summary-number {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }
        
        .summary-label {
            font-size: 12px;
            color: #666;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ $schoolInfo['name'] }}</div>
        @if($schoolInfo['address'])
            <div class="school-address">{{ $schoolInfo['address'] }}</div>
        @endif
        <div class="exam-info">পরীক্ষার ফলাফল - {{ $exam->name }}</div>
        <div class="class-info">শ্রেণী: {{ $class->name }} - {{ $class->section }}</div>
    </div>

    @php
        $totalStudents = count($results);
        $passedStudents = collect($results)->where('overall_result', 'পাস')->count();
        $failedStudents = $totalStudents - $passedStudents;
        $totalPercentage = collect($results)->where('percentage', '>', 0)->avg('percentage');
    @endphp

    <div class="summary">
        <div class="summary-item">
            <div class="summary-number">{{ $totalStudents }}</div>
            <div class="summary-label">মোট ছাত্র/ছাত্রী</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $passedStudents }}</div>
            <div class="summary-label">পাস</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $failedStudents }}</div>
            <div class="summary-label">ফেল</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ number_format($totalPercentage, 1) }}%</div>
            <div class="summary-label">গড় শতাংশ</div>
        </div>
    </div>

    <table class="results-table">
        <thead>
            <tr>
                <th>রোল</th>
                <th>ছাত্র/ছাত্রী</th>
                @foreach($subjects as $subject)
                    <th class="{{ $subject['is_group'] ? 'group-subject' : '' }}">
                        {{ $subject['name'] }}
                        @if($subject['is_group'])
                            (গ্রুপ)
                        @endif
                        ({{ $subject['total_marks'] }})
                    </th>
                @endforeach
                <th class="total-column">মোট</th>
                <th class="total-column">শতাংশ</th>
                <th class="total-column">গ্রেড</th>
                <th class="total-column">ফলাফল</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td>{{ $result['student']->roll_number }}</td>
                    <td class="student-info">
                        <div class="student-name">{{ $result['student']->name }}</div>
                        <div class="student-reg">{{ $result['student']->registration_number ?? 'N/A' }}</div>
                    </td>
                    
                    @foreach($result['subjects'] as $subjectResult)
                        <td class="
                            @if(!$subjectResult['is_present']) absent-mark
                            @elseif($subjectResult['status'] === 'pass') pass-mark
                            @elseif($subjectResult['status'] === 'fail') fail-mark
                            @endif
                        ">
                            @if(!$subjectResult['is_present'])
                                অনুপস্থিত
                            @elseif($subjectResult['obtained_marks'] !== null)
                                {{ $subjectResult['obtained_marks'] }}
                                @if($subjectResult['grade'])
                                    ({{ $subjectResult['grade'] }})
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                    
                    <td class="total-column">
                        {{ $result['total_marks'] > 0 ? $result['total_marks'] : '-' }}
                        @if($result['total_possible'] > 0)
                            /{{ $result['total_possible'] }}
                        @endif
                    </td>
                    <td class="total-column">
                        {{ $result['percentage'] > 0 ? number_format($result['percentage'], 1) . '%' : '-' }}
                    </td>
                    <td class="total-column">
                        {{ $result['overall_grade'] ?? '-' }}
                    </td>
                    <td class="total-column {{ $result['overall_result'] === 'পাস' ? 'result-pass' : 'result-fail' }}">
                        {{ $result['overall_result'] ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>প্রিন্ট তারিখ: {{ date('d/m/Y H:i:s') }}</p>
        <p>Smart Pathshala - School Management System</p>
    </div>
</body>
</html>