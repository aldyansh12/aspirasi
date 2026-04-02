@extends('layout.admin')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Penugasan & Personil</h2>
            <p class="text-gray-600">Kelola personil penanggung jawab dan penugasan kategori mereka</p>
        </div>
        <button onclick="openCreatePersonModal()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold flex items-center gap-2 shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Personil Baru
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Personil</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jabatan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori yang Dikelola</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($penanggungJawabs as $pj)
                <tr class="hover:bg-gray-50/50 transition duration-200">
                    <td class="px-6 py-5 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold border border-blue-100 mr-3">
                                {{ substr($pj->nama, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-black text-gray-900">{{ $pj->nama }}</div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $pj->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5 whitespace-nowrap">
                        <div class="text-sm text-gray-600 font-medium">{{ $pj->jabatan ?: '-' }}</div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex flex-wrap gap-1.5">
                            @forelse($pj->kategoris as $kat)
                                <span class="px-2.5 py-1 bg-white text-blue-700 rounded-lg text-[10px] font-black border border-blue-200 shadow-sm uppercase tracking-wider">
                                    {{ $kat->name }}
                                </span>
                            @empty
                                <span class="text-xs text-gray-400 italic font-medium">Belum ada penugasan</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-6 py-5 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <button onclick="openAssignModal('{{ $pj->id }}', '{{ $pj->nama }}')" class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-xs font-black border border-blue-200 uppercase tracking-tighter">
                                Atur Penugasan
                            </button>
                             <button onclick="openEditPersonModal('{{ $pj->id }}', '{{ $pj->nama }}', '{{ $pj->email }}', '{{ $pj->jabatan }}')" class="p-1.5 text-gray-400 hover:text-blue-600 transition" title="Edit Profil">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <form action="{{ route('admin.category-assignments.destroy-person', $pj->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus personil ini? Semua kategorinya akan kehilangan penanggung jawab.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 transition" title="Hapus Personil">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- Assignment Modal -->
                <div id="assign-modal-{{ $pj->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-60 backdrop-blur-sm" aria-hidden="true" onclick="closeAssignModal('{{ $pj->id }}')"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form action="{{ route('admin.category-assignments.update-assignments', $pj->id) }}" method="POST">
                                @csrf
                                <div class="px-6 py-8 bg-white sm:p-10">
                                    <div class="flex items-center justify-between mb-8">
                                        <div>
                                            <h3 class="text-2xl font-black text-gray-900 leading-tight">Atur Penugasan</h3>
                                            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $pj->nama }}</p>
                                        </div>
                                        <button type="button" onclick="closeAssignModal('{{ $pj->id }}')" class="p-2 text-gray-400 hover:text-gray-500 bg-gray-50 rounded-full transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="space-y-6">
                                        <div class="p-4 bg-blue-50 border border-blue-100 rounded-2xl flex items-start gap-3 mb-6">
                                            <svg class="w-6 h-6 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-xs font-bold text-blue-700 leading-relaxed uppercase tracking-tight">Kategori yang terpilih akan mengirimkan notifikasi email langsung ke personil ini.</p>
                                        </div>

                                        <div class="space-y-3 max-h-60 overflow-y-auto px-1">
                                            @foreach($categories as $category)
                                            <label class="group relative flex items-center p-4 rounded-2xl cursor-pointer hover:bg-gray-50 transition border-2 border-transparent hover:border-blue-100 bg-white shadow-sm border-gray-100">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" 
                                                           {{ $pj->kategoris->contains($category->id) ? 'checked' : '' }}
                                                           class="w-6 h-6 text-blue-600 border-gray-200 rounded-lg focus:ring-blue-500 transition cursor-pointer">
                                                </div>
                                                <div class="ml-4 text-sm font-black text-gray-700 group-hover:text-blue-700 uppercase">{{ $category->name }}</div>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="px-6 py-6 bg-gray-50 flex flex-col sm:flex-row-reverse gap-3 border-t border-gray-100">
                                    <button type="submit" class="px-8 py-3.5 text-sm font-black text-white bg-blue-600 rounded-2xl hover:bg-blue-700 transition shadow-lg shadow-blue-200 uppercase tracking-widest">
                                        Simpan Perubahan
                                    </button>
                                    <button type="button" onclick="closeAssignModal('{{ $pj->id }}')" class="px-8 py-3.5 text-sm font-black text-gray-700 bg-white border border-gray-200 rounded-2xl hover:bg-gray-50 transition uppercase tracking-widest">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="p-4 bg-gray-50 rounded-full mb-3">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-bold">Belum ada personil yang terdaftar.</p>
                            <button onclick="openCreatePersonModal()" class="mt-4 text-blue-600 font-black text-xs uppercase tracking-widest underline decoration-2 underline-offset-4 decoration-blue-200 hover:decoration-blue-600 transition">Klik untuk menambah baru</button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Category Management Section -->
    <div class="mt-12">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Manajemen Kategori</h2>
                <p class="text-gray-600">Kelola email bersama untuk setiap kategori aspirasi</p>
            </div>
            <button onclick="openCreateCategoryModal()" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-bold flex items-center gap-2 shadow-md text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Kategori Baru
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email Bersama</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($categories as $category)
                    <tr class="hover:bg-gray-50/50 transition duration-200">
                        <td class="px-6 py-5 whitespace-nowrap text-sm font-black text-gray-900 uppercase">
                            {{ $category->name }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-600 font-medium">
                            {{ $category->email ?: 'Belum diatur' }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <button onclick="openEditCategoryModal('{{ $category->id }}', '{{ $category->name }}', '{{ $category->email }}')" class="px-3 py-1.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition text-xs font-black border border-gray-200 uppercase tracking-tighter">
                                Edit Email
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<!-- Create Person Modal -->
<div id="createPersonModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-60 backdrop-blur-sm" onclick="closeCreatePersonModal()"></div>
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.category-assignments.store-person') }}" method="POST">
                @csrf
                <div class="px-6 py-8 bg-white sm:p-10">
                    <h3 class="text-2xl font-black text-gray-900 mb-8 leading-tight">Tambah Personil Penanggung Jawab</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" required placeholder="Contoh: Budi Santoso" class="block w-full px-4 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-0 transition font-bold text-gray-700 bg-gray-50/50">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Email Address</label>
                            <input type="email" name="email" required placeholder="Contoh: budi@sekolah.sch.id" class="block w-full px-4 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-0 transition font-bold text-gray-700 bg-gray-50/50">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Jabatan (Opsional)</label>
                            <input type="text" name="jabatan" placeholder="Contoh: Wakasek Kesiswaan" class="block w-full px-4 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-0 transition font-bold text-gray-700 bg-gray-50/50">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-6 bg-gray-50 flex flex-col sm:flex-row-reverse gap-3 border-t border-gray-100">
                    <button type="submit" class="px-8 py-3.5 text-sm font-black text-white bg-blue-600 rounded-2xl hover:bg-blue-700 transition shadow-lg shadow-blue-200 uppercase tracking-widest">
                        Tambah Personil
                    </button>
                    <button type="button" onclick="closeCreatePersonModal()" class="px-8 py-3.5 text-sm font-black text-gray-700 bg-white border border-gray-200 rounded-2xl hover:bg-gray-50 transition uppercase tracking-widest">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Person Modal -->
<div id="editPersonModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-60 backdrop-blur-sm" onclick="closeEditPersonModal()"></div>
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="editPersonForm" method="POST">
                @csrf
                @method('PUT')
                <div class="px-6 py-8 bg-white sm:p-10">
                    <h3 class="text-2xl font-black text-gray-900 mb-8 leading-tight">Edit Data Personil</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" id="edit_pj_nama" required class="block w-full px-4 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-0 transition font-bold text-gray-700 bg-gray-50/50">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Email Address</label>
                            <input type="email" name="email" id="edit_pj_email" required class="block w-full px-4 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-0 transition font-bold text-gray-700 bg-gray-50/50">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Jabatan (Opsional)</label>
                            <input type="text" name="jabatan" id="edit_pj_jabatan" class="block w-full px-4 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-0 transition font-bold text-gray-700 bg-gray-50/50">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-6 bg-gray-50 flex flex-col sm:flex-row-reverse gap-3 border-t border-gray-100">
                    <button type="submit" class="px-8 py-3.5 text-sm font-black text-white bg-blue-600 rounded-2xl hover:bg-blue-700 transition shadow-lg shadow-blue-200 uppercase tracking-widest">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="closeEditPersonModal()" class="px-8 py-3.5 text-sm font-black text-gray-700 bg-white border border-gray-200 rounded-2xl hover:bg-gray-50 transition uppercase tracking-widest">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-60 backdrop-blur-sm" onclick="closeEditCategoryModal()"></div>
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="px-6 py-8 bg-white sm:p-10">
                    <h3 class="text-2xl font-black text-gray-900 mb-2 leading-tight">Edit Email Kategori</h3>
                    <p id="edit_kat_name_display" class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-8"></p>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Email Bersama</label>
                            <input type="email" name="email" id="edit_kat_email" required placeholder="kategori@sekolah.sch.id" class="block w-full px-4 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-0 transition font-bold text-gray-700 bg-gray-50/50">
                            <p class="mt-2 text-[10px] text-gray-400 font-bold uppercase tracking-tight leading-relaxed">Email ini akan digunakan untuk menerima notifikasi aspirasi baru untuk kategori ini.</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-6 bg-gray-50 flex flex-col sm:flex-row-reverse gap-3 border-t border-gray-100">
                    <button type="submit" class="px-8 py-3.5 text-sm font-black text-white bg-blue-600 rounded-2xl hover:bg-blue-700 transition shadow-lg shadow-blue-200 uppercase tracking-widest">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="closeEditCategoryModal()" class="px-8 py-3.5 text-sm font-black text-gray-700 bg-white border border-gray-200 rounded-2xl hover:bg-gray-50 transition uppercase tracking-widest">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Category Modal -->
<div id="createCategoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-60 backdrop-blur-sm" onclick="closeCreateCategoryModal()"></div>
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.category-assignments.store-category') }}" method="POST">
                @csrf
                <div class="px-6 py-8 bg-white sm:p-10">
                    <h3 class="text-2xl font-black text-gray-900 mb-8 leading-tight">Tambah Kategori Baru</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nama Kategori</label>
                            <input type="text" name="name" required placeholder="Contoh: Sarana & Prasarana" class="block w-full px-4 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-0 transition font-bold text-gray-700 bg-gray-50/50">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Email Bersama (Opsional)</label>
                            <input type="email" name="email" placeholder="kategori@sekolah.sch.id" class="block w-full px-4 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-0 transition font-bold text-gray-700 bg-gray-50/50">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-6 bg-gray-50 flex flex-col sm:flex-row-reverse gap-3 border-t border-gray-100">
                    <button type="submit" class="px-8 py-3.5 text-sm font-black text-white bg-blue-600 rounded-2xl hover:bg-blue-700 transition shadow-lg shadow-blue-200 uppercase tracking-widest">
                        Tambah Kategori
                    </button>
                    <button type="button" onclick="closeCreateCategoryModal()" class="px-8 py-3.5 text-sm font-black text-gray-700 bg-white border border-gray-200 rounded-2xl hover:bg-gray-50 transition uppercase tracking-widest">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openAssignModal(id, name) {
        document.getElementById('assign-modal-' + id).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    function closeAssignModal(id) {
        document.getElementById('assign-modal-' + id).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    function openCreatePersonModal() {
        document.getElementById('createPersonModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    function closeCreatePersonModal() {
        document.getElementById('createPersonModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    function openEditPersonModal(id, nama, email, jabatan) {
        document.getElementById('editPersonForm').action = '/admin/category-assignments/person/' + id;
        document.getElementById('edit_pj_nama').value = nama;
        document.getElementById('edit_pj_email').value = email;
        document.getElementById('edit_pj_jabatan').value = jabatan;
        document.getElementById('editPersonModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    function closeEditPersonModal() {
        document.getElementById('editPersonModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    function openEditCategoryModal(id, name, email) {
        document.getElementById('editCategoryForm').action = '/admin/category-assignments/category/' + id;
        document.getElementById('edit_kat_name_display').textContent = name;
        document.getElementById('edit_kat_email').value = email === 'Belum diatur' ? '' : email;
        document.getElementById('editCategoryModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    function closeEditCategoryModal() {
        document.getElementById('editCategoryModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    function openCreateCategoryModal() {
        document.getElementById('createCategoryModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    function closeCreateCategoryModal() {
        document.getElementById('createCategoryModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
</script>
@endsection
