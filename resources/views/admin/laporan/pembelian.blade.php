@extends('layouts.admin')

@section('header_title', 'Laporan Pembelian')

@section('content')
<div class="space-y-6">

    {{-- PAGE HEADER --}}
    <div>
        <h2 class="text-xl font-extrabold text-slate-900">Laporan Pembelian & Supplier</h2>
        <p class="text-xs text-slate-500 mt-1">Riwayat purchase order dan analisis pengeluaran per mitra supplier.</p>
    </div>

    {{-- ANALISIS SUPPLIER --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900">Analisis Pengeluaran per Supplier</h3>
            <p class="text-xs text-slate-500 mt-0.5">Total nilai pembelian kumulatif dari setiap supplier (tidak termasuk PO dibatalkan)</p>
        </div>
        @php $totalSupplierVal = $supplierAnalysis->sum('total_nilai') ?: 1; @endphp
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-2.5 px-4 text-left">#</th>
                        <th class="py-2.5 px-4 text-left">Supplier</th>
                        <th class="py-2.5 px-4 text-right">Jumlah PO</th>
                        <th class="py-2.5 px-4 text-right">Total Nilai Pembelian</th>
                        <th class="py-2.5 px-4 text-right">Proporsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($supplierAnalysis as $i => $s)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-400 font-bold">{{ $i + 1 }}</td>
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $s->supplier }}</td>
                            <td class="py-3 px-4 text-right text-slate-700">{{ number_format($s->total_po) }}</td>
                            <td class="py-3 px-4 text-right font-bold text-[#0B5CFF]">{{ rupiah($s->total_nilai) }}</td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-16 bg-slate-100 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full bg-[#0B5CFF]"
                                             style="width: {{ round(($s->total_nilai / $totalSupplierVal) * 100) }}%"></div>
                                    </div>
                                    <span class="text-slate-500 w-8 text-right">{{ round(($s->total_nilai / $totalSupplierVal) * 100, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-8 text-slate-400">Belum ada data pembelian.</td></tr>
                    @endforelse
                </tbody>
                @if($supplierAnalysis->count() > 0)
                    <tfoot class="bg-slate-50 border-t border-slate-200">
                        <tr>
                            <td colspan="2" class="py-3 px-4 text-xs font-black text-slate-700">TOTAL</td>
                            <td class="py-3 px-4 text-right text-xs font-black text-slate-900">{{ number_format($supplierAnalysis->sum('total_po')) }}</td>
                            <td class="py-3 px-4 text-right text-xs font-black text-[#0B5CFF]">{{ rupiah($supplierAnalysis->sum('total_nilai')) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- FILTER RIWAYAT PO --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900">Riwayat Purchase Order</h3>
        </div>
        <div class="p-5 border-b border-slate-100">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Supplier</label>
                    <select name="supplier_id" class="border border-slate-200 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0B5CFF]/30">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" @selected(($poFilters['supplier_id'] ?? '') == $sup->id)>{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Status</label>
                    <select name="status" class="border border-slate-200 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0B5CFF]/30">
                        <option value="">Semua Status</option>
                        @foreach(['draft','sent','partial','received','cancelled'] as $st)
                            <option value="{{ $st }}" @selected(($poFilters['status'] ?? '') === $st)>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Dari</label>
                    <input type="date" name="from" value="{{ $poFilters['from'] ?? '' }}"
                        class="border border-slate-200 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0B5CFF]/30">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Sampai</label>
                    <input type="date" name="to" value="{{ $poFilters['to'] ?? '' }}"
                        class="border border-slate-200 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0B5CFF]/30">
                </div>
                <button type="submit" class="px-5 py-2 bg-[#0B5CFF] text-white rounded-xl text-sm font-bold hover:bg-[#063B9E] transition">Filter</button>
                <a href="{{ route('admin.laporan.pembelian') }}" class="px-5 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition">Reset</a>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-2.5 px-4 text-left">No. PO</th>
                        <th class="py-2.5 px-4 text-left">Supplier</th>
                        <th class="py-2.5 px-4 text-left">Tgl. Order</th>
                        <th class="py-2.5 px-4 text-right">Jumlah Item</th>
                        <th class="py-2.5 px-4 text-right">Total Nilai</th>
                        <th class="py-2.5 px-4 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($purchaseOrders as $po)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.pembelian.show', $po->id) }}" class="font-bold text-[#0B5CFF] hover:underline font-mono">{{ $po->po_number }}</a>
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $po->supplier?->name }}</td>
                            <td class="py-3 px-4 text-slate-500">{{ tgl_indo($po->order_date) }}</td>
                            <td class="py-3 px-4 text-right text-slate-700">{{ $po->items->count() }}</td>
                            <td class="py-3 px-4 text-right font-bold text-slate-900">{{ rupiah($po->total_amount) }}</td>
                            <td class="py-3 px-4">
                                <span @class([
                                    'px-2 py-0.5 rounded-full text-[10px] font-bold',
                                    'bg-slate-100 text-slate-700' => $po->status === 'draft',
                                    'bg-blue-100 text-blue-700'   => $po->status === 'sent',
                                    'bg-amber-100 text-amber-700' => $po->status === 'partial',
                                    'bg-emerald-100 text-emerald-700' => $po->status === 'received',
                                    'bg-rose-100 text-rose-700'   => $po->status === 'cancelled',
                                ])>{{ ucfirst($po->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-8 text-slate-400">Tidak ada purchase order.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($purchaseOrders->hasPages())
            <div class="p-4 border-t border-slate-100">{{ $purchaseOrders->links() }}</div>
        @endif
    </div>

</div>
@endsection
