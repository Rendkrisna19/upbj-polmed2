<div class="w-full pb-12">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center">
            Pekerjaan Berjalan
            @if($pekerjaan->total() > 0)
                <span class="ml-4 bg-purple-500 text-white text-sm font-bold px-3 py-1 rounded-full shadow-sm animate-pulse">
                    {{ $pekerjaan->total() }} Aktif
                </span>
            @endif
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Daftar pengadaan barang dan jasa yang sedang dalam proses pengerjaan oleh tim Admin.</p>
    </div>

    <div class="bg-white dark:bg-card-dark rounded-3xl shadow-md border-t-4 border-t-purple-500 border-x border-b border-gray-100 dark:border-gray-800 overflow-hidden">
        
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-900/10">
            <div class="relative w-full sm:w-96">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="search" class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 text-sm rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 block pl-11 p-3.5 transition-all shadow-sm" placeholder="Cari judul permohonan atau nama unit...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-4 font-bold">Mulai Diproses</th>
                        <th class="px-6 py-4 font-bold">Unit / Pemohon</th>
                        <th class="px-6 py-4 font-bold">Judul Pekerjaan</th>
                        <th class="px-6 py-4 font-bold text-center">Item</th>
                        <th class="px-6 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($pekerjaan as $item)
                        <tr class="hover:bg-purple-50/50 dark:hover:bg-purple-900/10 transition-colors group">
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400 font-medium whitespace-nowrap">
                                <i class="fa-solid fa-stopwatch text-purple-400 mr-2"></i> 
                                {{ $item->updated_at->diffForHumans() }}
                                <p class="text-xs text-gray-400 mt-1 ml-6">{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $item->unit->nama_unit ?? '-' }}</div>
                                <div class="text-xs text-gray-500 mt-0.5"><i class="fa-solid fa-user text-gray-400 mr-1"></i> {{ $item->user->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white max-w-xs truncate" title="{{ $item->judul }}">
                                {{ $item->judul }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-xs font-bold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700">
                                    {{ $item->items->count() }} Brg
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="showDetail({{ $item->id }})" class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-white bg-purple-500 hover:bg-purple-600 transition-colors shadow-sm font-semibold text-xs shadow-purple-500/30" title="Kelola Pekerjaan">
                                    <i class="fa-solid fa-bars-progress mr-2"></i> Kelola
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-24 h-24 bg-gray-50 dark:bg-gray-800/50 rounded-full flex items-center justify-center mb-5 text-gray-400 shadow-inner">
                                        <i class="fa-solid fa-clipboard-list text-4xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Tidak Ada Pekerjaan Aktif</h3>
                                    <p class="text-sm text-gray-500 max-w-sm">Saat ini tidak ada permohonan pengadaan yang sedang Anda kerjakan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pekerjaan->hasPages())
            <div class="p-5 border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-card-dark">
                {{ $pekerjaan->links() }}
            </div>
        @endif
    </div>

    @if($isModalOpen && $selectedData)
        <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/70 backdrop-blur-sm p-4 transition-all" x-data x-transition>
            <div class="relative w-full max-w-4xl bg-white dark:bg-card-dark rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] border border-gray-100 dark:border-gray-800">
                
                <div class="flex justify-between items-center p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/30">
                    <div>
                        <h3 class="text-xl font-extrabold text-gray-900 dark:text-white flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center mr-3 dark:bg-purple-900/30 dark:text-purple-400">
                                <i class="fa-solid fa-person-digging"></i>
                            </div>
                            Detail Pekerjaan
                        </h3>
                        <p class="text-xs text-gray-500 mt-1 ml-11">Tiket #PRM-{{ str_pad($selectedData->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <button wire:click="closeModal" class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white rounded-xl p-2 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-1 space-y-4">
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-5 border border-gray-100 dark:border-gray-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase font-bold tracking-wider">Unit Pemohon</p>
                                <p class="font-bold text-purple-600 dark:text-purple-400 text-base">{{ $selectedData->unit->nama_unit ?? '-' }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1"><i class="fa-solid fa-user-circle mr-1"></i> {{ $selectedData->user->name ?? '-' }}</p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-5 border border-gray-100 dark:border-gray-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase font-bold tracking-wider">Mulai Dikerjakan</p>
                                <p class="font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($selectedData->updated_at)->translatedFormat('d F Y') }}</p>
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/10 rounded-2xl p-5 border border-blue-100 dark:border-blue-800/30">
                                <p class="text-xs text-blue-600 dark:text-blue-400 mb-2 uppercase font-bold tracking-wider">Dokumen Referensi</p>
                                @if($selectedData->file_pdf)
                                    <button wire:click="openPdfPreview('{{ $selectedData->file_pdf }}')" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-white hover:bg-gray-50 text-blue-700 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-blue-400 text-sm font-bold rounded-xl transition-colors border border-blue-200 dark:border-blue-800/50 shadow-sm">
                                        <i class="fa-solid fa-file-pdf mr-2 text-red-500 text-lg"></i> Cek Dokumen PDF
                                    </button>
                                @else
                                    <span class="text-sm text-gray-500 italic">Tidak ada dokumen</span>
                                @endif
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase font-bold tracking-wider">Judul Pengadaan</p>
                                <p class="font-extrabold text-gray-900 dark:text-white text-lg">{{ $selectedData->judul }}</p>
                            </div>

                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-3 uppercase tracking-wider flex items-center border-b border-gray-100 dark:border-gray-800 pb-2">
                                Rincian Barang yang Dibutuhkan <span class="ml-auto bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 px-3 py-1 rounded-lg text-xs">{{ $selectedData->items->count() }} Item</span>
                            </h4>
                            
                            <div class="border border-gray-200 dark:border-gray-800 rounded-2xl overflow-hidden">
                                <table class="w-full text-sm text-left">
                                    <thead class="text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 uppercase tracking-wider">
                                        <tr>
                                            <th class="px-5 py-3 font-bold w-12 text-center">No</th>
                                            <th class="px-5 py-3 font-bold">Nama Barang / Jasa</th>
                                            <th class="px-5 py-3 font-bold text-center w-28">Kuantitas</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                        @foreach($selectedData->items as $index => $item)
                                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                                                <td class="px-5 py-3 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                                                <td class="px-5 py-3 font-bold text-gray-900 dark:text-white">{{ $item->nama_item }}</td>
                                                <td class="px-5 py-3 text-center font-extrabold text-purple-600 dark:text-purple-400">
                                                    {{ $item->jumlah }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-100 dark:border-gray-800 flex flex-col-reverse sm:flex-row justify-between gap-4 bg-gray-50/50 dark:bg-gray-900/30">
                    <button wire:click="closeModal" class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-xl transition-colors shadow-sm text-center">
                        Tutup
                    </button>
                    
                    <button type="button" @click="
                        Swal.fire({
                            title: 'Pekerjaan Selesai?',
                            text: 'Pekerjaan ini akan ditandai Selesai dan dipindahkan ke Arsip Realisasi.',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#10b981',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, Selesaikan!',
                            cancelButtonText: 'Batal',
                            customClass: { popup: 'rounded-3xl shadow-2xl' }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $wire.selesaikanPekerjaan({{ $selectedData->id }})
                            }
                        })
                    " class="px-8 py-3 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/30 flex items-center justify-center active:scale-95">
                        <i class="fa-solid fa-check-double mr-2"></i> Tandai Selesai
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($isPdfModalOpen && $previewPdfUrl)
        <div class="fixed inset-0 z-[110] flex items-center justify-center bg-gray-900/80 backdrop-blur-md p-4 transition-all" x-data x-transition>
            <div class="relative w-full max-w-5xl h-[85vh] bg-white dark:bg-gray-900 rounded-3xl shadow-2xl overflow-hidden flex flex-col border border-gray-700">
                <div class="flex justify-between items-center px-6 py-4 bg-gray-900 text-white shrink-0 border-b border-gray-800">
                    <h3 class="text-sm font-semibold flex items-center">
                        <i class="fa-solid fa-file-pdf text-red-500 mr-2 text-lg"></i> Pratinjau Dokumen PDF
                    </h3>
                    <div class="flex items-center gap-2">
                        <a href="{{ $previewPdfUrl }}" download class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white text-xs font-bold rounded-lg transition-colors border border-gray-700 flex items-center">
                            <i class="fa-solid fa-download mr-2"></i> Unduh File
                        </a>
                        <button wire:click="closePdfPreview" class="p-2 text-gray-400 hover:text-white hover:bg-red-500 rounded-lg transition-colors ml-2">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                </div>
                <div class="flex-1 w-full h-full bg-gray-800">
                    <iframe src="{{ $previewPdfUrl }}#toolbar=0" class="w-full h-full border-0"></iframe>
                </div>
            </div>
        </div>
    @endif

</div>