@extends('layouts.tenant')

@section('title', 'রুম ম্যানেজমেন্ট')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">রুম তালিকা</h3>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRoomModal">
                        নতুন রুম যোগ করুন
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>রুম নম্বর</th>
                                <th>হোস্টেল</th>
                                <th>রুমের ধরন</th>
                                <th>বেড সংখ্যা</th>
                                <th>কস্ট (প্রতি বেড)</th>
                                <th>বিবরণ</th>
                                <th>অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rooms as $room)
                            <tr>
                                <td>{{ $room->room_no }}</td>
                                <td>{{ $room->hostel->name }}</td>
                                <td>{{ $room->room_type }}</td>
                                <td>{{ $room->no_of_bed }}</td>
                                <td>{{ $room->cost_per_bed }}</td>
                                <td>{{ $room->description }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info edit-room" data-room='@json($room)' data-toggle="modal" data-target="#editRoomModal">
                                        এডিট
                                    </button>
                                    <form action="{{ route('tenant.hostels.rooms.destroy', $room->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('আপনি কি নিশ্চিত?')">মুছে ফেলুন</button>
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

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('tenant.hostels.rooms.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">নতুন রুম যোগ করুন</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>হোস্টেল</label>
                        <select name="hostel_id" class="form-control" required>
                            <option value="">সিলেক্ট করুন</option>
                            @foreach($hostels as $hostel)
                            <option value="{{ $hostel->id }}">{{ $hostel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>রুম নম্বর</label>
                        <input type="text" name="room_no" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>রুমের ধরন</label>
                        <input type="text" name="room_type" class="form-control" placeholder="যেমন: AC, Non-AC">
                    </div>
                    <div class="form-group">
                        <label>বেড সংখ্যা</label>
                        <input type="number" name="no_of_bed" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>কস্ট (প্রতি বেড)</label>
                        <input type="number" step="0.01" name="cost_per_bed" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>বিবরণ</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Room Modal -->
<div class="modal fade" id="editRoomModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editRoomForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">রুম এডিট করুন</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>হোস্টেল</label>
                        <select name="hostel_id" id="edit_hostel_id" class="form-control" required>
                            @foreach($hostels as $hostel)
                            <option value="{{ $hostel->id }}">{{ $hostel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>রুম নম্বর</label>
                        <input type="text" name="room_no" id="edit_room_no" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>রুমের ধরন</label>
                        <input type="text" name="room_type" id="edit_room_type" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>বেড সংখ্যা</label>
                        <input type="number" name="no_of_bed" id="edit_no_of_bed" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>কস্ট (প্রতি বেড)</label>
                        <input type="number" step="0.01" name="cost_per_bed" id="edit_cost_per_bed" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>বিবরণ</label>
                        <textarea name="description" id="edit_room_description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary">আপডেট করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('.edit-room').click(function() {
        let room = $(this).data('room');
        let url = "{{ route('tenant.hostels.rooms.update', ':id') }}";
        url = url.replace(':id', room.id);
        
        $('#editRoomForm').attr('action', url);
        $('#edit_hostel_id').val(room.hostel_id);
        $('#edit_room_no').val(room.room_no);
        $('#edit_room_type').val(room.room_type);
        $('#edit_no_of_bed').val(room.no_of_bed);
        $('#edit_cost_per_bed').val(room.cost_per_bed);
        $('#edit_room_description').val(room.description);
    });
</script>
@endsection
