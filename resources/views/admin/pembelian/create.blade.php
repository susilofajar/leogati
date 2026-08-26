@extends('layouts.admin')

@section('header_title', 'Buat Purchase Order Baru')

@section('content')
<div class="space-y-6" x-data="poBuilder()">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">

        <a href="{{ route('admin.pembelian.index') }}"
           class="w-10 h-10 shrink-0 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 transition flex items-center justify-center">

            <svg class="w-5 h-5 text-slate-600"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 19l-7-7 7-7"/>
            </svg>

        </a>

        <div>
            <h2 class="text-xl font-extrabold text-slate-900">
                Buat Purchase Order Baru
            </h2>

            <p class="text-xs text-slate-500 mt-1">
                Buat pesanan pembelian baru kepada supplier dan tentukan barang yang akan diterima.
            </p>
        </div>

    </div>


    {{-- VALIDATION ERROR --}}
    @if($errors->any())

        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4">

            <div class="flex items-start gap-3">

                <div class="w-8 h-8 shrink-0 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                    </svg>

                </div>

                <div>
                    <p class="text-xs font-extrabold text-rose-800">
                        Terdapat kesalahan pada formulir
                    </p>

                    <ul class="mt-1 text-xs text-rose-700 list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>

        </div>

    @endif


    <form method="POST" action="{{ route('admin.pembelian.store') }}">
        @csrf

        {{-- ========================================= --}}
        {{-- SUPPLIER + WAREHOUSE + SUMMARY --}}
        {{-- ========================================= --}}

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">

            {{-- DATA SUPPLIER --}}
            <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

                <div class="px-5 py-4 border-b border-slate-100">

                    <div class="flex items-center gap-3">

                        <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center">

                            <svg class="w-4 h-4 text-[#0B5CFF]"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5M9 9h.01M15 9h.01M9 12h.01M15 12h.01"/>
                            </svg>

                        </div>

                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">
                                Data Supplier & Gudang
                            </h3>

                            <p class="text-[10px] text-slate-500">
                                Tentukan supplier dan lokasi penerimaan barang.
                            </p>
                        </div>

                    </div>

                </div>


                <div class="p-5">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- SUPPLIER --}}
                        <div>

                            <label class="block text-[11px] font-bold text-slate-600 mb-1.5">
                                Pilih Supplier
                                <span class="text-rose-500">*</span>
                            </label>

                            <select name="supplier_id"
                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#0B5CFF] focus:bg-white transition @error('supplier_id') border-rose-400 bg-rose-50 @enderror"
                                    required>

                                <option value="">
                                    -- Pilih Supplier --
                                </option>

                                @foreach($suppliers as $sup)

                                    <option value="{{ $sup->id }}"
                                        {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>

                                        {{ $sup->name }} ({{ $sup->code }})

                                    </option>

                                @endforeach

                            </select>

                            @error('supplier_id')
                                <p class="mt-1 text-[10px] font-semibold text-rose-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- WAREHOUSE --}}
                        <div>

                            <label class="block text-[11px] font-bold text-slate-600 mb-1.5">
                                Gudang Tujuan Penerimaan
                                <span class="text-rose-500">*</span>
                            </label>

                            <select name="warehouse_id"
                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#0B5CFF] focus:bg-white transition @error('warehouse_id') border-rose-400 bg-rose-50 @enderror"
                                    required>

                                <option value="">
                                    -- Pilih Gudang Tujuan --
                                </option>

                                @foreach($warehouses as $wh)

                                    <option value="{{ $wh->id }}"
                                        {{ old('warehouse_id', $wh->is_default ? $wh->id : '') == $wh->id ? 'selected' : '' }}>

                                        {{ $wh->name }} ({{ $wh->code }}){{ $wh->is_default ? ' — Utama' : '' }}

                                    </option>

                                @endforeach

                            </select>

                            @error('warehouse_id')
                                <p class="mt-1 text-[10px] font-semibold text-rose-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- EXPECTED DATE --}}
                        <div>

                            <label class="block text-[11px] font-bold text-slate-600 mb-1.5">
                                Estimasi Tanggal Tiba
                            </label>

                            <input type="date"
                                   name="expected_at"
                                   value="{{ old('expected_at') }}"
                                   class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#0B5CFF] focus:bg-white transition">

                        </div>


                        {{-- NOTES --}}
                        <div>

                            <label class="block text-[11px] font-bold text-slate-600 mb-1.5">
                                Catatan Internal
                            </label>

                            <textarea name="notes"
                                      rows="1"
                                      placeholder="Catatan pengiriman, no invoice supplier, dll"
                                      class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#0B5CFF] focus:bg-white transition resize-none">{{ old('notes') }}</textarea>

                        </div>

                    </div>

                </div>

            </div>


            {{-- SUMMARY --}}
            <div class="bg-slate-900 rounded-2xl shadow-2xs overflow-hidden relative">

                <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full bg-blue-500/10"></div>

                <div class="relative p-5 h-full flex flex-col justify-between">

                    <div>

                        <div class="flex items-center gap-3">

                            <div class="w-8 h-8 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center">

                                <svg class="w-4 h-4 text-blue-300"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M9 7h6m-6 4h6m-6 4h3m6 5H6a2 2 0 01-2-2V6a2 2 0 012-2h7.586a1 1 0 01.707.293l4.414 4.414A1 1 0 0119 6.414V18a2 2 0 01-2 2z"/>
                                </svg>

                            </div>

                            <div>
                                <h3 class="text-sm font-extrabold text-white">
                                    Ringkasan Biaya PO
                                </h3>

                                <p class="text-[10px] text-slate-400">
                                    Estimasi total pembelian.
                                </p>
                            </div>

                        </div>

                    </div>


                    <div class="py-8">

                        <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                            Total Estimasi Pembelian
                        </p>

                        <div class="mt-2 text-2xl sm:text-3xl font-black text-white">
                            <span class="text-base text-slate-400 mr-1">Rp</span>
                            <span x-text="formatRupiah(grandTotal)">0</span>
                        </div>

                    </div>


                    <div class="flex items-center justify-between pt-4 border-t border-white/10">

                        <span class="text-[11px] text-slate-400">
                            Jenis barang
                        </span>

                        <span class="px-2.5 py-1 rounded-full bg-white/10 text-white text-[10px] font-bold">
                            <span x-text="items.length">1</span> Item
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================= --}}
        {{-- ITEM PO --}}
        {{-- ========================================= --}}

        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden mb-5">

            {{-- HEADER --}}
            <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                <div class="flex items-center gap-3">

                    <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center">

                        <svg class="w-4 h-4 text-[#0B5CFF]"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7m16 0l-8 4m-8-4l8 4m0 0v10"/>
                        </svg>

                    </div>

                    <div>

                        <h3 class="text-sm font-extrabold text-slate-900">
                            Daftar Barang yang Dipesan
                        </h3>

                        <p class="text-[10px] text-slate-500">
                            Tambahkan produk, jumlah, dan harga beli satuan.
                        </p>

                    </div>

                </div>


                <button type="button"
                        @click="addItem()"
                        class="self-start sm:self-auto px-3.5 py-2 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5">

                    <svg class="w-3.5 h-3.5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>

                    Tambah Baris Produk

                </button>

            </div>


            {{-- TABLE --}}
            <div class="overflow-x-auto">

                <table class="w-full text-xs text-left min-w-[850px]">

                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">

                        <tr>

                            <th class="px-5 py-3.5">
                                Produk / Varian
                            </th>

                            <th class="px-5 py-3.5 text-center w-36">
                                Jumlah Pesan
                            </th>

                            <th class="px-5 py-3.5 text-right w-52">
                                Harga Beli Satuan
                            </th>

                            <th class="px-5 py-3.5 text-right w-44">
                                Subtotal
                            </th>

                            <th class="px-5 py-3.5 text-center w-16">
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        <template x-for="(item, index) in items" :key="index">

                            <tr class="hover:bg-slate-50/70 transition">

                                {{-- PRODUCT --}}
                                <td class="px-5 py-4">

                                    <select
                                        :name="'items[' + index + '][product_variant_id]'"
                                        x-model="item.product_variant_id"
                                        @change="updatePrice(item, $event)"
                                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#0B5CFF] focus:bg-white transition"
                                        required>

                                        <option value="">
                                            -- Pilih Produk / Varian --
                                        </option>

                                        @foreach($variants as $v)

                                            <option value="{{ $v->id }}"
                                                    data-cost="{{ $v->cost_price ?: $v->price * 0.8 }}">

                                                {{ $v->product->name ?? '' }}
                                                — {{ $v->name }}
                                                ({{ $v->sku }})

                                            </option>

                                        @endforeach

                                    </select>

                                </td>


                                {{-- QUANTITY --}}
                                <td class="px-5 py-4">

                                    <input type="number"
                                           :name="'items[' + index + '][quantity_ordered]'"
                                           x-model.number="item.quantity_ordered"
                                           min="1"
                                           class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-center focus:outline-none focus:border-[#0B5CFF] focus:bg-white transition"
                                           required>

                                </td>


                                {{-- UNIT COST --}}
                                <td class="px-5 py-4">

                                    <div class="relative">

                                        <span class="absolute left-3 top-2.5 text-[10px] font-bold text-slate-400">
                                            Rp
                                        </span>

                                        <input type="number"
                                               :name="'items[' + index + '][unit_cost]'"
                                               x-model.number="item.unit_cost"
                                               min="0"
                                               step="1000"
                                               class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-right focus:outline-none focus:border-[#0B5CFF] focus:bg-white transition"
                                               required>

                                    </div>

                                </td>


                                {{-- SUBTOTAL --}}
                                <td class="px-5 py-4 text-right">

                                    <div class="text-xs font-extrabold text-slate-900 whitespace-nowrap">
                                        Rp <span x-text="formatRupiah(item.quantity_ordered * item.unit_cost)">
                                            0
                                        </span>
                                    </div>

                                </td>


                                {{-- DELETE --}}
                                <td class="px-5 py-4 text-center">

                                    <button type="button"
                                            @click="removeItem(index)"
                                            x-show="items.length > 1"
                                            class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white transition inline-flex items-center justify-center"
                                            title="Hapus baris">

                                        <svg class="w-4 h-4"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M6 7h12M9 7V4h6v3m2 0v13H7V7m3 4v6m4-6v6"/>
                                        </svg>

                                    </button>

                                </td>

                            </tr>

                        </template>

                    </tbody>


                    {{-- TABLE FOOTER --}}
                    <tfoot class="bg-slate-50 border-t border-slate-200">

                        <tr>

                            <td colspan="3" class="px-5 py-4 text-right">

                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Total Estimasi
                                </span>

                            </td>

                            <td class="px-5 py-4 text-right">

                                <span class="text-sm font-black text-[#0B5CFF] whitespace-nowrap">
                                    Rp <span x-text="formatRupiah(grandTotal)">0</span>
                                </span>

                            </td>

                            <td></td>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>


        {{-- ========================================= --}}
        {{-- ACTION --}}
        {{-- ========================================= --}}

        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">

            <a href="{{ route('admin.pembelian.index') }}"
               class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition text-center">

                Batal

            </a>


            <button type="submit"
                    class="px-5 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center justify-center gap-1.5">

                <svg class="w-4 h-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M9 12l2 2 4-4m6.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.291 9 11.623C17.176 19.291 21 14.591 21 9c0-1.046-.133-2.061-.382-3.016z"/>
                </svg>

                Simpan Draft Purchase Order

            </button>

        </div>

    </form>

</div>


<script>
function poBuilder() {
    return {
        items: [
            {
                product_variant_id: '',
                quantity_ordered: 1,
                unit_cost: 0
            }
        ],

        addItem() {
            this.items.push({
                product_variant_id: '',
                quantity_ordered: 1,
                unit_cost: 0
            });
        },

        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },

        updatePrice(item, event) {
            const selectEl = event.target;
            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            const cost = selectedOpt.getAttribute('data-cost');

            if (cost) {
                item.unit_cost = parseFloat(cost);
            }
        },

        get grandTotal() {
            return this.items.reduce((sum, item) => {
                return sum +
                    ((item.quantity_ordered || 0) *
                    (item.unit_cost || 0));
            }, 0);
        },

        formatRupiah(num) {
            return new Intl.NumberFormat('id-ID').format(num || 0);
        }
    };
}
</script>

@endsection