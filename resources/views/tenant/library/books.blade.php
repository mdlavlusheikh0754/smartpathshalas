@extends('layouts.tenant')

@section('content')
<div class="p-8">
    <div class="max-w-full mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('tenant.library.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-3 rounded-xl transition-colors duration-300 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">বই তালিকা</h1>
                    <p class="text-gray-600 mt-1">লাইব্রেরির সকল বইয়ের তথ্য দেখুন এবং পরিচালনা করুন</p>
                </div>
            </div>
            <button onclick="openAddModal()" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                নতুন বই যোগ করুন
            </button>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">সফল!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">ত্রুটি!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Search and Filter -->
        <div class="bg-white rounded-2xl p-6 shadow-lg mb-8 border border-gray-100">
            <form action="{{ route('tenant.library.books') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="নাম, লেখক বা ISBN দিয়ে খুঁজুন..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div>
                    <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">সকল বিষয়</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors font-bold">খুঁজুন</button>
                </div>
            </form>
        </div>

        <!-- Books Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">বইয়ের নাম</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">লেখক</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">বিষয়</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap text-center">মোট</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap text-center">উপলব্ধ</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">অবস্থান</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 whitespace-nowrap">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($books as $book)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $book->title }}</div>
                                    <div class="text-xs text-gray-500">ISBN: {{ $book->isbn ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $book->author }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-md text-xs font-medium">{{ $book->category ?? 'অন্যান্য' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-700">{{ $book->total_quantity }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 {{ $book->available_quantity > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full text-xs font-bold">
                                        {{ $book->available_quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $book->shelf_location ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button onclick="openEditModal({{ $book }})" class="text-indigo-600 hover:text-indigo-900 p-2 hover:bg-indigo-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <form action="{{ route('tenant.library.books.destroy', $book) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিতভাবে এই বইটি মুছে ফেলতে চান?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 p-2 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">কোন বই পাওয়া যায়নি</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($books->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $books->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add/Edit Book Modal -->
<div id="bookModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">নতুন বই যোগ করুন</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="bookForm" method="POST" action="{{ route('tenant.library.books.store') }}" class="p-6">
            @csrf
            <div id="methodField"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">বইয়ের নাম *</label>
                    <input type="text" name="title" id="bookTitle" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">লেখক *</label>
                    <input type="text" name="author" id="bookAuthor" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">বিষয়</label>
                    <input type="text" name="category" id="bookCategory" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ISBN</label>
                    <input type="text" name="isbn" id="bookISBN" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">মোট পরিমাণ *</label>
                    <input type="number" name="total_quantity" id="bookQuantity" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">তাক/অবস্থান</label>
                    <input type="text" name="shelf_location" id="bookShelf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">মূল্য</label>
                    <input type="number" name="price" id="bookPrice" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">বিবরণ</label>
                    <textarea name="description" id="bookDescription" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-bold transition-all">বাতিল</button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 rounded-xl font-bold shadow-lg transition-all">সংরক্ষণ করুন</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'নতুন বই যোগ করুন';
        document.getElementById('bookForm').action = "{{ route('tenant.library.books.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('bookForm').reset();
        document.getElementById('bookModal').classList.remove('hidden');
    }

    function openEditModal(book) {
        document.getElementById('modalTitle').innerText = 'বইয়ের তথ্য সংশোধন';
        document.getElementById('bookForm').action = `/library/books/${book.id}`;
        document.getElementById('methodField').innerHTML = '@method("PUT")';
        
        document.getElementById('bookTitle').value = book.title;
        document.getElementById('bookAuthor').value = book.author;
        document.getElementById('bookCategory').value = book.category || '';
        document.getElementById('bookISBN').value = book.isbn || '';
        document.getElementById('bookQuantity').value = book.total_quantity;
        document.getElementById('bookShelf').value = book.shelf_location || '';
        document.getElementById('bookPrice').value = book.price || '';
        document.getElementById('bookDescription').value = book.description || '';
        
        document.getElementById('bookModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('bookModal').classList.add('hidden');
    }
</script>
@endsection
