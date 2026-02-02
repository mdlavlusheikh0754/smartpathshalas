<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LibraryController extends Controller
{
    public function index()
    {
        $stats = [
            'total_books' => Book::sum('total_quantity'),
            'issued_books' => BookIssue::where('status', 'issued')->count(),
            'overdue_books' => BookIssue::where('status', 'issued')
                ->where('due_date', '<', now())
                ->count(),
            'available_books' => Book::sum('available_quantity'),
        ];

        return view('tenant.library.index', compact('stats'));
    }

    public function books(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%')
                  ->orWhere('isbn', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $books = $query->latest()->paginate(15);
        $categories = Book::distinct()->pluck('category')->filter();

        return view('tenant.library.books', compact('books', 'categories'));
    }

    public function storeBook(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'category' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'total_quantity' => 'required|integer|min:1',
            'shelf_location' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
        ]);

        $validated['available_quantity'] = $validated['total_quantity'];

        Book::create($validated);

        return redirect()->back()->with('success', 'বইটি সফলভাবে যোগ করা হয়েছে।');
    }

    public function updateBook(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'category' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'total_quantity' => 'required|integer|min:0',
            'shelf_location' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
        ]);

        // Adjust available quantity
        $diff = $validated['total_quantity'] - $book->total_quantity;
        $validated['available_quantity'] = max(0, $book->available_quantity + $diff);

        $book->update($validated);

        return redirect()->back()->with('success', 'বইটির তথ্য আপডেট করা হয়েছে।');
    }

    public function deleteBook(Book $book)
    {
        if ($book->issues()->where('status', 'issued')->exists()) {
            return redirect()->back()->with('error', 'এই বইটি বর্তমানে ইস্যু করা আছে, তাই মুছে ফেলা সম্ভব নয়।');
        }

        $book->delete();
        return redirect()->back()->with('success', 'বইটি মুছে ফেলা হয়েছে।');
    }

    public function issue()
    {
        $issues = BookIssue::with(['book', 'student'])->latest()->paginate(15);
        $availableBooks = Book::where('available_quantity', '>', 0)->get();
        $students = Student::orderBy('name_en')->get();

        return view('tenant.library.issue', compact('issues', 'availableBooks', 'students'));
    }

    public function storeIssue(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'student_id' => 'required|exists:students,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'notes' => 'nullable|string',
        ]);

        $book = Book::find($validated['book_id']);

        if ($book->available_quantity <= 0) {
            return redirect()->back()->with('error', 'এই বইটি বর্তমানে উপলব্ধ নয়।');
        }

        BookIssue::create($validated);

        $book->decrement('available_quantity');

        return redirect()->back()->with('success', 'বইটি সফলভাবে ইস্যু করা হয়েছে।');
    }

    public function return()
    {
        $issuedItems = BookIssue::with(['book', 'student'])
            ->where('status', 'issued')
            ->latest()
            ->paginate(15);

        return view('tenant.library.return', compact('issuedItems'));
    }

    public function processReturn(Request $request, BookIssue $issue)
    {
        $validated = $request->validate([
            'return_date' => 'required|date',
            'fine_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:returned,lost',
            'notes' => 'nullable|string',
        ]);

        $issue->update([
            'return_date' => $validated['return_date'],
            'fine_amount' => $validated['fine_amount'] ?? 0,
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);

        if ($validated['status'] === 'returned') {
            $issue->book->increment('available_quantity');
        }

        return redirect()->back()->with('success', 'বইটি সফলভাবে ফেরত নেওয়া হয়েছে।');
    }
}
