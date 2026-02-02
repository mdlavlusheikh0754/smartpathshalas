@extends('layouts.tenant')

@section('title', 'হোস্টেল বরাদ্দ')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">নতুন বরাদ্দ</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('tenant.hostels.allocations.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>শিক্ষার্থী</label>
                            <select name="student_id" class="form-control select2" required>
                                <option value="">সিলেক্ট করুন</option>
                                @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->id }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>রুম</label>
                            <select name="room_id" class="form-control" required>
                                <option value="">সিলেক্ট করুন</option>
                                @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->hostel->name }} - Room: {{ $room->room_no }} (Bed: {{ $room->no_of_bed }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>বরাদ্দ তারিখ</label>
                            <input type="date" name="allocation_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">বরাদ্দ করুন</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">বর্তমান বরাদ্দ তালিকা</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>শিক্ষার্থী</th>
                                <th>হোস্টেল</th>
                                <th>রুম</th>
                                <th>তারিখ</th>
                                <th>অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allocations as $allocation)
                            <tr>
                                <td>{{ $allocation->student->name }}</td>
                                <td>{{ $allocation->room->hostel->name }}</td>
                                <td>{{ $allocation->room->room_no }}</td>
                                <td>{{ $allocation->allocation_date }}</td>
                                <td>
                                    <form action="{{ route('tenant.hostels.allocations.destroy', $allocation->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('আপনি কি এই শিক্ষার্থীকে রুম থেকে রিলিজ করতে চান?')">রিলিজ</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
