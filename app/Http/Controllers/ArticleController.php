<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('kirim', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'cropped_image' => 'nullable|string'
        ]);

        $imagePath = null;

        // Handle cropped image (base64 from client)
        if ($request->cropped_image) {
            try {
                $imageData = $request->cropped_image;
                
                if (strpos($imageData, ',') !== false) {
                    list($type, $imageData) = explode(',', $imageData);
                    $imageData = base64_decode($imageData);
                } else {
                    $imageData = base64_decode($imageData);
                }

                $fileName = 'articles/' . uniqid() . '_' . time() . '.png';
                Storage::put($fileName, $imageData);
                $imagePath = $fileName;
            } catch (\Exception $e) {
                Log::error('Image crop error: ' . $e->getMessage());
            }
        }
        elseif ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles');
        }

        Article::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'status' => 'pending',
            'image' => $imagePath,
        ]);

        return redirect()->route('home')
            ->with('success', 'Berita berhasil dikirim dan menunggu persetujuan admin.');
    }

    public function myArticles()
    {
        $articles = Article::where('user_id', auth()->id())
            ->with('category')
            ->latest()
            ->paginate(10);
            
        return view('my-articles', compact('articles'));
    }

    public function adminOverview()
    {
        $stats = [
            'total_articles' => Article::count(),
            'published_articles' => Article::where('status', 'approved')->count(),
            'pending_articles' => Article::where('status', 'pending')->count(),
            'total_users' => \App\Models\User::count(),
            'total_likes' => \App\Models\ArticleLike::count(),
            'total_views' => Article::sum('view_count'),
        ];
        
        $recentArticles = Article::with('user', 'category')->latest()->take(5)->get();
        $popularArticles = Article::with('user', 'category')->orderByDesc('view_count')->take(5)->get();

        return view('admin.overview', compact('stats', 'recentArticles', 'popularArticles'));
    }

    public function admin()
    {
        $articles = Article::where('status', 'pending')->latest()->get();
        return view('admin', compact('articles'));
    }

    public function approve($id)
    {
        $article = Article::findOrFail($id);
        $article->status = 'approved';
        $article->save();

        \App\Models\Notification::create([
            'user_id' => $article->user_id,
            'type' => 'article_approved',
            'message' => 'Berita Anda berjudul "' . $article->title . '" telah disetujui dan tayang.',
            'link' => route('artikel.show', $article->id),
        ]);

        return redirect()->route('admin.verifikasi')
            ->with('success', 'Berita berhasil di-approve.');
    }

    public function reject($id)
    {
        $article = Article::findOrFail($id);
        $article->status = 'trashed';
        $article->trashed_reason = 'rejected';
        $article->save();

        \App\Models\Notification::create([
            'user_id' => $article->user_id,
            'type' => 'article_rejected',
            'message' => 'Berita Anda berjudul "' . $article->title . '" ditolak oleh admin.',
            'link' => route('artikel.my-articles'),
        ]);

        return redirect()->route('admin.verifikasi')
            ->with('success', 'Berita dipindahkan ke Trash.');
    }
    
    public function show($id)
    {
        $article = Article::with('category', 'likes')->findOrFail($id);

        if ($article->status === 'pending' || $article->status === 'rejected') {
            if (!auth()->check() || (auth()->user()->id !== $article->user_id && !auth()->user()->is_admin)) {
                abort(403, 'Unauthorized');
            }
        }

        // Increment view count using increment method to prevent race conditions
        $article->increment('view_count');

        return view('detail', compact('article'));
    }

    public function published()
    {
        $articles = Article::where('status', 'approved')
            ->with(['category', 'user'])
            ->latest()
            ->get()
            ->groupBy(function($article) {
                return $article->category ? $article->category->name : 'Tanpa Kategori';
            });
            
        return view('admin.published', compact('articles'));
    }

    public function unpublish($id)
    {
        $article = Article::findOrFail($id);
        $article->status = 'unpublished';
        $article->save();

        \App\Models\Notification::create([
            'user_id' => $article->user_id,
            'type' => 'article_unpublished',
            'message' => 'Berita Anda berjudul "' . $article->title . '" diturunkan oleh admin.',
            'link' => route('artikel.my-articles'),
        ]);

        return redirect()->route('admin.published')
            ->with('success', 'Berita berhasil diturunkan.');
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->status = 'trashed';
        $article->trashed_reason = 'deleted';
        $article->save();

        return redirect()->back()
            ->with('success', 'Berita dipindahkan ke Trash.');
    }

    public function setFeatured($id)
    {
        Article::where('featured', true)->update(['featured' => false]);

        $article = Article::findOrFail($id);
        $article->featured = true;
        $article->save();

        return redirect()->route('admin.published')
            ->with('success', 'Artikel diset sebagai featured.');
    }

    public function toggleSpotlight($id)
    {
        $article = Article::findOrFail($id);
        $article->spotlight = ! $article->spotlight;
        $article->save();

        return redirect()->route('admin.published')
            ->with('success', 'Spotlight artikel diperbarui.');
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        $categories = Category::all();

        return view('admin.edit', compact('article', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'cropped_image' => 'nullable|string'
        ]);

        if ($request->cropped_image) {
            try {
                $imageData = $request->cropped_image;
                
                if (strpos($imageData, ',') !== false) {
                    list($type, $imageData) = explode(',', $imageData);
                    $imageData = base64_decode($imageData);
                } else {
                    $imageData = base64_decode($imageData);
                }

                $fileName = 'articles/' . uniqid() . '_' . time() . '.png';
                Storage::put($fileName, $imageData);
                $article->image = $fileName;
            } catch (\Exception $e) {
                Log::error('Image crop error: ' . $e->getMessage());
            }
        }

        $article->title = $request->title;
        $article->content = $request->content;
        $article->category_id = $request->category_id;
        $article->save();

        return redirect()->back()->with('success', 'Artikel berhasil diperbarui.');
    }

    public function unpublished()
    {
        $articles = Article::where('status', 'unpublished')->latest()->get();
        return view('admin.unpublished', compact('articles'));
    }

    public function republish($id)
    {
        $article = Article::findOrFail($id);
        $article->status = 'approved';
        $article->save();

        return redirect()->route('admin.unpublished')
            ->with('success', 'Berita berhasil dipublikasikan kembali.');
    }

    public function trash()
    {
        $articles = Article::where('status', 'trashed')->latest()->get();
        return view('admin.trash', compact('articles'));
    }

    public function restore($id)
    {
        $article = Article::findOrFail($id);
        
        if ($article->trashed_reason === 'rejected') {
            $article->status = 'pending';
        } else {
            $article->status = 'approved';
        }
        
        $article->trashed_reason = null;
        $article->save();

        return redirect()->route('admin.trash')
            ->with('success', 'Artikel berhasil dipulihkan.');
    }

    public function permanentDelete($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return redirect()->route('admin.trash')
            ->with('success', 'Artikel berhasil dihapus secara permanen.');
    }

    public function bulkTrash(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Article::whereIn('id', $request->ids)->update([
            'status' => 'trashed',
            'trashed_reason' => 'deleted',
        ]);
        return redirect()->back()->with('success', count($request->ids) . ' berita dipindahkan ke Trash.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        $articles = Article::whereIn('id', $request->ids)->get();
        foreach($articles as $article) {
            if ($article->image) {
                Storage::delete($article->image);
            }
            $article->delete();
        }
        return redirect()->back()->with('success', count($request->ids) . ' berita dihapus permanen.');
    }

    // Like/Unlike toggle
    public function like($id)
    {
        $article = Article::findOrFail($id);
        $user = auth()->user();

        $existing = ArticleLike::where('user_id', $user->id)
                                ->where('article_id', $article->id)
                                ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            ArticleLike::create([
                'user_id' => $user->id,
                'article_id' => $article->id,
            ]);
            $liked = true;

            // Notify author if liked by someone else
            if ($user->id !== $article->user_id) {
                \App\Models\Notification::create([
                    'user_id' => $article->user_id,
                    'type' => 'new_like',
                    'message' => $user->name . ' menyukai berita Anda "' . $article->title . '".',
                    'link' => route('artikel.show', $article->id),
                ]);
            }
        }

        return response()->json([
            'liked' => $liked,
            'count' => $article->likes()->count(),
        ]);
    }
}