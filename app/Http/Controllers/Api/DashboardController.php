<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Homework;
use App\Models\Notice;
use App\Models\FeeStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseApiController
{
    /**
     * Get dashboard data
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get basic stats
        $stats = $this->getStats();
        
        // Get recent activities
        $recentHomework = Homework::where('status', 'active')
            ->orderBy('assigned_date', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($homework) {
                return [
                    'id' => $homework->id,
                    'title' => $homework->title,
                    'subject' => $homework->subject,
                    'class' => $homework->class,
                    'section' => $homework->section,
                    'assigned_date' => $homework->assigned_date?->format('Y-m-d'),
                    'due_date' => $homework->due_date?->format('Y-m-d'),
                    'status' => $homework->status,
                    'is_overdue' => $homework->due_date && $homework->due_date->isPast(),
                ];
            });

        $recentNotices = Notice::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($notice) {
                return [
                    'id' => $notice->id,
                    'title' => $notice->title,
                    'content' => substr($notice->content, 0, 100) . '...',
                    'priority' => $notice->priority ?? 'normal',
                    'created_at' => $notice->created_at->format('Y-m-d H:i'),
                ];
            });

        // Get upcoming events/deadlines
        $upcomingHomework = Homework::where('status', 'active')
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addDays(7))
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($homework) {
                return [
                    'id' => $homework->id,
                    'title' => $homework->title,
                    'subject' => $homework->subject,
                    'class' => $homework->class,
                    'due_date' => $homework->due_date?->format('Y-m-d'),
                    'days_remaining' => now()->diffInDays($homework->due_date, false),
                ];
            });

        // Get class-wise student distribution
        $classDistribution = Student::select('class', DB::raw('count(*) as count'))
            ->where('status', 'active')
            ->groupBy('class')
            ->orderBy('class')
            ->get()
            ->map(function ($item) {
                return [
                    'class' => $item->class,
                    'count' => $item->count,
                ];
            });

        // Get monthly homework statistics
        $homeworkStats = Homework::select(
                DB::raw('MONTH(assigned_date) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('assigned_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => date('M', mktime(0, 0, 0, $item->month, 1)),
                    'count' => $item->count,
                ];
            });

        $dashboardData = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'user',
            ],
            'stats' => $stats,
            'recent_homework' => $recentHomework,
            'recent_notices' => $recentNotices,
            'upcoming_homework' => $upcomingHomework,
            'class_distribution' => $classDistribution,
            'homework_stats' => $homeworkStats,
            'quick_actions' => [
                [
                    'title' => 'Add Homework',
                    'icon' => 'book-open',
                    'action' => 'add_homework',
                ],
                [
                    'title' => 'Take Attendance',
                    'icon' => 'check-circle',
                    'action' => 'take_attendance',
                ],
                [
                    'title' => 'Add Student',
                    'icon' => 'user-plus',
                    'action' => 'add_student',
                ],
                [
                    'title' => 'Create Notice',
                    'icon' => 'bell',
                    'action' => 'create_notice',
                ],
            ],
        ];

        return $this->sendResponse($dashboardData, 'Dashboard data retrieved successfully');
    }

    /**
     * Get dashboard statistics
     */
    public function getStats()
    {
        $totalStudents = Student::where('status', 'active')->count();
        $totalTeachers = Teacher::where('status', 'active')->count();
        $totalHomework = Homework::where('status', 'active')->count();
        $totalNotices = Notice::where('status', 'active')->count();

        // Get pending homework (due within 3 days)
        $pendingHomework = Homework::where('status', 'active')
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addDays(3))
            ->count();

        // Get overdue homework
        $overdueHomework = Homework::where('status', 'active')
            ->where('due_date', '<', now())
            ->count();

        // Get today's attendance rate - TODO: Calculate from actual attendance data
        $attendanceRate = 0; // This would be calculated from actual attendance data

        // Get fee collection rate - TODO: Calculate from actual fee data
        $feeCollectionRate = 0; // This would be calculated from actual fee data

        $stats = [
            'total_students' => $totalStudents,
            'total_teachers' => $totalTeachers,
            'total_homework' => $totalHomework,
            'total_notices' => $totalNotices,
            'pending_homework' => $pendingHomework,
            'overdue_homework' => $overdueHomework,
            'attendance_rate' => $attendanceRate,
            'fee_collection_rate' => $feeCollectionRate,
            'active_classes' => Student::distinct('class')->count('class'),
            'this_month_homework' => Homework::whereMonth('assigned_date', now()->month)
                ->whereYear('assigned_date', now()->year)
                ->count(),
        ];

        return $stats;
    }
}