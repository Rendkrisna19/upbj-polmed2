<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Realisasi Selesai</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Arsip seluruh permohonan pengadaan yang telah berhasil diselesaikan.</p>
        </div>
        
        <button class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm gap-2">
            <i class="fa-solid fa-print"></i> Cetak Rekapitulasi
        </button>
    </div>

    <div class="bg-white dark:bg-card-dark rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
            <div class="relative w-full max-w-md">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                </div>
                <input wire:model.live.debounce.500ms="search" type="search" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block pl-10 p-3 transition-colors" placeholder="Cari judul, unit, atau tanggal...">
                
                <div wire:loading wire:target="search" class="absolute inset-y-0 right-0 flex items-center pr-4">
                    <i class="fa-solid fa-circle-notch fa-spin text-emerald-500"></i>
                </div>
            </div>
            
            <div class="hidden md:flex items-center gap-2">
                <span class="flex items-center text-xs font-semibold text-emerald-600 bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-400 px-3 py-1.5 rounded-lg border border-emerald-100 dark:border-emerald-800/50">
                    <i class="fa-solid fa-check-double mr-2"></i> {{ $realisasi->total() }} Data Selesai
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50/50 dark:bg-gray-800/50 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold">Tgl Selesai</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Unit Pengirim</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Judul Pengadaan</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-center">Status</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($realisasi as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($item->updated_at)->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->updated_at)->format('H:i WIB') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-building text-gray-400 dark:text-gray-500"></i>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $item->user->unit ?? 'Tidak diketahui' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-gray-200">{{ $item->judul }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-1 rounded-md dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="showDetail({{ $item->id }})" class="text-primary hover:text-primary-dark dark:text-primary-light dark:hover:text-white bg-primary/5 hover:bg-primary/10 dark:bg-primary/10 dark:hover:bg-primary/20 p-2 rounded-lg transition-colors inline-flex items-center gap-2" title="Lihat Rincian">
                                    <i class="fa-regular fa-eye"></i> <span class="text-xs font-semibold">Rincian</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-900/20 mb-4">
                                    <i class="fa-solid fa-box-open text-2xl text-emerald-500"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Belum Ada Data Realisasi</h3>
                                <p class="text-sm text-gray-500">Data permohonan yang telah selesai diproses akan muncul di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-100 dark:border-gray-800">
            {{ $realisasi->links() }}
        </div>
    </div>

    @if($isDetailModalOpen && $detailData)
        <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/60 backdrop-blur-sm p-4 transition-all">
            <div class="relative w-full max-w-2xl bg-white dark:bg-card-dark rounded-3xl shadow-2xl p-6 sm:p-8 transform transition-all">
                
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">
                            Detail Realisasi Pengadaan
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">ID Ref: #REQ-{{ str_pad($detailData->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <button wire:click="closeModal" class="text-gray-400 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-800 dark:hover:text-white rounded-lg p-2 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 sm:p-5 mb-6 border border-gray-100 dark:border-gray-700">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Unit Pengaju</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $detailData->user->unit ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Tanggal Disetujui</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($detailData->updated_at)->translatedFormat('l, d F Y') }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Judul Permohonan</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $detailData->judul }}</p>
                        </div>
                    </div>
                </div>

                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-3">Rincian Barang Terealisasi</h4>
                <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden mb-6">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3 font-semibold">No</th>
                                <th class="px-4 py-3 font-semibold">Nama Item</th>
                                <th class="px-4 py-3 font-semibold text-center">Jumlah (Qty)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($detailData->items ?? [] as $index => $item)
                                <tr class="bg-white dark:bg-card-dark">
                                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-200">{{ $item->nama_item }}</td>
                                    <td class="px-4 py-3 text-center text-gray-900 dark:text-gray-200">{{ $item->jumlah }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500 text-sm">Tidak ada rincian item.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button wire:click="closeModal" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                        Tutup Rincian
                    </button>
                    @if($detailData->id_file)
                        <button class="px-5 py-2.5 text-sm font-medium text-white bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-xl transition-colors shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-file-pdf"></i> Unduh Lampiran
                        </button>
                    @endif
                </div>

            </div>
        </div>
    @endif

</div>