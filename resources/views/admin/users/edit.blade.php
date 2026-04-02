@extends('layout.admin')

@section('content')
<div class="p-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="p-2 hover:bg-gray-100 rounded-full transition">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Edit Akun</h2>
                <p class="text-gray-600">Perbarui informasi akun {{ $user->full_name }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Nama Lengkap</label>
                        <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none">
                        @error('full_name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none">
                        @error('username') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Role</label>
                        <select name="roles" id="role-select" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none appearance-none bg-white">
                            <option value="siswa" {{ old('roles', $user->roles) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="admin" {{ old('roles', $user->roles) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="super_admin" {{ old('roles', $user->roles) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                    </div>

                    <div id="kelas-field" class="space-y-2 {{ old('roles', $user->roles) == 'siswa' ? '' : 'hidden' }}">
                        <label class="text-sm font-semibold text-gray-700">Kelas</label>
                        <div class="grid grid-cols-2 gap-4">
                            @php
                                $kelasParts = explode(' ', $user->kelas ?? 'X RPL');
                                $currentGrade = $kelasParts[0] ?? 'X';
                                $currentMajor = $kelasParts[1] ?? 'RPL';
                            @endphp
                            <select id="grade-select"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none appearance-none bg-white">
                                <option value="X" {{ $currentGrade == 'X' ? 'selected' : '' }}>X</option>
                                <option value="XI" {{ $currentGrade == 'XI' ? 'selected' : '' }}>XI</option>
                                <option value="XII" {{ $currentGrade == 'XII' ? 'selected' : '' }}>XII</option>
                            </select>
                            <select id="major-select"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none appearance-none bg-white">
                                <option value="RPL" {{ $currentMajor == 'RPL' ? 'selected' : '' }}>RPL</option>
                                <option value="TKR" {{ $currentMajor == 'TKR' ? 'selected' : '' }}>TKR</option>
                                <option value="TKJ" {{ $currentMajor == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                                <option value="MP" {{ $currentMajor == 'MP' ? 'selected' : '' }}>MP</option>
                                <option value="TITL" {{ $currentMajor == 'TITL' ? 'selected' : '' }}>TITL</option>
                                <option value="TBSM" {{ $currentMajor == 'TBSM' ? 'selected' : '' }}>TBSM</option>
                                <option value="MESIN" {{ $currentMajor == 'MESIN' ? 'selected' : '' }}>MESIN</option>
                            </select>
                        </div>
                        <input type="hidden" name="kelas" id="kelas-input" value="{{ old('kelas', $user->kelas) }}">
                        @error('kelas') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 rounded-lg">
                    <div class="md:col-span-2">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 italic">Kosongkan jika tidak ingin mengubah password</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Password Baru</label>
                        <input type="password" name="password"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none">
                        @error('password') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold py-3 px-6 rounded-lg hover:from-blue-700 hover:to-blue-800 transition transform hover:-translate-y-0.5 active:translate-y-0 shadow-md">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="block text-center mt-4 text-sm font-semibold text-gray-500 hover:text-gray-700 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role-select');
        const kelasField = document.getElementById('kelas-field');
        const gradeSelect = document.getElementById('grade-select');
        const majorSelect = document.getElementById('major-select');
        const kelasInput = document.getElementById('kelas-input');

        function updateKelas() {
            if (roleSelect.value === 'siswa') {
                kelasInput.value = gradeSelect.value + ' ' + majorSelect.value;
            } else {
                kelasInput.value = '';
            }
        }

        roleSelect.addEventListener('change', function() {
            if (this.value === 'siswa') {
                kelasField.classList.remove('hidden');
                updateKelas();
            } else {
                kelasField.classList.add('hidden');
                kelasInput.value = '';
            }
        });

        gradeSelect.addEventListener('change', updateKelas);
        majorSelect.addEventListener('change', updateKelas);
    });
</script>
@endsection
