<?php

namespace App\Http\Controllers;

use App\Article;
use App\Tag;
use App\Http\Requests\ArticleRequest;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Article::class, 'article');
    }
    /**
    * BASSER一覧画面
    * /
    */
    public function index()
    {
        // ログインユーザー取得
        $user = auth()->user();
        // 記事一覧取得
        $articles = Article::all()->sortByDesc('created_at')
            ->load(['user', 'likes', 'tags']);

        return view('articles.index',
            [
                'user' => $user,
                'articles' => $articles,
            ]);
    }

    /**
    * 新規記事作成画面
    * /articles/create
    */
    public function create()
    {
        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return view('articles.create', [
            'allTagNames' => $allTagNames,
        ]);
    }

    /** 
    * 新規記事登録処理
    * /articles
    */
    public function store(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all());
        $article->user_id = $request->user()->id;
        $article->save();
        
        $request->tags->each(function ($tagName) use ($article) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $article->tags()->attach($tag);
        });

        return redirect()->route('articles.index');
    }

    /**
    * 記事再編集画面
    * /articles/{article}/edit
    */
    public function edit(Article $article)
    {
        $tagNames = $article->tags->map(function ($tag) {
            return ['text' => $tag->name];
        });

        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return view('articles.edit', [
            'article' => $article,
            'tagNames' => $tagNames,
            'allTagNames' =>$allTagNames,
        ]);
    }

    /**
     * 記事更新処理
     * /articles/{article}/update
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all())->save();

        $article->tags()->detach();
        $request->tags->each(function ($tagName) use ($article) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $article->tags()->attach($tag);
        });

        return redirect()->route('articles.index');
    }

    /**
     * 記事削除処理
     * /articles/{article}/destroy
     */
    public function destroy(Article $article)
    {
        $article->delete();
        
        return redirect()->route('articles.index');
    }

    /**
     * 記事1件詳細表示
     * /articles/{article}
     */
    public function show(Article $article)
    {
        return view('articles.show', 
        [
            'article' => $article,
        ]);
    }

    /**
     * いいね機能
     * /articles/{article}/like
     */
    public function like(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);
        $article->likes()->attach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }

    /**
     * いいね解除機能
     * /articles/{article}/unlike
     */
    public function unlike(REquest $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }
}
