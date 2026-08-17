<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Models\News;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class AdminNewsController extends Controller
{
    protected NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', News::class);

        $search = $request->input('search');
        $status = $request->input('status');

        $query = News::with('admin')->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('subjudul', 'like', '%' . $search . '%');
            });
        }

        if ($status && in_array($status, ['draft', 'published'])) {
            $query->where('status', $status);
        }

        $newsList = $query->paginate(10)->withQueryString();

        return view('admin.news.index', compact('newsList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', News::class);

        return view('admin.news.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request)
    {
        $this->authorize('create', News::class);

        $validated = $request->validated();

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('news', 'public');
            $validated['gambar'] = $path;
        }

        $this->newsService->create($validated, Auth::id());

        Session::flash('success', 'Berita berhasil dibuat dan disimpan.');
        return redirect()->route('admin.news.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $news = News::findOrFail($id);
        $this->authorize('update', $news);

        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, $id)
    {
        $news = News::findOrFail($id);
        $this->authorize('update', $news);

        $validated = $request->validated();

        if ($request->hasFile('gambar')) {
            // Hapus file gambar lama jika ada
            if ($news->gambar && Storage::disk('public')->exists($news->gambar)) {
                Storage::disk('public')->delete($news->gambar);
            }

            $path = $request->file('gambar')->store('news', 'public');
            $validated['gambar'] = $path;
        }

        $this->newsService->update($news, $validated);

        Session::flash('success', 'Berita berhasil diperbarui.');
        return redirect()->route('admin.news.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $this->authorize('delete', $news);

        $this->newsService->delete($news);

        Session::flash('success', 'Berita berhasil dihapus.');
        return redirect()->route('admin.news.index');
    }
}
