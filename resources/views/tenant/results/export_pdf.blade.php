<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>পরীক্ষার ফলাফল - {{ $exam->name }} - {{ $class->name }} {{ $class->section }}</title>
    <style>
        body {
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            margin: 0;
            padding: 15px;
            color: #333;
            direction: ltr;
            font-weight: normal;
        }
        
        .header {
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            position: relative;
        }
        
        .header-content {
            display: table;
            width: 100%;
        }
        
        .logo-section {
            display: table-cell;
            width: 100px;
            vertical-align: middle;
            text-align: left;
        }
        
        .school-info-section {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding-left: 20px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            display: block;
        }
        
        .school-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #1e40af;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
        }
        
        .school-address {
            font-size: 14px;
            color: #666;
            margin-bottom: 12px;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
            font-weight: normal;
        }
        
        .exam-info {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
        }
        
        .class-info {
            font-size: 16px;
            color: #666;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
            font-weight: normal;
        }
        
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 9px;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
        }
        
        .results-table th,
        .results-table td {
            border: 1px solid #333;
            padding: 6px 3px;
            text-align: center;
            vertical-align: middle;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
            font-weight: normal;
        }
        
        .results-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 10px;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
        }
        
        .student-info {
            text-align: left;
            padding-left: 6px;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
        }
        
        .student-name {
            font-weight: normal;
            font-size: 10px;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
        }
        
        .student-reg {
            font-size: 8px;
            color: #666;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
            font-weight: normal;
        }
        
        .subject-header {
            min-width: 20px;
            max-width: 30px;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
        }
        
        .group-subject {
            background-color: #f3e8ff;
            color: #7c3aed;
            font-weight: bold;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
        }
        
        .pass-mark {
            background-color: #dcfce7;
            color: #166534;
            font-weight: normal;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
        }
        
        .fail-mark {
            background-color: #fecaca;
            color: #dc2626;
            font-weight: normal;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
        }
        
        .absent-mark {
            background-color: #f3f4f6;
            color: #6b7280;
            font-size: 8px;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
            font-weight: normal;
        }
        
        .total-column {
            background-color: #f8f9fa;
            font-weight: normal;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
        }
        
        .grade-a-plus {
            background-color: #dcfce7;
            color: #166534;
            font-weight: normal;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
        }
        
        .grade-fail {
            background-color: #fecaca;
            color: #dc2626;
            font-weight: normal;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
        }
        
        .result-pass {
            color: #166534;
            font-weight: normal;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
        }
        
        .result-fail {
            color: #dc2626;
            font-weight: normal;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
            font-weight: normal;
        }
        
        .summary {
            margin-top: 15px;
            background-color: #f8f9fa;
            padding: 12px;
            border: 1px solid #ddd;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
        }
        
        .summary-row {
            display: table;
            width: 100%;
        }
        
        .summary-item {
            display: table-cell;
            text-align: center;
            width: 25%;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
            padding: 0 10px;
        }
        
        .summary-number {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
            margin-bottom: 3px;
        }
        
        .summary-label {
            font-size: 10px;
            color: #666;
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif;
            font-weight: normal;
        }
        
        .bengali-text {
            font-family: 'solaimanlipi', 'dejavusans', Arial, sans-serif !important;
            font-weight: normal;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="logo-section">
                @if($schoolInfo['logo'])
                    <img src="{{ $schoolInfo['logo'] }}" alt="School Logo" class="logo">
                @endif
            </div>
            <div class="school-info-section">
                <div class="school-name bengali-text">{{ $schoolInfo['name'] }}</div>
                @if($schoolInfo['address'])
                    <div class="school-address bengali-text">{{ $schoolInfo['address'] }}</div>
                @endif
                <div class="exam-info bengali-text">পরীক্ষার ফলাফল - {{ $exam->name }}</div>
                <div class="class-info bengali-text">শ্রেণী: {{ $class->name }} - {{ $class->section }}</div>
            </div>
        </div>
    </div>

    @php
        $totalStudents = count($results);
        $passedStudents = collect($results)->where('overall_result', 'পাস')->count();
        $failedStudents = $totalStudents - $passedStudents;
        $totalPercentage = collect($results)->where('percentage', '>', 0)->avg('percentage');
    @endphp

    <div class="summary">
        <div class="summary-row">
            <div class="summary-item">
                <div class="summary-number bengali-text">{{ $totalStudents }}</div>
                <div class="summary-label bengali-text">মোট ছাত্র/ছাত্রী</div>
            </div>
            <div class="summary-item">
                <div class="summary-number bengali-text">{{ $passedStudents }}</div>
                <div class="summary-label bengali-text">পাস</div>
            </div>
            <div class="summary-item">
                <div class="summary-number bengali-text">{{ $failedStudents }}</div>
                <div class="summary-label bengali-text">ফেল</div>
            </div>
            <div class="summary-item">
                <div class="summary-number bengali-text">{{ number_format($totalPercentage, 1) }}%</div>
                <div class="summary-label bengali-text">গড় শতাংশ</div>
            </div>
        </div>
    </div>

    <table class="results-table">
        <thead>
            <tr>
                <th class="bengali-text">রোল</th>
                <th class="bengali-text">ছাত্র/ছাত্রী</th>
                @foreach($subjects as $subject)
                    <th class="subject-header bengali-text {{ $subject['is_group'] ? 'group-subject' : '' }}">
                        {{ $subject['name'] }}
                        @if($subject['is_group'])
                            <br><small class="bengali-text">(গ্রুপ)</small>
                        @endif
                        <br><small class="bengali-text">({{ $subject['total_marks'] }})</small>
                    </th>
                @endforeach
                <th class="total-column bengali-text">মোট</th>
                <th class="total-column bengali-text">শতাংশ</th>
                <th class="total-column bengali-text">গ্রেড</th>
                <th class="total-column bengali-text">ফলাফল</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td class="bengali-text">{{ $result['student']->roll_number }}</td>
                    <td class="student-info">
                        <div class="student-name bengali-text">{{ $result['student']->name }}</div>
                        <div class="student-reg bengali-text">{{ $result['student']->registration_number ?? 'N/A' }}</div>
                    </td>
                    
                    @foreach($result['subjects'] as $subjectResult)
                        <td class="bengali-text
                            @if(!$subjectResult['is_present']) absent-mark
                            @elseif($subjectResult['status'] === 'pass') pass-mark
                            @elseif($subjectResult['status'] === 'fail') fail-mark
                            @endif
                        ">
                            @if(!$subjectResult['is_present'])
                                <span class="bengali-text">অনুপস্থিত</span>
                            @elseif($subjectResult['obtained_marks'] !== null)
                                <span class="bengali-text">{{ $subjectResult['obtained_marks'] }}</span>
                                @if($subjectResult['grade'])
                                    <br><small class="bengali-text">{{ $subjectResult['grade'] }}</small>
                                @endif
                            @else
                                <span class="bengali-text">-</span>
                            @endif
                        </td>
                    @endforeach
                    
                    <td class="total-column bengali-text">
                        {{ $result['total_marks'] > 0 ? $result['total_marks'] : '-' }}
                        @if($result['total_possible'] > 0)
                            <br><small class="bengali-text">{{ $result['total_possible'] }}</small>
                        @endif
                    </td>
                    <td class="total-column bengali-text">
                        {{ $result['percentage'] > 0 ? number_format($result['percentage'], 1) . '%' : '-' }}
                    </td>
                    <td class="total-column bengali-text {{ $result['overall_grade'] === 'A+' ? 'grade-a-plus' : ($result['overall_grade'] === 'ফেল' ? 'grade-fail' : '') }}">
                        {{ $result['overall_grade'] ?? '-' }}
                    </td>
                    <td class="total-column bengali-text {{ $result['overall_result'] === 'পাস' ? 'result-pass' : 'result-fail' }}">
                        {{ $result['overall_result'] ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p class="bengali-text">প্রিন্ট তারিখ: {{ date('d/m/Y H:i:s') }}</p>
        <p class="bengali-text">Smart Pathshala - School Management System</p>
    </div>
</body>
</html>