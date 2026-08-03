<?php

namespace App\Observers;

use App\Models\Review;
use App\Models\Toko;

class ReviewObserver
{
    /**
     * Handle the Review "created" event.
     */
    public function created(Review $review): void
    {
        $this->syncTokoRating($review->id_toko);
    }

    /**
     * Handle the Review "updated" event.
     */
    public function updated(Review $review): void
    {
        $this->syncTokoRating($review->id_toko);
    }

    /**
     * Handle the Review "deleted" event.
     */
    public function deleted(Review $review): void
    {
        $this->syncTokoRating($review->id_toko);
    }

    /**
     * Recalculate and update Toko average rating and total review count.
     */
    private function syncTokoRating(int $idToko): void
    {
        $avgRating = Review::where('id_toko', $idToko)->avg('rating');
        $countReviews = Review::where('id_toko', $idToko)->count();

        Toko::where('id_toko', $idToko)->update([
            'rating_rata_rata' => round($avgRating ?? 0, 2),
            'jumlah_review' => $countReviews,
        ]);
    }
}
