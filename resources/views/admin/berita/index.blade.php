@extends('layouts.admin')

@section('title','Kelola Berita')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold">
            Kelola Berita
        </h3>

        <p class="text-muted mb-0">
            Daftar berita dan hasil analisis sentimen.
        </p>
    </div>

    <a href="{{ route('admin.berita.create') }}"
        class="btn btn-primary">

        <i class="bi bi-plus-circle"></i>
        Tambah Berita

    </a>

</div>


<div class="card shadow-sm">

<div class="card-body">


<table class="table table-hover">

<thead>

<tr>
    <th>No</th>
    <th>Judul</th>
    <th>Kategori</th>
    <th>Sentimen</th>
    <th>Tanggal</th>
    <th>Aksi</th>
</tr>

</thead>


<tbody>

@forelse($berita as $item)

<tr>

<td>
{{ $loop->iteration }}
</td>


<td>
{{ $item->judul }}
</td>


<td>
{{ $item->kategori ?? '-' }}
</td>


<td>

@if($item->label_sentimen == 'positif')

<span class="badge bg-success">
Positif
</span>

@elseif($item->label_sentimen == 'negatif')

<span class="badge bg-danger">
Negatif
</span>

@else

<span class="badge bg-secondary">
Netral
</span>

@endif

</td>


<td>
{{ $item->diterbitkan_pada }}
</td>


<td>

<a href="{{ route('admin.berita.edit',$item->id) }}"
    class="btn btn-warning btn-sm">

    <i class="bi bi-pencil"></i>
</a>


<form action="{{ route('admin.berita.destroy',$item->id) }}"
method="POST"
class="d-inline">

@csrf
@method('DELETE')

<button class="btn btn-danger btn-sm">
<i class="bi bi-trash"></i>
</button>

</form>


</td>


</tr>


@empty

<tr>
<td colspan="6" class="text-center">
Belum ada berita
</td>
</tr>

@endforelse


</tbody>


</table>


</div>

</div>


@endsection