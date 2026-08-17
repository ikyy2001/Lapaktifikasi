@extends('layout')

@section('title', 'Kelola Berita & Informasi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold mb-1 text-dark">Kelola Berita & Informasi</h1>
            <p class="text-muted small mb-0">Manajemen konten artikel, pengumuman, dan berita terbaru platform.</p>
        </div>
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg mr-1"></i> Tulis Berita Baru
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <!-- Filter & Search Bar -->
            <form method="GET" action="{{ route('admin.news.index') }}" class="mb-4">
                <div class="row align-items-center">
                    <div class="col-md-5 mb-2 mb-md-0">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari judul atau subjudul berita..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Semua Status --</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    @if(request('search') || request('status'))
                    <div class="col-md-2">
                        <a href="{{ route('admin.news.index') }}" class="btn btn-light btn-sm text-secondary">
                            <i class="bi bi-x-circle mr-1"></i> Reset Filter
                        </a>
                    </div>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 80px;">Cover</th>
                            <th>Judul & Subjudul</th>
                            <th>Penulis</th>
                            <th>Status</th>
                            <th>Tanggal Publikasi</th>
                            <th class="text-center" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($newsList as $item)
                        <tr>
                            <td>
                                @if($item->gambar)
                                    <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="rounded shadow-sm" style="width: 60px; height: 45px; object-fit: cover;">
                                @else
                                    <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded" style="width: 60px; height: 45px; font-size: 1.2rem;">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                                    {{ $item->judul }}
                                </div>
                                @if($item->subjudul)
                                    <div class="text-muted small text-truncate" style="max-width: 320px;">
                                        {{ $item->subjudul }}
                                    </div>
                                @endif
                                <div class="small text-muted font-monospace mt-1">
                                    <i class="bi bi-link-45deg"></i> /berita/{{ $item->slug }}
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-light border">
                                    <i class="bi bi-person mr-1"></i> {{ $item->admin?->name ?? 'Admin' }}
                                </span>
                            </td>
                            <td>
                                @if($item->status === 'published')
                                    <span class="badge badge-success px-2 py-1">
                                        <i class="bi bi-check-circle mr-1"></i> Published
                                    </span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1">
                                        <i class="bi bi-file-earmark mr-1"></i> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="small">
                                @if($item->published_at)
                                    <div class="font-weight-bold text-dark">{{ $item->published_at->format('d M Y') }}</div>
                                    <div class="text-muted">{{ $item->published_at->format('H:i') }} WIB</div>
                                @else
                                    <span class="text-muted italic">- Belum Publish -</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Berita">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Berita">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-newspaper display-4 d-block mb-3 text-muted" style="opacity: 0.4;"></i>
                                <h6 class="font-weight-bold">Belum Ada Berita</h6>
                                <p class="small mb-3">Mulai tulis berita atau pengumuman pertama Anda untuk para pengguna.</p>
                                <a href="{{ route('admin.news.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg mr-1"></i> Tulis Berita
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Menampilkan {{ $newsList->firstItem() ?? 0 }} - {{ $newsList->lastItem() ?? 0 }} dari total {{ $newsList->total() }} berita
                </div>
                <div>
                    {{ $newsList->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
