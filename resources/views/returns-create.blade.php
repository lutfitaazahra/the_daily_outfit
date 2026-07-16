@extends('layouts.app')

@section('title', 'Ajukan Retur')

@section('content')
<div style="max-width:600px; margin:2rem auto; padding:0 1rem;">
    <h1 style="font-size:22px; margin-bottom:1.5rem;">Ajukan Retur — {{ $order->order_number }}</h1>

    @if($errors->any())
    <div style="background:#fef2f2; color:#991b1b; border:1px solid #fecaca; padding:12px 16px; border-radius:10px; margin-bottom:1.5rem; font-size:14px;">
        @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('returns.store', $order->id) }}" enctype="multipart/form-data"
          style="background:white; border-radius:16px; padding:1.5rem; box-shadow:0 2px 12px rgba(0,0,0,0.04);">
        @csrf

        <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Alasan Retur</label>
        <textarea name="reason" required minlength="10" maxlength="1000" rows="4"
                  placeholder="Jelaskan alasan kamu mengajukan retur..."
                  style="width:100%; padding:10px 14px; border:1.5px solid #f3d9e0; border-radius:10px; margin-bottom:1rem;">{{ old('reason') }}</textarea>

        <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Foto Bukti (opsional)</label>
        <input type="file" name="proof_image" accept="image/*" style="margin-bottom:1.5rem;">

        <button type="submit" class="abtn abtn-pink" style="width:100%; justify-content:center; padding:12px;">
            Kirim Pengajuan Retur
        </button>
    </form>
</div>
@endsection