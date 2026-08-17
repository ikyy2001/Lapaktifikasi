<?php

namespace App\Http\Controllers;

use App\Services\NewsService;
use Illuminate\Http\Request;

class CustomerNewsController extends Controller
{
    protected NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * Display a listing of the published news.
     */
    public function index()
    {
        $newsList = $this->newsService->getPublishedList(12);

        return view('customer.news.index', compact('newsList'));
    }

    /**
     * Display the specified published news detail.
     */
    public function show(string $slug)
    {
        $news = $this->newsService->getBySlug($slug);

        return view('customer.news.show', compact('news'));
    }
}
