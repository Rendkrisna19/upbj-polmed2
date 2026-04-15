<div class="w-full pb-8">
    <style>
        .pagination-custom nav span[aria-current="page"] > span {
            background-color: #9333ea !important; /* text-purple-600 */
            border-color: #9333ea !important;
            color: white !important;
        }
        .pagination-custom nav a:hover {
            background-color: #faf5ff !important; /* bg-purple-50 */
            color: #7e22ce !important; /* text-purple-700 */
            border-color: #d8b4fe !important; /* border-purple-300 */
        }
    </style>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Riwayat & Status</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau perkembangan status permohonan pengadaan barang/jasa dari unit Anda.</p>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
        
        <div class="p-5 border-b border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 space-y-4 sm:space-y-0 sm:flex sm:flex-wrap sm:items-center sm:justify-between gap-4">
            
            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto flex-grow">
                <div class="relative w-full sm:max-w-md">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="search" class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 block pl-10 py-2.5 transition-colors shadow-sm" placeholder="Cari judul permohonan...">
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
                <select wire:model.live="statusFilter" class="w-full sm:w-auto bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 py-2.5 px-3 transition-colors shadow-sm font-medium cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="Baru">Baru Masuk</option>
                    <option value="Proses">Sedang Diproses</option>
                    <option value="Selesai">Selesai / Terealisasi</option>
                    <option value="Ditolak">Ditolak</option>
                </select>

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
                        <th class="px-6 py-4 font-semibold">Tgl. Pengajuan</th>
                        <th class="px-6 py-4 font-semibold">Judul Permohonan</th>
                        <th class="px-6 py-4 font-semibold text-center">Item</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($riwayat as $item)
                        <tr class="hover:bg-purple-50/50 dark:hover:bg-gray-800/60 transition-colors group">
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                <i class="fa-regular fa-calendar text-gray-400 mr-2"></i> {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white max-w-xs truncate" title="{{ $item->judul }}">
                                {{ $item->judul }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-semibold px-2.5 py-1 rounded-md border border-gray-200 dark:border-gray-700">
                                    {{ $item->items_count }} Brg
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->status == 'Baru')
                                    <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">Baru</span>
                                @elseif($item->status == 'Proses')
                                    <span class="px-2.5 py-1 rounded-full bg-orange-50 text-orange-700 text-xs font-semibold dark:bg-orange-900/30 dark:text-orange-400 border border-orange-200 dark:border-orange-800"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Diproses</span>
                                @elseif($item->status == 'Selesai')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800"><i class="fa-solid fa-check mr-1"></i> Selesai</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-semibold dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-200">
                                    
                                    <button wire:click="showDetail({{ $item->id }})" class="p-2 rounded-lg flex items-center justify-center text-purple-600 bg-purple-50 hover:bg-purple-600 hover:text-white dark:bg-purple-900/20 dark:text-purple-400 dark:hover:bg-purple-600 dark:hover:text-white transition-colors" title="Lihat Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    @if($item->status == 'Baru')
                                        <a href="{{ route('user.buat_permohonan', $item->id) }}" wire:navigate class="p-2 rounded-lg flex items-center justify-center text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white dark:text-blue-400 dark:bg-blue-900/20 dark:hover:bg-blue-600 dark:hover:text-white transition-colors" title="Edit Permohonan">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                        <button type="button" @click="
                                            Swal.fire({
                                                title: 'Batalkan Pengajuan?',
                                                text: 'Permohonan ini akan dihapus secara permanen.',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#dc2626',
                                                cancelButtonColor: '#6b7280',
                                                confirmButtonText: 'Ya, Hapus!',
                                                cancelButtonText: 'Kembali',
                                                customClass: { popup: 'rounded-xl shadow-lg border border-gray-200 dark:border-gray-800' }
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $wire.delete({{ $item->id }})
                                                }
                                            })
                                        " class="p-2 rounded-lg flex items-center justify-center text-red-600 bg-red-50 hover:bg-red-600 hover:text-white dark:text-red-400 dark:bg-red-900/20 dark:hover:bg-red-600 dark:hover:text-white transition-colors" title="Hapus Pengajuan">
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
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4 text-gray-400">
                                        <i class="fa-solid fa-folder-open text-2xl"></i>
                                    </div>
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Belum Ada Permohonan</h3>
                                    <p class="text-sm text-gray-500">Anda belum membuat permohonan atau data tidak ditemukan.</p>
                                    <a href="{{ route('user.buat_permohonan') }}" wire:navigate class="mt-4 px-5 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
                                        Buat Permohonan Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($riwayat->hasPages())
            <div class="p-5 border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 pagination-custom">
                {{ $riwayat->links() }}
            </div>
        @endif
    </div>

    @if($isModalOpen && $selectedData)
        <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/60 backdrop-blur-sm p-4 transition-all" x-data x-transition>
            <div class="relative w-full max-w-3xl bg-white dark:bg-gray-900 rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] border border-gray-200 dark:border-gray-800">
                
                <div class="flex justify-between items-center p-5 border-b border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center mr-3">
                            <i class="fa-solid fa-file-invoice"></i>
                        </div>
                        Detail Permohonan
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white rounded-lg p-2 transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto">
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-5 mb-6 border border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 font-semibold uppercase tracking-wider">Judul Permohonan</p>
                                <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $selectedData->judul }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 font-semibold uppercase tracking-wider">Tanggal Pengajuan</p>
                                <p class="font-medium text-gray-900 dark:text-white text-sm flex items-center"><i class="fa-regular fa-calendar text-gray-400 mr-2"></i> {{ \Carbon\Carbon::parse($selectedData->tanggal)->translatedFormat('l, d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 font-semibold uppercase tracking-wider">Status Saat Ini</p>
                                @if($selectedData->status == 'Baru')
                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800">Baru Masuk</span>
                                @elseif($selectedData->status == 'Proses')
                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-orange-50 text-orange-700 text-xs font-semibold border border-orange-200 dark:bg-orange-900/30 dark:text-orange-400 dark:border-orange-800">Sedang Diproses</span>
                                @elseif($selectedData->status == 'Selesai')
                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800">Selesai / Terealisasi</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 font-semibold uppercase tracking-wider">Dokumen Lampiran</p>
                                @if($selectedData->file_pdf)
                                    <button wire:click="openPdfPreview('{{ $selectedData->file_pdf }}')" class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-300 text-xs font-semibold rounded-lg transition-colors border border-gray-300 dark:border-gray-600 shadow-sm">
                                        <i class="fa-solid fa-file-pdf text-red-500 mr-2 text-sm"></i> Preview PDF
                                    </button>
                                @else
                                    <span class="text-sm text-gray-500 italic">Tidak ada dokumen PDF</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 uppercase tracking-wide flex items-center">
                        Rincian Barang <span class="ml-2 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 px-2 py-0.5 rounded text-xs">{{ $selectedData->items->count() }} Item</span>
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
                                        <td class="px-5 py-3 text-center text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ $item->nama_item }}</td>
                                        <td class="px-5 py-3 text-center text-gray-900 dark:text-white">
                                            {{ $item->jumlah }} Unit
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="p-5 border-t border-gray-200 dark:border-gray-800 flex justify-end bg-gray-50/50 dark:bg-gray-800/30">
                    <button wire:click="closeModal" class="px-5 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors shadow-sm">
                        Tutup
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