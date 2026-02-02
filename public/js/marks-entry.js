let currentExam = null;
let currentSubject = null;
let students = [];
let examSubjects = [];
let marks = {};

document.addEventListener('DOMContentLoaded', function() {
    loadExams();
    loadClasses();
    document.getElementById('examSelect').addEventListener('change', loadExamData);
    document.getElementById('classSelect').addEventListener('change', loadStudents);
    document.getElementById('subjectSelect').addEventListener('change', renderMarksTable);
});

async function loadExams() {
    try {
        const response = await fetch('/marks/api/exams');
        const data = await response.json();
        const examSelect = document.getElementById('examSelect');
        examSelect.innerHTML = '<option value="">পরীক্ষা নির্বাচন করুন</option>';
        if (data.success && data.exams) {
            data.exams.forEach(exam => {
                const option = document.createElement('option');
                option.value = exam.id;
                option.textContent = exam.name;
                examSelect.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading exams:', error);
    }
}

async function loadClasses() {
    try {
        const response = await fetch('/marks/api/classes');
        const data = await response.json();
        const classSelect = document.getElementById('classSelect');
        classSelect.innerHTML = '<option value="">শ্রেণী নির্বাচন করুন</option>';
        if (data.success && data.classes) {
            data.classes.forEach(cls => {
                const option = document.createElement('option');
                option.value = cls.id;
                option.textContent = cls.name;
                classSelect.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading classes:', error);
    }
}

async function loadExamData() {
    const examId = document.getElementById('examSelect').value;
    if (!examId) {
        currentExam = null;
        document.getElementById('marksEntrySection').classList.add('hidden');
        return;
    }
    try {
        const response = await fetch(`/marks/api/exams/${examId}`);
        const data = await response.json();
        if (data.success && data.exam) {
            currentExam = data.exam;
            examSubjects = data.exam.subjects || [];
            const subjectSelect = document.getElementById('subjectSelect');
            subjectSelect.innerHTML = '<option value="">সকল বিষয়</option>';
            examSubjects.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.name;
                subjectSelect.appendChild(option);
            });
            const classId = document.getElementById('classSelect').value;
            if (classId) await loadStudents();
        }
    } catch (error) {
        console.error('Error loading exam data:', error);
    }
}

async function loadStudents() {
    const classId = document.getElementById('classSelect').value;
    const examId = document.getElementById('examSelect').value;
    if (!classId || !examId) {
        students = [];
        document.getElementById('marksEntrySection').classList.add('hidden');
        return;
    }
    try {
        const response = await fetch(`/marks/api/students/${classId}`);
        const data = await response.json();
        if (data.success && data.students) {
            students = data.students;
            await loadAllMarks();
            renderMarksTable();
            document.getElementById('marksEntrySection').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error loading students:', error);
    }
}

async function loadAllMarks() {
    if (!currentExam || !examSubjects.length) return;
    const classId = document.getElementById('classSelect').value;
    try {
        for (const subject of examSubjects) {
            const response = await fetch(`/marks/api/marks?exam_id=${currentExam.id}&subject_id=${subject.id}&class_id=${classId}`);
            const data = await response.json();
            if (data.success && data.marks) {
                Object.keys(data.marks).forEach(studentId => {
                    const result = data.marks[studentId];
                    const studentKey = `${currentExam.id}_${subject.id}_${result.student_id}`;
                    marks[studentKey] = {
                        present: result.status !== 'absent',
                        marks: result.obtained_marks || '',
                        grade: result.grade || '',
                        result: result.status === 'pass' ? 'পাস' : (result.status === 'fail' ? 'ফেল' : 'অনুপস্থিত')
                    };
                });
            }
        }
    } catch (error) {
        console.error('Error loading marks:', error);
    }
}

function renderMarksTable() {
    if (!currentExam || !examSubjects.length) return;
    const tableHeaderRow = document.getElementById('tableHeaderRow');
    const tableBody = document.getElementById('marksTableBody');
    
    while (tableHeaderRow.children.length > 6) {
        tableHeaderRow.removeChild(tableHeaderRow.children[3]);
    }
    
    const totalColumn = tableHeaderRow.children[3];
    examSubjects.forEach(subject => {
        const th = document.createElement('th');
        th.className = 'px-3 py-3 text-center text-sm font-bold text-gray-700 bg-blue-50 border-x border-gray-200';
        th.style.minWidth = '120px';
        th.innerHTML = `<div class="font-bold">${subject.name}</div><div class="text-xs text-gray-600">${toBengaliNumber(subject.total_marks)}</div>`;
        tableHeaderRow.insertBefore(th, totalColumn);
    });
    
    tableBody.innerHTML = '';
    
    students.forEach((student, index) => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-blue-50';
        
        let rowHtml = `
            <td class="px-4 py-3 text-center text-sm font-bold">${student.student_id || toBengaliNumber(index + 1)}</td>
            <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                    <img src="${student.photo || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(student.name) + '&background=3b82f6&color=fff&size=128'}" 
                         alt="${student.name}" 
                         class="w-12 h-12 rounded-full object-cover border-2 border-blue-500"
                         onerror="this.src='https://ui-avatars.com/api/?name=' + encodeURIComponent('${student.name}') + '&background=3b82f6&color=fff&size=128'">
                    <div>
                        <div class="text-sm font-bold">${student.name}</div>
                        <div class="text-xs text-gray-500">${student.registration_number || 'N/A'}</div>
                    </div>
                </div>
            </td>
            <td class="px-4 py-3 text-center font-semibold">${toBengaliNumber(student.roll_number)}</td>
        `;
        
        let totalMarks = 0;
        let totalPossible = 0;
        let subjectsWithMarks = 0;
        let failedSubjects = 0;
        
        examSubjects.forEach(subject => {
            const studentKey = `${currentExam.id}_${subject.id}_${student.id}`;
            const studentMark = marks[studentKey] || { present: true, marks: '', grade: '', result: '' };
            
            let displayText = '-';
            let bgColor = 'bg-white';
            let textColor = 'text-gray-400';
            
            if (!studentMark.present) {
                displayText = 'অনুপস্থিত';
                bgColor = 'bg-red-50';
                textColor = 'text-red-600 text-xs';
            } else if (studentMark.marks !== '' && studentMark.marks !== null) {
                const marksValue = parseFloat(studentMark.marks);
                displayText = toBengaliNumber(marksValue);
                totalMarks += marksValue;
                totalPossible += subject.total_marks;
                subjectsWithMarks++;
                
                const percentage = (marksValue / subject.total_marks) * 100;
                const passPercentage = (subject.pass_marks / subject.total_marks) * 100;
                
                if (percentage >= passPercentage) {
                    bgColor = 'bg-green-50';
                    textColor = 'text-green-700 font-semibold';
                } else {
                    bgColor = 'bg-red-50';
                    textColor = 'text-red-700 font-semibold';
                    failedSubjects++;
                }
            } else {
                totalPossible += subject.total_marks;
            }
            
            rowHtml += `<td class="px-3 py-3 text-center ${bgColor} cursor-pointer hover:bg-blue-100" onclick="openMarksModal(${student.id})"><div class="${textColor} text-sm">${displayText}</div></td>`;
        });
        
        let overallGrade = '';
        let overallResult = '';
        let gradeColor = 'bg-gray-100 text-gray-600';
        let resultColor = 'text-gray-500';
        
        if (subjectsWithMarks === examSubjects.length && totalPossible > 0) {
            const percentage = (totalMarks / totalPossible) * 100;
            if (percentage >= 80) { overallGrade = 'A+'; gradeColor = 'bg-green-100 text-green-800'; }
            else if (percentage >= 70) { overallGrade = 'A'; gradeColor = 'bg-green-100 text-green-700'; }
            else if (percentage >= 60) { overallGrade = 'A-'; gradeColor = 'bg-blue-100 text-blue-700'; }
            else if (percentage >= 50) { overallGrade = 'B'; gradeColor = 'bg-blue-100 text-blue-600'; }
            else if (percentage >= 40) { overallGrade = 'C'; gradeColor = 'bg-yellow-100 text-yellow-700'; }
            else if (percentage >= 33) { overallGrade = 'D'; gradeColor = 'bg-orange-100 text-orange-700'; }
            else { overallGrade = 'F'; gradeColor = 'bg-red-100 text-red-700'; }
            
            if (failedSubjects === 0 && percentage >= 33) {
                overallResult = 'পাস';
                resultColor = 'text-green-700 font-bold';
            } else {
                overallResult = 'ফেল';
                resultColor = 'text-red-700 font-bold';
            }
        }
        
        rowHtml += `
            <td class="px-4 py-3 text-center bg-gray-50"><div class="font-bold">${subjectsWithMarks === examSubjects.length ? toBengaliNumber(totalMarks) : '-'}</div></td>
            <td class="px-4 py-3 text-center bg-gray-50"><span class="px-3 py-1 text-sm font-bold ${gradeColor} rounded-full">${overallGrade || '-'}</span></td>
            <td class="px-4 py-3 text-center bg-gray-50"><span class="text-sm ${resultColor}">${overallResult || '-'}</span></td>
        `;
        
        row.innerHTML = rowHtml;
        tableBody.appendChild(row);
    });
}

function openMarksModal(studentId) {
    const student = students.find(s => s.id === studentId);
    if (!student) return;
    
    document.getElementById('modalStudentName').textContent = student.name;
    document.getElementById('modalStudentId').textContent = student.student_id || '-';
    document.getElementById('modalStudentRoll').textContent = toBengaliNumber(student.roll_number);
    document.getElementById('modalStudentPhoto').src = student.photo || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(student.name) + '&background=3b82f6&color=fff&size=128';
    
    const container = document.getElementById('modalSubjectsContainer');
    container.innerHTML = '';
    container.dataset.studentId = studentId;
    
    examSubjects.forEach(subject => {
        const studentKey = `${currentExam.id}_${subject.id}_${studentId}`;
        const studentMark = marks[studentKey] || { present: true, marks: '', grade: '', result: '' };
        
        const subjectDiv = document.createElement('div');
        subjectDiv.className = 'p-4 bg-gray-50 rounded-xl';
        subjectDiv.innerHTML = `
            <div class="flex justify-between mb-3">
                <h5 class="font-bold">${subject.name}</h5>
                <span class="text-sm text-gray-600">পূর্ণমান: ${toBengaliNumber(subject.total_marks)}</span>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <label class="flex items-center gap-2">
                    <input type="checkbox" ${studentMark.present ? 'checked' : ''} onchange="updateModalAttendance(${studentId}, ${subject.id}, this.checked)" class="form-checkbox h-5 w-5 text-blue-600">
                    <span class="text-sm font-semibold">${studentMark.present ? 'উপস্থিত' : 'অনুপস্থিত'}</span>
                </label>
                <input type="number" id="modal_marks_${subject.id}" value="${studentMark.marks}" min="0" max="${subject.total_marks}" ${!studentMark.present ? 'disabled' : ''} onchange="updateModalMarks(${studentId}, ${subject.id}, this.value)" class="px-3 py-2 border rounded-lg" placeholder="নম্বর">
            </div>
            <div class="mt-3 flex justify-between text-sm">
                <span>গ্রেড: <b id="modal_grade_${subject.id}">${studentMark.grade || '-'}</b></span>
                <span>ফলাফল: <b id="modal_result_${subject.id}">${studentMark.result || '-'}</b></span>
            </div>
        `;
        container.appendChild(subjectDiv);
    });
    
    document.getElementById('marksEntryModal').classList.remove('hidden');
}

function closeMarksModal() {
    document.getElementById('marksEntryModal').classList.add('hidden');
}

function updateModalAttendance(studentId, subjectId, isPresent) {
    const studentKey = `${currentExam.id}_${subjectId}_${studentId}`;
    if (!marks[studentKey]) marks[studentKey] = { present: true, marks: '', grade: '', result: '' };
    marks[studentKey].present = isPresent;
    if (!isPresent) {
        marks[studentKey].marks = '';
        marks[studentKey].grade = '';
        marks[studentKey].result = 'অনুপস্থিত';
        document.getElementById(`modal_marks_${subjectId}`).value = '';
        document.getElementById(`modal_marks_${subjectId}`).disabled = true;
    } else {
        marks[studentKey].result = '';
        document.getElementById(`modal_marks_${subjectId}`).disabled = false;
    }
    document.getElementById(`modal_grade_${subjectId}`).textContent = marks[studentKey].grade || '-';
    document.getElementById(`modal_result_${subjectId}`).textContent = marks[studentKey].result || '-';
}

function updateModalMarks(studentId, subjectId, marksValue) {
    const studentKey = `${currentExam.id}_${subjectId}_${studentId}`;
    if (!marks[studentKey]) marks[studentKey] = { present: true, marks: '', grade: '', result: '' };
    marks[studentKey].marks = marksValue;
    const subject = examSubjects.find(s => s.id == subjectId);
    if (marksValue && subject) {
        const numMarks = parseFloat(marksValue);
        const percentage = (numMarks / subject.total_marks) * 100;
        let grade = '';
        if (percentage >= 80) grade = 'A+';
        else if (percentage >= 70) grade = 'A';
        else if (percentage >= 60) grade = 'A-';
        else if (percentage >= 50) grade = 'B';
        else if (percentage >= 40) grade = 'C';
        else if (percentage >= 33) grade = 'D';
        else grade = 'F';
        marks[studentKey].grade = grade;
        marks[studentKey].result = numMarks >= subject.pass_marks ? 'পাস' : 'ফেল';
    } else {
        marks[studentKey].grade = '';
        marks[studentKey].result = '';
    }
    document.getElementById(`modal_grade_${subjectId}`).textContent = marks[studentKey].grade || '-';
    document.getElementById(`modal_result_${subjectId}`).textContent = marks[studentKey].result || '-';
}

function saveMarksFromModal() {
    renderMarksTable();
    closeMarksModal();
    alert('মার্ক সফলভাবে আপডেট করা হয়েছে');
}

async function saveAllMarks() {
    if (!currentExam || !examSubjects.length || !students.length) {
        alert('প্রথমে পরীক্ষা, ক্লাস এবং ছাত্র/ছাত্রী নির্বাচন করুন');
        return;
    }
    const classId = document.getElementById('classSelect').value;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let successCount = 0;
        for (const subject of examSubjects) {
            const marksData = [];
            students.forEach(student => {
                const studentKey = `${currentExam.id}_${subject.id}_${student.id}`;
                const studentMark = marks[studentKey];
                if (studentMark && (studentMark.marks !== '' || !studentMark.present)) {
                    marksData.push({
                        student_id: student.id,
                        obtained_marks: studentMark.present ? studentMark.marks : null,
                        grade: studentMark.grade || null,
                        status: !studentMark.present ? 'absent' : (studentMark.result === 'পাস' ? 'pass' : 'fail')
                    });
                }
            });
            if (marksData.length > 0) {
                const response = await fetch('/marks/api/marks', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ exam_id: currentExam.id, subject_id: subject.id, class_id: classId, marks: marksData })
                });
                const data = await response.json();
                if (data.success) successCount++;
            }
        }
        if (successCount > 0) {
            alert(`${toBengaliNumber(successCount)} টি বিষয়ের মার্ক সংরক্ষণ করা হয়েছে`);
            await loadAllMarks();
            renderMarksTable();
        }
    } catch (error) {
        console.error('Error saving marks:', error);
        alert('মার্ক সংরক্ষণ করতে সমস্যা হয়েছে');
    }
}

function toBengaliNumber(num) {
    if (num === null || num === undefined) return '০';
    const banglaDigits = {'0':'০','1':'১','2':'২','3':'৩','4':'৪','5':'৫','6':'৬','7':'৭','8':'৮','9':'৯'};
    return num.toString().replace(/\d/g, d => banglaDigits[d]);
}
