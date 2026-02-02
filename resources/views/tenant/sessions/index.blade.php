@extends('layouts.tenant')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">একাডেমিক সেশন ম্যানেজমেন্ট</h4>
                    <div>
                        <a href="{{ route('tenant.dashboard') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> ফিরে যান
                        </a>
                        <a href="{{ route('tenant.sessions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> নতুন সেশন যোগ করুন
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <p class="text-muted mb-4">শিক্ষাবর্ষ এবং সেশন পরিচালনা করুন</p>
                    
                    <div class="row">
                        @forelse($sessions as $session)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card border {{ $session->is_current ? 'border-success' : 'border-light' }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="card-title">{{ $session->session_name }}</h5>
                                        @if($session->is_current)
                                        <span class="badge bg-success">বর্তমান</span>
                                        @endif
                                    </div>
                                    
                                    <div class="session-details">
                                        <p class="mb-2">
                                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                                            {{ \Carbon\Carbon::parse($session->start_date)->format('d M Y') }} - 
                                            {{ \Carbon\Carbon::parse($session->end_date)->format('d M Y') }}
                                        </p>
                                        
                                        <p class="mb-2">
                                            <i class="fas fa-clock text-info me-2"></i>
                                            মোট সপ্তাহ: {{ $session->total_weeks ?? 'N/A' }}
                                        </p>
                                        
                                        <p class="mb-2">
                                            <i class="fas fa-graduation-cap text-warning me-2"></i>
                                            পরীক্ষা শুরু: {{ $session->exam_start_date ? \Carbon\Carbon::parse($session->exam_start_date)->format('d M Y') : 'নির্ধারিত হয়নি' }}
                                        </p>
                                        
                                        <p class="mb-3">
                                            <i class="fas fa-umbrella-beach text-secondary me-2"></i>
                                            ছুটির দিন: {{ $session->holidays_days ?? 0 }} দিন
                                        </p>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('tenant.sessions.edit', $session->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i> সম্পাদনা
                                        </a>
                                        
                                        @if(!$session->is_current)
                                        <form action="{{ route('tenant.sessions.set-current', $session->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-check"></i> বর্তমান সেট করুন
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger delete-btn"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal"
                                                data-id="{{ $session->id }}"
                                                data-name="{{ $session->session_name }}">
                                            <i class="fas fa-trash"></i> মুছুন
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-calendar-times fa-4x text-muted"></i>
                                </div>
                                <h4 class="text-muted">কোন সেশন নেই</h4>
                                <p class="text-muted mb-4">এখনো কোন একাডেমিক সেশন তৈরি করা হয়নি।</p>
                                <a href="{{ route('tenant.sessions.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> প্রথম সেশন তৈরি করুন
                                </a>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">নিশ্চিতকরণ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>আইটেম মুছে ফেলুন</h6>
                <p>আপনি কি নিশ্চিত যে এটি মুছে ফেলতে চান?</p>
                <p class="text-muted" id="sessionName"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">মুছে ফেলুন</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const deleteForm = document.getElementById('deleteForm');
    const sessionNameElement = document.getElementById('sessionName');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const sessionId = this.getAttribute('data-id');
            const sessionName = this.getAttribute('data-name');
            
            deleteForm.action = `{{ route('tenant.sessions.destroy', '') }}/${sessionId}`;
            sessionNameElement.textContent = sessionName;
        });
    });
});
</script>
@endpush
@endsection