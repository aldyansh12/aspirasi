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
                <h2 class="text-3xl font-bold text-gray-900">Tambah Akun</h2>
                <p class="text-gray-600">Buat akun baru untuk admin atau siswa</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.users.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Nama Lengkap</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none">
                        @error('full_name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none">
                        @error('username') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Role</label>
                        <select name="roles" id="role-select" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none appearance-none bg-white">
                            <option value="siswa" {{ old('roles') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="admin" {{ old('roles') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="super_admin" {{ old('roles') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                    </div>

                    <div id="kelas-field" class="space-y-2 {{ old('roles') == 'siswa' || !old('roles') ? '' : 'hidden' }}">
                        <label class="text-sm font-semibold text-gray-700">Kelas</label>
                        <div class="grid grid-cols-2 gap-4">
                            <select name="grade" id="grade-select"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none appearance-none bg-white">
                                <option value="X">X</option>
                                <option value="XI">XI</option>
                                <option value="XII">XII</option>
                            </select>
                            <select name="major" id="major-select"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none appearance-none bg-white">
                                <option value="RPL">RPL</option>
                                <option value="TKR">TKR</option>
                                <option value="TKJ">TKJ</option>
                                <option value="MP">MP</option>
                                <option value="TITL">TITL</option>
                                <option value="TBSM">TBSM</option>
                                <option value="MESIN">MESIN</option>
                            </select>
                        </div>
                        <input type="hidden" name="kelas" id="kelas-input" value="{{ old('kelas') }}">
                        @error('kelas') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
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
                            kelasInput.value = gradeSelect.value + ' ' + majorSelect.value;
                        }

                        roleSelect.addEventListener('change', function() {
                            if (this.value === 'siswa') {
                                kelasField.classList.remove('hidden');
                            } else {
                                kelasField.classList.add('hidden');
                                kelasInput.value = '';
                            }
                        });

                        gradeSelect.addEventListener('change', updateKelas);
                        majorSelect.addEventListener('change', updateKelas);

                        // Initial update
                        updateKelas();
                    });
                </script>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none">
                        @error('password') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold py-3 px-6 rounded-lg hover:from-blue-700 hover:to-blue-800 transition transform hover:-translate-y-0.5 active:translate-y-0 shadow-md">
                        Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
