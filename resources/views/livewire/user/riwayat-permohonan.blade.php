<div class="w-full pb-8">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Riwayat & Status</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Pantau perkembangan status permohonan pengadaan barang/jasa dari unit Anda.</p>
    </div>

    <div class="bg-white dark:bg-card-dark rounded-3xl shadow-md border-t-4 border-t-primary border-x border-b border-gray-100 dark:border-gray-800 overflow-hidden">
        
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-4 bg-gray-50/30 dark:bg-gray-900/10">
            <div class="relative w-full sm:w-96">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="search" class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 text-sm rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary block pl-11 p-3 transition-all shadow-sm" placeholder="Cari judul permohonan...">
            </div>

            <div class="w-full sm:w-auto flex items-center gap-3">
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 hidden sm:block">Filter:</span>
                <select wire:model.live="statusFilter" class="w-full sm:w-48 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 text-sm rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary p-3 transition-all shadow-sm font-medium">
                    <option value="">Semua Status</option>
                    <option value="Baru">Baru Masuk</option>
                    <option value="Proses">Sedang Diproses</option>
                    <option value="Selesai">Selesai / Terealisasi</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-4 font-bold">Tgl. Pengajuan</th>
                        <th class="px-6 py-4 font-bold">Judul Permohonan</th>
                        <th class="px-6 py-4 font-bold text-center">Item</th>
                        <th class="px-6 py-4 font-bold text-center">Status</th>
                        <th class="px-6 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($riwayat as $item)
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/40 transition-colors group">
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400 font-medium whitespace-nowrap">
                                <i class="fa-regular fa-calendar text-gray-400 mr-2"></i> {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white max-w-xs truncate" title="{{ $item->judul }}">
                                {{ $item->judul }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-xs font-bold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700">
                                    {{ $item->items->count() }} Brg
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->status == 'Baru')
                                    <span class="px-3 py-1.5 rounded-full bg-blue-50 text-blue-600 text-xs font-bold dark:bg-blue-900/20 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50">Baru</span>
                                @elseif($item->status == 'Proses')
                                    <span class="px-3 py-1.5 rounded-full bg-orange-50 text-orange-600 text-xs font-bold dark:bg-orange-900/20 dark:text-orange-400 border border-orange-200 dark:border-orange-800/50"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Diproses</span>
                                @elseif($item->status == 'Selesai')
                                    <span class="px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold dark:bg-emerald-900/20 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50"><i class="fa-solid fa-check mr-1"></i> Selesai</span>
                                @else
                                    <span class="px-3 py-1.5 rounded-full bg-red-50 text-red-600 text-xs font-bold dark:bg-red-900/20 dark:text-red-400 border border-red-200 dark:border-red-800/50">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-200">
                                    
                                    <button wire:click="showDetail({{ $item->id }})" class="w-8 h-8 rounded-lg flex items-center justify-center text-primary bg-primary/10 hover:bg-primary hover:text-white transition-all shadow-sm" title="Lihat Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    @if($item->status == 'Baru')
                                        <a href="{{ route('user.buat_permohonan', $item->id) }}" wire:navigate class="w-8 h-8 rounded-lg flex items-center justify-center text-blue-600 bg-blue-50 hover:bg-blue-500 hover:text-white dark:text-blue-400 dark:bg-blue-900/20 transition-all shadow-sm" title="Edit Permohonan">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                        <button type="button" @click="
                                            Swal.fire({
                                                title: 'Batalkan Pengajuan?',
                                                text: 'Permohonan ini akan dihapus secara permanen.',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#ef4444',
                                                cancelButtonColor: '#6b7280',
                                                confirmButtonText: 'Ya, Hapus!',
                                                cancelButtonText: 'Kembali',
                                                customClass: { popup: 'rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-800' }
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $wire.delete({{ $item->id }})
                                                }
                                            })
                                        " class="w-8 h-8 rounded-lg flex items-center justify-center text-red-600 bg-red-50 hover:bg-red-500 hover:text-white dark:text-red-400 dark:bg-red-900/20 transition-all shadow-sm" title="Hapus Pengajuan">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800/50 rounded-full flex items-center justify-center mb-4 text-gray-400 shadow-inner">
                                        <i class="fa-solid fa-box-open text-3xl"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Belum Ada Permohonan</h3>
                                    <p class="text-sm text-gray-500">Anda belum membuat permohonan atau data tidak ditemukan.</p>
                                    <a href="{{ route('user.buat_permohonan') }}" wire:navigate class="mt-4 px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary-dark transition-colors shadow-sm shadow-primary/30">
                                        Buat Permohonan Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($riwayat->hasPages())
            <div class="p-5 border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-card-dark">
                {{ $riwayat->links() }}
            </div>
        @endif
    </div>

    @if($isModalOpen && $selectedData)
        <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/60 backdrop-blur-sm p-4 transition-all" x-data x-transition>
            <div class="relative w-full max-w-3xl bg-white dark:bg-card-dark rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] border border-gray-100 dark:border-gray-800">
                
                <div class="flex justify-between items-center p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/30">
                    <h3 class="text-xl font-extrabold text-gray-900 dark:text-white flex items-center">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3">
                            <i class="fa-solid fa-file-invoice"></i>
                        </div>
                        Detail Permohonan
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white rounded-xl p-2 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto custom-scrollbar">
                    
                    <div class="bg-gray-50/80 dark:bg-gray-800/40 rounded-2xl p-6 mb-6 border border-gray-100 dark:border-gray-800">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1.5 uppercase font-bold tracking-wider">Judul Permohonan</p>
                                <p class="font-bold text-gray-900 dark:text-white text-base">{{ $selectedData->judul }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1.5 uppercase font-bold tracking-wider">Tanggal Pengajuan</p>
                                <p class="font-medium text-gray-900 dark:text-white flex items-center"><i class="fa-regular fa-calendar text-gray-400 mr-2"></i> {{ \Carbon\Carbon::parse($selectedData->tanggal)->translatedFormat('l, d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1.5 uppercase font-bold tracking-wider">Status Saat Ini</p>
                                @if($selectedData->status == 'Baru')
                                    <span class="inline-flex px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-bold border border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800">Baru Masuk</span>
                                @elseif($selectedData->status == 'Proses')
                                    <span class="inline-flex px-3 py-1 rounded-full bg-orange-50 text-orange-600 text-xs font-bold border border-orange-200 dark:bg-orange-900/20 dark:text-orange-400 dark:border-orange-800">Sedang Diproses</span>
                                @elseif($selectedData->status == 'Selesai')
                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800">Selesai / Terealisasi</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1.5 uppercase font-bold tracking-wider">Dokumen PDF</p>
                                @if($selectedData->file_pdf)
                                    <button wire:click="openPdfPreview('{{ $selectedData->file_pdf }}')" class="inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-900/20 dark:hover:bg-red-900/40 dark:text-red-400 text-sm font-bold rounded-xl transition-colors border border-red-200 dark:border-red-800/50 shadow-sm">
                                        <i class="fa-solid fa-file-pdf mr-2 text-lg"></i> Preview Dokumen
                                    </button>
                                @else
                                    <span class="text-sm text-gray-500 italic">Tidak ada file terlampir</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-3 uppercase tracking-wider flex items-center">
                        Rincian Barang yang Diminta <span class="ml-3 bg-primary/10 text-primary px-2.5 py-0.5 rounded-md text-xs">{{ $selectedData->items->count() }} Item</span>
                    </h4>
                    
                    <div class="border border-gray-200 dark:border-gray-800 rounded-2xl overflow-hidden">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-4 font-bold w-12 text-center">No</th>
                                    <th class="px-5 py-4 font-bold">Nama Barang / Jasa</th>
                                    <th class="px-5 py-4 font-bold text-center w-28">Kuantitas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($selectedData->items as $index => $item)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                                        <td class="px-5 py-4 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                                        <td class="px-5 py-4 font-bold text-gray-900 dark:text-white">{{ $item->nama_item }}</td>
                                        <td class="px-5 py-4 text-center font-extrabold text-primary dark:text-primary-light">
                                            <span class="bg-primary/5 px-3 py-1 rounded-lg">{{ $item->jumlah }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-100 dark:border-gray-800 flex justify-end bg-gray-50/30 dark:bg-gray-900/10">
                    <button wire:click="closeModal" class="px-6 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-xl transition-colors shadow-sm">
                        Tutup Detail
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