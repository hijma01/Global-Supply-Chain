@extends('layouts.admin')

@section('title','Tambah Berita')


@section('content')


<div class="card shadow-sm">


<div class="card-body">


<h4 class="fw-bold mb-4">
Tambah Berita
</h4>



<form action="{{ route('berita.store') }}"
method="POST">


@csrf



<div class="mb-3">

<label class="form-label">
Judul Berita
</label>

<input type="text"
name="judul"
class="form-control"
required>

</div>



<div class="mb-3">

<label class="form-label">
Deskripsi
</label>

<textarea 
name="deskripsi"
class="form-control"
rows="5"></textarea>

</div>



<div class="mb-3">

<label class="form-label">
URL Berita
</label>

<input type="text"
name="url"
class="form-control"
required>

</div>



<div class="mb-3">

<label class="form-label">
Sumber
</label>

<input type="text"
name="sumber"
class="form-control">

</div>



<div class="mb-3">

<label class="form-label">
Kategori
</label>


<select name="kategori"
class="form-select">


<option value="">
Pilih kategori
</option>


<option value="ekonomi">
Ekonomi
</option>


<option value="logistik">
Logistik
</option>


<option value="perdagangan">
Perdagangan
</option>


<option value="pelayaran">
Pelayaran
</option>


<option value="geopolitik">
Geopolitik
</option>


<option value="lainnya">
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
class="form-control">


</div>



<button class="btn btn-primary">

<i class="bi bi-save"></i>

Simpan Berita

</button>



<a href="{{ route('admin.berita.index') }}"
class="btn btn-secondary">

Kembali

</a>



</form>


</div>


</div>


@endsection