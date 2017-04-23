<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Http\Requests\ArticleRequest;
use Carbon\Carbon;

class ArticlesController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function index()
    {
      $articles = Article::latest('published_at')->get();
      return view('articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
      // dd($article);
      // $article = Article::findOrFail($id);
      //dd($article->published_at->diffForHumans());
      return view('articles.show', compact('article'));
    }

    public function create()
    {
      $tags = \App\Tag::pluck('name', 'id');
      return view('articles.create', compact('tags'));
    }

    public function store(ArticleRequest $request)
    {
      // $article = new Article($request->all());
      // \Auth::user()->articles()->save($article);
      $article = \Auth::user()->articles()->create($request->all());

      $article->tags()->attach($request->input('tag_list'));

      \Session::flash('flash_message', 'Your article has been created');

      return redirect('articles');
    }

    public function edit(Article $article)
    {
      // $article = Article::findOrFail($id);
      $tags = \App\Tag::pluck('name', 'id');

      $tagList = $article->tags->pluck('id')->toArray();

      //dd($tagList);

      return view('articles.edit', compact('article', 'tags', 'tagList'));
    }

    public function update(ArticleRequest $request, $id)
    {
      $article = Article::findOrFail($id);
      $article->update($request->all());

      return redirect('articles');
    }
}
