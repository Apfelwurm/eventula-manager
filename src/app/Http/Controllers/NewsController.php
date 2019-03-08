<?php

namespace App\Http\Controllers;

use DB;
use Auth;

use App\NewsArticle;
use App\NewsTag;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class NewsController extends Controller
{
	/**
	 * Show News Index Page
	 * @return View
	 */
	public function index()
	{
		return view('news.index')->withNewsArticles(NewsArticle::all()->reverse());  
	}

	/**
	 * Show News Article Page
	 * @param  NewsArticle $news_article
	 * @return View      
	 */
	public function show(NewsArticle $news_article)
	{
		return view('news.show')->withNewsArticle($news_article);  
	}

	/**
	 * Show News Articles for Given Tag
	 * @param  NewsTag $news_tag
	 * @return View      
	 */
	public function showTag(NewsTag $news_tag)
	{
		foreach (NewsTag::where('tag', $news_tag->tag)->get()->reverse() as $news_tag) {
			$news_articles[] = $news_tag->newsArticle;
		}
		return view('news.tag')->withTag($news_tag->tag)->withNewsArticles($news_articles);  
	}

	/**
	 * Store News Article Comment
	 * @param  NewsArticle $news_article
	 * @param  Request $request
	 * @return View      
	 */
	public function storeComment(NewsArticle $news_article, Request $request)
	{
		if (!Auth::user()) {
			$request->session()->flash('alert-danger', 'Please Login.');
			return Redirect::to('login');
		}
		$rules = [
			'comment'		=> 'required|filled',
		];
		$messages = [
			'comment.required'		=> 'A Comment is required',
			'comment.filled'		=> 'Comment cannot be empty',
		];
		$this->validate($request, $rules, $messages);

		if (!$news_article->storeComment($request->comment, Auth::id())) {
			$request->session()->flash('alert-danger', 'Cannot post comment. Please try again.');
			return Redirect::back();
		}
		$request->session()->flash('alert-success', 'Comment Posted!');
		return Redirect::back();
	}
}
