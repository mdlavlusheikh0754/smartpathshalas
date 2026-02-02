@extends('layouts.tenant')

@section('title', 'হোস্টেল ম্যানেজমেন্ট')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">হোস্টেল তালিকা</h3>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addHostelModal">
                        নতুন হোস্টেল যোগ করুন
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>আইডি</th>
                                <th>নাম</th>
                                <th>ধরন</th>
                                <th>ঠিকানা</th>
                                <th>আসন সংখ্যা</th>
                                <th>বিবরণ</th>
                                <th>অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hostels as $hostel)
                            <tr>
                                <td>{{ $hostel->id }}</td>
                                <td>{{ $hostel->name }}</td>
                                <td>{{ $hostel->type == 'boys' ? 'ছেলেদের' : ($hostel->type == 'girls' ? 'মেয়েদের' : 'উভয়') }}</td>
                                <td>{{ $hostel->address }}</td>
                                <td>{{ $hostel->intake }}</td>
                                <td>{{ $hostel->description }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info edit-hostel" data-hostel='@json($hostel)' data-toggle="modal" data-target="#editHostelModal">
                                        এডিট
                                    </button>
                                    <form action="{{ route('tenant.hostels.destroy', $hostel->id) }}" method="POST" class="d-inline">
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

<!-- Add Hostel Modal -->
<div class="modal fade" id="addHostelModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('tenant.hostels.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">নতুন হোস্টেল যোগ করুন</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>হোস্টেলের নাম</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>ধরন</label>
                        <select name="type" class="form-control" required>
                            <option value="boys">ছেলেদের</option>
                            <option value="girls">মেয়েদের</option>
                            <option value="combine">উভয়</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ঠিকানা</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>আসন সংখ্যা (Intake)</label>
                        <input type="number" name="intake" class="form-control" required>
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

<!-- Edit Hostel Modal -->
<div class="modal fade" id="editHostelModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editHostelForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">হোস্টেল এডিট করুন</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>হোস্টেলের নাম</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>ধরন</label>
                        <select name="type" id="edit_type" class="form-control" required>
                            <option value="boys">ছেলেদের</option>
                            <option value="girls">মেয়েদের</option>
                            <option value="combine">উভয়</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ঠিকানা</label>
                        <input type="text" name="address" id="edit_address" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>আসন সংখ্যা (Intake)</label>
                        <input type="number" name="intake" id="edit_intake" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>বিবরণ</label>
                        <textarea name="description" id="edit_description" class="form-control"></textarea>
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
    $('.edit-hostel').click(function() {
        let hostel = $(this).data('hostel');
        let url = "{{ route('tenant.hostels.update', ':id') }}";
        url = url.replace(':id', hostel.id);
        
        $('#editHostelForm').attr('action', url);
        $('#edit_name').val(hostel.name);
        $('#edit_type').val(hostel.type);
        $('#edit_address').val(hostel.address);
        $('#edit_intake').val(hostel.intake);
        $('#edit_description').val(hostel.description);
    });
</script>
@endsection
