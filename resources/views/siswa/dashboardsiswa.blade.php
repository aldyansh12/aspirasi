@extends('layout.siswa')

@section('content')
<div class="p-4 md:p-8 space-y-8 bg-gray-100 min-h-screen">
    <!-- Header/Greeting Area -->
    <div class="relative overflow-hidden rounded-3xl bg-gray-900 p-8 md:p-12 text-white shadow-2xl">
        <div class="absolute right-0 top-0 w-64 h-64 bg-blue-600 bg-opacity-20 filter blur-3xl -mr-20 -mt-20"></div>
        <div class="absolute left-0 bottom-0 w-64 h-64 bg-indigo-600 bg-opacity-10 filter blur-3xl -ml-20 -mb-20"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <h2 class="text-3xl md:text-4xl font-black tracking-tight mb-2">
                    Halo, <span class="text-blue-400">{{ auth()->user()->username }}</span> 
                    @if(auth()->user()->kelas)
                        <span class="text-gray-400 text-lg md:text-xl font-medium">({{ auth()->user()->kelas }})</span>
                    @endif
                    👋
                </h2>
                <p class="text-gray-400 font-medium max-w-md">Sampaikan aspirasi Anda untuk memajukan sekolah kita bersama. Suara Anda sangat berharga.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('siswa.buataspirasi') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold transition-all transform active:scale-95 shadow-lg">
                    Buat Aspirasi
                </a>
                <a href="{{ route('siswa.aspirasisaya') }}" class="px-6 py-3 bg-gray-800 hover:bg-gray-700 text-white rounded-2xl font-bold transition-all transform active:scale-95">
                    Aspirasi Saya
                </a>
            </div>
        </div>
    </div>



    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group">
            <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-2">Total Aspirasi</p>
            <p class="text-4xl font-black text-gray-900 leading-none">{{ $totalAspirasi }}</p>
            <div class="w-8 h-1 bg-blue-600 mt-4 rounded-full"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group">
            <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-2">Selesai/Disetujui</p>
            <p class="text-4xl font-black text-green-600 leading-none">{{ $aspirasiComplete }}</p>
            <div class="w-8 h-1 bg-green-500 mt-4 rounded-full"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group">
            <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-2">Sedang Diproses</p>
            <p class="text-4xl font-black text-yellow-600 leading-none">{{ $aspirasiPending }}</p>
            <div class="w-8 h-1 bg-yellow-500 mt-4 rounded-full"></div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Aspirasi -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Aspirasi Terbaru Saya</h3>
                    <a href="{{ route('siswa.aspirasisaya') }}" class="text-blue-600 hover:text-blue-700 font-bold text-xs uppercase tracking-tighter transition">Lihat Semua →</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($latestAspirasi as $aspirasi)
                    <div class="group">
                        <a href="{{ route('siswa.aspirasi.show', $aspirasi->id) }}" class="block p-6 hover:bg-blue-50/20 transition-colors">
                            <div class="flex items-center justify-between gap-4 mb-2">
                                <h4 class="font-bold text-gray-900 text-lg leading-tight group-hover:text-blue-600 transition-colors">{{ $aspirasi->feedback_title }}</h4>
                                <span class="shrink-0 px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-widest {{ $aspirasi->status === 'complete' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ str_replace('_', ' ', $aspirasi->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ Str::limit($aspirasi->details, 120) }}</p>
                            <div class="flex items-center gap-4 text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                <span class="flex items-center gap-1">📅 {{ $aspirasi->created_at->format('d M Y') }}</span>
                                <span class="flex items-center gap-1">📁 {{ $aspirasi->kategori->name ?? 'Lainnya' }}</span>
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="p-12 text-center">
                        <p class="text-gray-400 text-sm font-medium">Belum ada aspirasi yang dikirimkan.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Popular Categories -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-extrabold text-gray-900 mb-6 text-base tracking-tight uppercase">Kategori Populer</h3>
                <div class="space-y-3">
                    @forelse($popularKategoris as $kategori)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-transparent hover:border-blue-100 hover:bg-white transition-all group">
                        <span class="text-sm font-bold text-gray-700 group-hover:text-blue-600">{{ $kategori->name }}</span>
                        <span class="text-[10px] font-black bg-blue-600 text-white px-2 py-0.5 rounded-full">{{ $kategori->aspirasis_count }}</span>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 text-center py-4 italic">Belum ada kategori tersedia.</p>
                    @endforelse
                </div>
            </div>

            <!-- Tips Card -->
            <div class="bg-gray-900 rounded-2xl p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/20 blur-3xl -mr-16 -mt-16"></div>
                <h3 class="font-black text-xs uppercase tracking-widest text-blue-400 mb-4">💡 Tips Aspirasi</h3>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <span class="text-blue-500 font-black">01</span>
                        <p class="text-xs text-gray-300 leading-relaxed">Berikan judul yang mencerminkan inti aspirasi Anda agar mudah dipahami.</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="text-blue-500 font-black">02</span>
                        <p class="text-xs text-gray-300 leading-relaxed">Sertakan detail lokasi atau waktu kejadian untuk mempermudah tindak lanjut.</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="text-blue-500 font-black">03</span>
                        <p class="text-xs text-gray-300 leading-relaxed">Pilihlah bahasa yang sopan dan tawarkan solusi yang konstruktif.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
