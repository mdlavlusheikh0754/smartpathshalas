<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::active()->ordered()->get();
        return view('tenant.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('tenant.classes.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'section' => [
                    'required',
                    'string',
                    'max:10',
                    Rule::unique('school_classes')->where(function ($query) use ($request) {
                        return $query->where('name', $request->name);
                    })
                ],
                'students' => 'nullable|integer|min:0',
                'teachers' => 'nullable|integer|min:0',
                'description' => 'nullable|string'
            ], [
                'name.required' => 'ক্লাসের নাম প্রয়োজন',
                'section.required' => 'সেকশন নির্বাচন করুন',
                'section.unique' => 'এই ক্লাস এবং সেকশনের সমন্বয় ইতিমধ্যে বিদ্যমান।',
                'students.integer' => 'শিক্ষার্থী সংখ্যা একটি সংখ্যা হতে হবে',
                'teachers.integer' => 'শিক্ষক সংখ্যা একটি সংখ্যা হতে হবে'
            ]);

            SchoolClass::create([
                'name' => $request->name,
                'section' => $request->section,
                'students' => $request->students ?? 0,
                'teachers' => $request->teachers ?? 0,
                'description' => $request->description
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'ক্লাস সফলভাবে যোগ করা হয়েছে'
                ]);
            }

            return redirect()->route('tenant.classes.index')->with('success', 'ক্লাস সফলভাবে যোগ করা হয়েছে');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ভ্যালিডেশন এরর',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ক্লাস তৈরি করতে সমস্যা হয়েছে: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'ক্লাস তৈরি করতে সমস্যা হয়েছে');
        }
    }

    public function show($id)
    {
        $class = SchoolClass::findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json($class);
        }
        
        return view('tenant.classes.show', compact('class'));
    }

    public function edit($id)
    {
        $class = SchoolClass::findOrFail($id);
        return view('tenant.classes.edit', compact('class'));
    }

    public function update(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'section' => [
                'required',
                'string',
                'max:10',
                Rule::unique('school_classes')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                })->ignore($id)
            ],
            'students' => 'nullable|integer|min:0',
            'teachers' => 'nullable|integer|min:0',
            'description' => 'nullable|string'
        ], [
            'section.unique' => 'এই ক্লাস এবং সেকশনের সমন্বয় ইতিমধ্যে বিদ্যমান।'
        ]);

        $class->update([
            'name' => $request->name,
            'section' => $request->section,
            'students' => $request->students ?? 0,
            'teachers' => $request->teachers ?? 0,
            'description' => $request->description
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'ক্লাস সফলভাবে আপডেট করা হয়েছে'
            ]);
        }

        return redirect()->route('tenant.classes.index')->with('success', 'ক্লাস সফলভাবে আপডেট করা হয়েছে');
    }

    public function destroy($id)
    {
        $class = SchoolClass::findOrFail($id);
        $class->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'ক্লাস সফলভাবে মুছে ফেলা হয়েছে'
            ]);
        }

        return redirect()->route('tenant.classes.index')->with('success', 'ক্লাস সফলভাবে মুছে ফেলা হয়েছে');
    }

    /**
     * Get all classes as JSON for AJAX requests
     */
    public function getClasses()
    {
        $classes = SchoolClass::active()->ordered()->get();
        return response()->json($classes);
    }

    /**
     * Bulk delete classes
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'class_ids' => 'required|array',
                'class_ids.*' => 'integer|exists:school_classes,id'
            ]);

            $classIds = $request->input('class_ids');
            
            // Log the incoming request for debugging
            \Log::info('Bulk delete request received', [
                'class_ids' => $classIds,
                'request_data' => $request->all()
            ]);
            
            $deletedCount = SchoolClass::whereIn('id', $classIds)->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "{$deletedCount}টি ক্লাস সফলভাবে মুছে ফেলা হয়েছে"
                ]);
            }

            return redirect()->route('tenant.classes.index')->with('success', "{$deletedCount}টি ক্লাস সফলভাবে মুছে ফেলা হয়েছে");
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Bulk delete validation error', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ভ্যালিডেশন এরর: ' . implode(', ', $e->validator->errors()->all()),
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->back()->withErrors($e->validator)->withInput();
            
        } catch (\Exception $e) {
            \Log::error('Bulk delete error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'বাল্ক ডিলিট করতে সমস্যা হয়েছে: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'বাল্ক ডিলিট করতে সমস্যা হয়েছে');
        }
    }
}
