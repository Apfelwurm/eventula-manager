<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Storage;
use Input;
use Image;
use File;

use App\Game;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GamesController extends Controller
{
	/**
	 * Show Games Index Page
	 * @return Redirect
	 */
	public function index()
	{
		return view('admin.games.index')
			->withGames(Game::all());
	}

	/**
	 * Show Game Page
	 * @return Redirect
	 */
	public function show(Game $game)
	{
		return view('admin.games.show')
			->withGame($game);
	}

	/**
   	 * Store Game to Database
   	 * @param  Event   $event
   	 * @param  Request $request
   	 * @return Redirect
   	 */
	public function store(Request $request)
	{
		$rules = [
			'name'				=> 'required',
			'image_header'		=> 'image',
			'image_thumbnail'	=> 'image',
		];
		$messages = [
			'name.required'			=> 'Game name is required',
			'image_header.image'	=> 'Header image must be a Image',
			'image_thumbnail.image'	=> 'Thumbnail image must be a Image'
		];
		$this->validate($request, $rules, $messages);

		$game 				= new Game();
		$game->name 		= $request->name;
		$game->description 	= @(trim($request->description) == '' ? null : $request->description);
		$game->version 		= @(trim($request->version) == '' ? null : $request->version);
		$game->public 		= true;

		if (!$game->save()) {
			Session::flash('alert-danger', 'Could not save Game!'); 
			return Redirect::back();
		}

		$destinationPath = '/storage/images/games/' . $game->slug . '/';
		
		// TODO - refactor into model
		if ((Input::file('image_thumbnail') || Input::file('image_header')) && !File::exists(public_path() . $destinationPath)) {
		    File::makeDirectory(public_path() . $destinationPath, 0777, true);
		}

		if (Input::file('image_thumbnail')) {
			$imageName	= 'thumbnail.' . Input::file('image_thumbnail')->getClientOriginalExtension();
			Image::make(Input::file('image_thumbnail'))->resize(500, 500)->save(public_path() . $destinationPath . $imageName);
			$game->image_thumbnail_path = $destinationPath . $imageName;
			if (!$game->save()) {
				Session::flash('alert-danger', 'Could not save Game thumbnail!'); 
				return Redirect::back();
			}
		}

		if (Input::file('image_header')) {
			$imageName	= 'header.' . Input::file('image_header')->getClientOriginalExtension();
			Image::make(Input::file('image_header'))->resize(1600, 400)->save(public_path() . $destinationPath . $imageName);
			$game->image_header_path = $destinationPath . $imageName;
			if (!$game->save()) {
				Session::flash('alert-danger', 'Could not save Game Header!'); 
				return Redirect::back();
			}
		}
		Session::flash('alert-success', 'Successfully saved Game!');
		return Redirect::back();
	}

	/**
   	 * Update Game
   	 * @param  Event   $event
   	 * @param  Request $request
   	 * @return Redirect
   	 */
	public function update(Game $game, Request $request)
	{
		$rules = [
			'name'				=> 'filled',
			'active'			=> 'in:true,false',
			'image_header'		=> 'image',
			'image_thumbnail'	=> 'image',
		];
		$messages = [
			'name.required'			=> 'Game name is required',
			'active.filled'			=> 'Active must be true or false',
			'image_header.image'	=> 'Header image must be a Image',
			'image_thumbnail.image'	=> 'Thumbnail image must be a Image'
		];
		$this->validate($request, $rules, $messages);

		$game->name 		= @$request->name;
		$game->description 	= @(trim($request->description) == '' ? null : $request->description);
		$game->version 		= @(trim($request->version) == '' ? null : $request->version);
		$game->public 		= @($request->public ? true : false);

		if (!$game->save()) {
			Session::flash('alert-danger', 'Could not save Game!'); 
			return Redirect::back();
		}

		$destinationPath = '/storage/images/games/' . $game->slug . '/';
		
		// TODO - refactor into model
		if ((Input::file('image_thumbnail') || Input::file('image_header')) && !File::exists(public_path() . $destinationPath)) {
		    File::makeDirectory(public_path() . $destinationPath, 0777, true);
		}

		if (Input::file('image_thumbnail')) {
			Storage::delete($game->image_thumbnail_path);
			$imageName	= 'thumbnail.' . Input::file('image_thumbnail')->getClientOriginalExtension();
			Image::make(Input::file('image_thumbnail'))->resize(500, 500)->save(public_path() . $destinationPath . $imageName);
			$game->image_thumbnail_path = $destinationPath . $imageName;
			if (!$game->save()) {
				Session::flash('alert-danger', 'Could not save Game thumbnail!'); 
				return Redirect::back();
			}
		}

		if (Input::file('image_header')) {
			Storage::delete($game->image_header_path);
			$imageName	= 'header.' . Input::file('image_header')->getClientOriginalExtension();
			Image::make(Input::file('image_header'))->resize(1600, 400)->save(public_path() . $destinationPath . $imageName);
			$game->image_header_path = $destinationPath . $imageName;
			if (!$game->save()) {
				Session::flash('alert-danger', 'Could not save Game Header!'); 
				return Redirect::back();
			}
		}
		Session::flash('alert-success', 'Successfully saved Game!');
		return Redirect::to('admin/games/' . $game->slug);
	}

	/**
	 * Delete Game from Database
	 * @param  Game  $game
	 * @return Redirect
	 */
	public function destroy(Game $game)
	{
		if ($game->eventTournaments && !$game->eventTournaments->isEmpty()) {
			Session::flash('alert-danger', 'Cannot delete game with tournaments!');
			return Redirect::back();
		}

		if (!$game->delete()) {
			Session::flash('alert-danger', 'Cannot delete Game!');
			return Redirect::back();
		}

		Session::flash('alert-success', 'Successfully deleted Game!');
		return Redirect::to('admin/games/');
	}
}