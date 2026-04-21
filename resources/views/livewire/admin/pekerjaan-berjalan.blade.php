<div class="w-full pb-12" wire:poll.10s>
    
    <div wire:loading.flex wire:target="selesaikanPekerjaan" class="fixed inset-0 z-[150] bg-gray-900/60 backdrop-blur-sm flex items-center justify-center transition-all">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-2xl flex flex-col items-center max-w-sm w-full mx-4 animate-pulse border border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 border-4 border-purple-200 border-t-purple-600 rounded-full animate-spin mb-4"></div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 text-center">Menyelesaikan Pekerjaan...</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Mohon tunggu, sistem sedang memindahkan ke arsip Realisasi dan mengirim notifikasi email.</p>
        </div>
    </div>

    <style>
        .pagination-custom nav span[aria-current="page"] > span {
            background-color: #9333ea !important; 
            border-color: #9333ea !important;
            color: white !important;
        }
        .pagination-custom nav a:hover {
            background-color: #faf5ff !important; 
            color: #7e22ce !important; 
            border-color: #d8b4fe !important; 
        }
    </style>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight flex items-center">
            Pekerjaan Berjalan
            @if($pekerjaan->total() > 0)
                <span class="ml-4 bg-purple-50 text-purple-700 border border-purple-200 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm dark:bg-purple-900/30 dark:text-purple-400 dark:border-purple-800">
                    {{ $pekerjaan->total() }} Aktif
                </span>
            @endif
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar pengadaan barang dan jasa yang sedang dalam proses pengerjaan oleh tim Admin.</p>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden relative">
        
        <div wire:loading.flex wire:target="search, monthFilter, yearFilter, perPage" class="absolute inset-0 z-10 bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm items-center justify-center">
            <div class="w-8 h-8 border-4 border-purple-200 border-t-purple-600 rounded-full animate-spin"></div>
        </div>

        <div class="p-5 border-b border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 space-y-4 sm:space-y-0 sm:flex sm:flex-wrap sm:items-center sm:justify-between gap-4">
            
            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto flex-grow">
                <div class="relative w-full sm:max-w-md">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="search" class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 block pl-10 py-2.5 transition-colors shadow-sm" placeholder="Cari judul pekerjaan atau nama unit...">
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Tampilkan:</span>
                    <select wire:model.live="perPage" class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 py-2.5 px-3 transition-colors shadow-sm font-medium cursor-pointer">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <select wire:model.live="monthFilter" class="w-full sm:w-auto bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 py-2.5 px-3 transition-colors shadow-sm font-medium cursor-pointer">
                    <option value="">Semua Bulan</option>
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>

                <select wire:model.live="yearFilter" class="w-full sm:w-auto bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 py-2.5 px-3 transition-colors shadow-sm font-medium cursor-pointer">
                    <option value="">Semua Tahun</option>
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Mulai Diproses</th>
                        <th class="px-6 py-4 font-semibold">Unit / Pemohon</th>
                        <th class="px-6 py-4 font-semibold">Judul Pekerjaan</th>
                        <th class="px-6 py-4 font-semibold text-center">Item</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($pekerjaan as $item)
                        <tr class="hover:bg-purple-50/50 dark:hover:bg-gray-800/60 transition-colors group">
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-stopwatch text-purple-500 mr-2"></i> 
                                    <span class="font-medium">{{ $item->updated_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 ml-6">{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $item->unit->nama_unit ?? 'Unit Tidak Diketahui' }}</div>
                                <div class="text-xs text-gray-500 mt-1 flex items-center"><i class="fa-solid fa-user text-gray-400 mr-1.5"></i> {{ $item->user->name ?? 'User Terhapus' }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white max-w-xs truncate" title="{{ $item->judul }}">
                                {{ $item->judul }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-semibold px-2.5 py-1 rounded-md border border-gray-200 dark:border-gray-700">
                                    {{ $item->items_count }} Brg
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="showDetail({{ $item->id }})" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-white bg-purple-600 hover:bg-purple-700 transition-colors shadow-sm font-medium text-xs" title="Kelola Pekerjaan">
                                    <i class="fa-solid fa-bars-progress mr-2"></i> Kelola
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-purple-50 dark:bg-purple-900/20 rounded-full flex items-center justify-center mb-4 text-purple-500">
                                        <i class="fa-solid fa-clipboard-list text-2xl"></i>
                                    </div>
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Tidak Ada Pekerjaan Aktif</h3>
                                    <p class="text-sm text-gray-500 max-w-sm">Saat ini tidak ada permohonan pengadaan yang sedang Anda kerjakan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pekerjaan->hasPages())
            <div class="p-5 border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 pagination-custom">
                {{ $pekerjaan->links() }}
            </div>
        @endif
    </div>

    @if($isModalOpen && $selectedData)
        <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/60 backdrop-blur-sm p-4 transition-all" x-data x-transition>
            <div class="relative w-full max-w-4xl bg-white dark:bg-gray-900 rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] border border-gray-200 dark:border-gray-800">
                
                <div class="flex justify-between items-center p-5 border-b border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center mr-3 dark:bg-purple-900/30 dark:text-purple-400">
                                <i class="fa-solid fa-person-digging"></i>
                            </div>
                            Detail Pekerjaan
                        </h3>
                        <p class="text-xs text-gray-500 mt-1 ml-11">Tiket #PRM-{{ str_pad($selectedData->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <button wire:click="closeModal" class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white rounded-lg p-2 transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto flex-1">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-1 space-y-4">
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase font-semibold tracking-wider">Unit Pemohon</p>
                                <p class="font-semibold text-purple-600 dark:text-purple-400 text-sm">{{ $selectedData->unit->nama_unit ?? '-' }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-300 mt-1.5 flex items-center"><i class="fa-solid fa-user-circle mr-1.5"></i> {{ $selectedData->user->name ?? '-' }}</p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase font-semibold tracking-wider">Mulai Dikerjakan</p>
                                <p class="font-medium text-gray-900 dark:text-white text-sm">{{ \Carbon\Carbon::parse($selectedData->updated_at)->translatedFormat('d F Y') }}</p>
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/10 rounded-xl p-4 border border-blue-200 dark:border-blue-800/30">
                                <p class="text-xs text-blue-700 dark:text-blue-400 mb-3 uppercase font-semibold tracking-wider flex items-center justify-between">
                                    Dokumen Referensi
                                    <span class="bg-blue-200 text-blue-800 dark:bg-blue-800 dark:text-blue-200 py-0.5 px-2 rounded-full text-[10px]">{{ $selectedData->dokumenLampirans->count() }} File</span>
                                </p>
                                
                                <div class="space-y-2">
                                    @forelse($selectedData->dokumenLampirans as $doc)
                                        <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-2.5 rounded-lg border border-blue-100 dark:border-blue-800/50 shadow-sm">
                                            <div class="flex items-center overflow-hidden mr-2">
                                                <i class="fa-solid fa-file-pdf text-red-500 text-lg mr-2 shrink-0"></i>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate" title="{{ $doc->nama_dokumen ?: 'Dokumen ' . $loop->iteration }}">
                                                    {{ $doc->nama_dokumen ?: 'Dokumen ' . $loop->iteration }}
                                                </span>
                                            </div>
                                            <button wire:click="openPdfPreview('{{ $doc->file_path }}')" class="shrink-0 text-[11px] font-bold px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 dark:bg-blue-900/50 dark:hover:bg-blue-700 dark:text-blue-300 rounded-md transition-colors">
                                                Buka
                                            </button>
                                        </div>
                                    @empty
                                        <span class="text-sm text-gray-500 italic block mt-1">Tidak ada dokumen dilampirkan</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="mb-5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-4 rounded-xl">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase font-semibold tracking-wider">Judul Pengadaan</p>
                                <p class="font-bold text-gray-900 dark:text-white text-base">{{ $selectedData->judul }}</p>
                            </div>

                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 uppercase tracking-wide flex items-center border-b border-gray-200 dark:border-gray-700 pb-2">
                                Rincian Barang Kebutuhan <span class="ml-auto bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-2.5 py-0.5 rounded text-xs">{{ $selectedData->items->count() }} Item</span>
                            </h4>
                            
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                <table class="w-full text-sm text-left">
                                    <thead class="text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 uppercase tracking-wide border-b border-gray-200 dark:border-gray-700">
                                        <tr>
                                            <th class="px-5 py-3 font-semibold w-12 text-center">No</th>
                                            <th class="px-5 py-3 font-semibold">Nama Barang / Jasa</th>
                                            <th class="px-5 py-3 font-semibold text-center w-28">Kuantitas</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-900">
                                        @foreach($selectedData->items as $index => $item)
                                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                                                <td class="px-5 py-3 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                                                <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ $item->nama_item }}</td>
                                                <td class="px-5 py-3 text-center font-semibold text-purple-600 dark:text-purple-400">
                                                    {{ $item->jumlah }} Unit
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-5 border-t border-gray-200 dark:border-gray-800 flex flex-col-reverse sm:flex-row justify-between gap-4 bg-gray-50/50 dark:bg-gray-800/30">
                    <button wire:click="closeModal" class="px-6 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors shadow-sm text-center">
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
                            customClass: { popup: 'rounded-xl shadow-lg border border-gray-200 dark:border-gray-800' }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $wire.selesaikanPekerjaan({{ $selectedData->id }})
                            }
                        })
                    " class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm flex items-center justify-center">
                        <i class="fa-solid fa-check-double mr-2"></i> Tandai Selesai
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($isPdfModalOpen && $previewPdfUrl)
        <div class="fixed inset-0 z-[110] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm p-4 transition-all" x-data x-transition>
            <div class="relative w-full max-w-5xl h-[85vh] bg-white dark:bg-gray-900 rounded-xl shadow-2xl overflow-hidden flex flex-col border border-gray-700">
                <div class="flex justify-between items-center px-5 py-3 bg-gray-900 text-white shrink-0 border-b border-gray-800">
                    <h3 class="text-sm font-medium flex items-center">
                        <i class="fa-solid fa-file-pdf text-red-500 mr-2"></i> Pratinjau Dokumen PDF
                    </h3>
                    <div class="flex items-center gap-2">
                        <a href="{{ $previewPdfUrl }}" download class="px-3 py-1.5 bg-gray-800 hover:bg-gray-700 text-white text-xs font-medium rounded-lg transition-colors border border-gray-700 flex items-center">
                            <i class="fa-solid fa-download mr-1.5"></i> Unduh
                        </a>
                        <button wire:click="closePdfPreview" class="p-1.5 text-gray-400 hover:text-white hover:bg-red-500 rounded-lg transition-colors ml-2">
                            <i class="fa-solid fa-xmark text-lg"></i>
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