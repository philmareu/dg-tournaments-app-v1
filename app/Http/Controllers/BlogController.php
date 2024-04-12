<?php

namespace DGTournaments\Http\Controllers;

use DGTournaments\Models\PostCategory;
use DGTournaments\Repositories\BlogRepository;
use Illuminate\Http\Request;

use DGTournaments\Http\Requests;
use DGTournaments\Http\Controllers\Controller;

class BlogController extends Controller
{
    protected $blogRepository;

    protected $category;

    public function __construct(BlogRepository $blogRepository, PostCategory $category)
    {
        $this->blogRepository = $blogRepository;
        $this->category = $category;
    }

    public function index()
    {
        $posts = $this->blogRepository->getPaginated();

        return view('pages.blog.index', compact('posts'));
    }

    public function show($year, $month, $day, $slug)
    {
        $post = $this->blogRepository->getPostForPage($year, $month, $day, $slug);

        if(is_null($post)) abort(404);

        return view('pages.blog.show', compact('post'));
    }

    public function category($category)
    {
        $posts = $this->blogRepository->getPostsByCategory($category);
        $category = $this->category->whereSlug($category)->first();

        return view('posts.category', compact('posts', 'category'));
    }

    public function archive($year, $month)
    {
        $posts = $this->blogRepository->getPostsByMonth($year, $month);

        return view('posts.archive', compact('posts', 'year', 'month'));
    }

    public function preview($id)
    {
        return view('pages.blog.show')
            ->with('post', $this->blogRepository->getForPreview($id));
    }
}