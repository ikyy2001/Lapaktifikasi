<?php

namespace App\Services;

use App\Models\News;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class NewsService
{
    /**
     * Ambil daftar berita yang sudah dipublikasikan (paginated).
     * Hanya select kolom yang dibutuhkan untuk listing tampilan kartu.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPublishedList(int $perPage = 12): LengthAwarePaginator
    {
        return News::select(['id', 'judul', 'slug', 'subjudul', 'gambar', 'published_at'])
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Ambil detail berita berdasarkan slug dengan status published.
     * Eager load relasi admin (hanya id dan name untuk menghindari N+1 query).
     *
     * @param string $slug
     * @return News
     */
    public function getBySlug(string $slug): News
    {
        return News::published()
            ->with(['admin' => function ($query) {
                $query->select('id', 'name');
            }])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /**
     * Simpan berita baru ke database.
     * Generate slug unik (cek collision), set admin_id, dan set published_at jika status published.
     *
     * @param array $data
     * @param int $adminId
     * @return News
     */
    public function create(array $data, int $adminId): News
    {
        $data['admin_id'] = $adminId;

        // Generate unique slug dari judul berita
        $slug = Str::slug($data['judul'] ?? '');
        $originalSlug = $slug;
        $counter = 1;
        while (News::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        $data['slug'] = $slug;

        // Set published_at = now() jika status published dan published_at belum ditentukan
        if (($data['status'] ?? 'draft') === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return News::create($data);
    }

    /**
     * Perbarui data berita.
     * Menangani perubahan status draft -> published (set published_at jika belum ada).
     *
     * @param News $news
     * @param array $data
     * @return News
     */
    public function update(News $news, array $data): News
    {
        // Regenerate unique slug jika judul diubah dan slug tidak diinputkan manual
        if (isset($data['judul']) && !isset($data['slug']) && $data['judul'] !== $news->judul) {
            $slug = Str::slug($data['judul']);
            $originalSlug = $slug;
            $counter = 1;
            while (News::withTrashed()->where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }
            $data['slug'] = $slug;
        }

        // Handle perubahan status menjadi published
        if (isset($data['status']) && $data['status'] === 'published') {
            if (!$news->published_at && empty($data['published_at'])) {
                $data['published_at'] = now();
            }
        }

        $news->update($data);

        return $news->fresh();
    }

    /**
     * Hapus berita (soft delete).
     *
     * @param News $news
     * @return bool
     */
    public function delete(News $news): bool
    {
        return (bool) $news->delete();
    }
}
