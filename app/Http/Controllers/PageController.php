<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function main()
    {
        $posts = Post::with('category')
            ->latest('id')
            ->take(4)
            ->get();

        $categories = Category::orderBy('name')->get();

        $testimonials = Comment::with(['user', 'post'])
            ->latest('id')
            ->take(6)
            ->get();

        return view('main', compact('posts', 'categories', 'testimonials'));
    }

    public function about()
    {
        $stats = [
            ['label' => 'Postlar', 'value' => Post::count()],
            ['label' => 'Kategoriyalar', 'value' => Category::count()],
            ['label' => 'Foydalanuvchilar', 'value' => User::count()],
            ['label' => 'Izohlar', 'value' => Comment::count()],
        ];

        $authors = User::with('roles')
            ->withCount('posts')
            ->where(function ($query) {
                $query->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['admin', 'editor', 'blogger']);
                })->orHas('posts');
            })
            ->orderByDesc('posts_count')
            ->orderBy('name')
            ->take(8)
            ->get();

        return view('about', compact('stats', 'authors'));
    }

    public function services(Request $request)
    {
        $categoryId = $request->query('category');
        $categoryId = $categoryId !== null ? filter_var($categoryId, FILTER_VALIDATE_INT) : null;

        if ($categoryId === false) {
            abort(404);
        }

        $categories = Category::orderBy('name')->get();

        if ($categoryId && ! $categories->contains('id', $categoryId)) {
            abort(404);
        }

        $search = trim($request->string('search')->toString());

        $posts = Post::with('category')
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($search !== '', function ($query) use ($search) {
                $like = '%'.Str::lower($search).'%';

                $query->where(function ($query) use ($like) {
                    $query->whereRaw('LOWER(title) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(short_content) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(content) LIKE ?', [$like])
                        ->orWhereHas('category', function ($query) use ($like) {
                            $query->whereRaw('LOWER(name) LIKE ?', [$like]);
                        });
                });
            })
            ->latest('id')
            ->paginate(6)
            ->withQueryString();

        return view('services', compact('posts', 'categories', 'categoryId', 'search'));
    }
}
