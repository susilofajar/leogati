@extends('layouts.admin')

@section('header_title', 'Jejak Audit Keamanan')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Jejak Audit Keamanan & Aktivitas</h2>
            <p class="text-xs text-slate-500 mt-1">Pencatatan lengkap perubahan status pesanan, penyesuaian stok, pengolahan klaim garansi, dan aktivitas staf.</p>
        </div>
    </div>

    {{-- FILTER FORM --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-5">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-slate-600 mb-1">Pencarian Kata Kunci</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari aksi, user, IP, atau metadata..."
                    class="w-full border border-slate-200 rounded-xl text-xs px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0B5CFF]/30">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Tipe Aksi</label>
                <select name="action" class="border border-slate-200 rounded-xl text-xs px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0B5CFF]/30">
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" @selected(request('action') === $act)>{{ $act }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Pengguna</label>
                <select name="user_id" class="border border-slate-200 rounded-xl text-xs px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0B5CFF]/30">
                    <option value="">Semua Pengguna</option>
                    @foreach($staffUsers as $u)
                        <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-5 py-2 bg-[#0B5CFF] text-white rounded-xl text-xs font-bold hover:bg-[#063B9E] transition">
                Cari Log
            </button>
            <a href="{{ route('admin.audit_log.index') }}" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition">
                Reset
            </a>
        </form>
    </div>

    {{-- TABEL AUDIT LOG --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4 text-left">Waktu (WIB)</th>
                        <th class="py-3 px-4 text-left">Pelaksana</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                        <th class="py-3 px-4 text-left">Target</th>
                        <th class="py-3 px-4 text-left">Detail Metadata</th>
                        <th class="py-3 px-4 text-left">IP & Agent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3 px-4 text-slate-500 font-mono whitespace-nowrap">
                                {{ tgl_indo($log->created_at) }}<br>
                                <span class="text-[10px] text-slate-400">{{ $log->created_at->format('H:i:s') }} WIB</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900">{{ $log->user_name }}</div>
                                @if($log->user)
                                    <span class="text-[10px] text-slate-400">{{ $log->user->email }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span @class([
                                    'inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold font-mono',
                                    'bg-blue-100 text-blue-800' => str_contains($log->action, 'order'),
                                    'bg-amber-100 text-amber-800' => str_contains($log->action, 'stock'),
                                    'bg-purple-100 text-purple-800' => str_contains($log->action, 'warranty'),
                                    'bg-emerald-100 text-emerald-800' => str_contains($log->action, 'product'),
                                    'bg-slate-100 text-slate-700' => !str_contains($log->action, 'order') && !str_contains($log->action, 'stock') && !str_contains($log->action, 'warranty'),
                                ])>
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-700">
                                @if($log->target_type)
                                    <span class="font-bold">{{ class_basename($log->target_type) }}</span>
                                    <span class="text-slate-400 font-mono">#{{ $log->target_id }}</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($log->payload)
                                    <div class="max-w-[280px] bg-slate-50 border border-slate-200 rounded-lg p-2 font-mono text-[10px] text-slate-600 space-y-0.5 overflow-hidden">
                                        @foreach($log->payload as $k => $v)
                                            <div class="truncate"><strong class="text-slate-800">{{ $k }}:</strong> {{ is_array($v) ? json_encode($v) : $v }}</div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-500 font-mono text-[10px]">
                                <div>{{ $log->ip_address }}</div>
                                <div class="text-[9px] text-slate-400 truncate max-w-[120px]" title="{{ $log->user_agent }}">{{ $log->user_agent }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-400">
                                Belum ada catatan jejak audit yang terekam.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
