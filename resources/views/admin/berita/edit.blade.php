@extends('layouts.admin')

@section('title','Edit Berita')


@section('content')


<div class="card shadow-sm">

<div class="card-body">


<h4 class="fw-bold mb-4">
Edit Berita
</h4>



<form action="{{ route('admin.berita.update',$berita->id) }}"
method="POST">


@csrf
@method('PUT')



<div class="mb-3">

<label class="form-label">
Judul Berita
</label>

<input type="text"
name="judul"
class="form-control"
value="{{ $berita->judul }}">

</div>



<div class="mb-3">

<label class="form-label">
Deskripsi
</label>


<textarea
name="deskripsi"
class="form-control"
rows="5">{{ $berita->deskripsi }}</textarea>


</div>



<div class="mb-3">

<label>
URL
</label>


<input type="text"
name="url"
class="form-control"
value="{{ $berita->url }}">


</div>



<div class="mb-3">

<label>
Sumber
</label>


<input type="text"
name="sumber"
class="form-control"
value="{{ $berita->sumber }}">


</div>



<div class="mb-3">

<label>
Kategori
</label>


<select name="kategori"
class="form-select">


<option value="ekonomi"
{{ $berita->kategori=='ekonomi'?'selected':'' }}>
Ekonomi
</option>


<option value="logistik"
{{ $berita->kategori=='logistik'?'selected':'' }}>
Logistik
</option>


<option value="perdagangan"
{{ $berita->kategori=='perdagangan'?'selected':'' }}>
Perdagangan
</option>


<option value="pelayaran"
{{ $berita->kategori=='pelayaran'?'selected':'' }}>
Pelayaran
</option>


<option value="geopolitik"
{{ $berita->kategori=='geopolitik'?'selected':'' }}>
Geopolitik
</option>


<option value="lainnya"
{{ $berita->kategori=='lainnya'?'selected':'' }}>
Lainnya
</option>


</select>

</div>



<div class="mb-3">

<label>
Tanggal Terbit
</label>


<input type="date"
name="diterbitkan_pada"
class="form-control"
value="{{ $berita->diterbitkan_pada }}">


</div>



<button class="btn btn-primary">

<i class="bi bi-save"></i>
Update

</button>



<a href="{{ route('admin.berita.index') }}"
class="btn btn-secondary">

Kembali

</a>



</form>


</div>

</div>


@endsection