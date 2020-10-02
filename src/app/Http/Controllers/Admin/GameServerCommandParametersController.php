<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Storage;
use Image;
use File;

use App\Game;
use App\GameServer;
use App\GameServerCommand;
use App\GameServerCommandParameter;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use xPaw\SourceQuery\SourceQuery;

class GameServerCommandParametersController extends Controller
{
    // /**
    //  * Show Games Index Page
    //  * @return Redirect
    //  */
    // public function index()
    // {
    //     return view('admin.games.index')
    //         ->withGames(Game::paginate(20));
    // }

    // /**
    //  * Show Game Page
    //  * @return Redirect
    //  */
    // public function show(Game $game)
    // {
    //     return view('admin.games.show')
    //         ->withGame($game);
    // }

    /**
     * Store GameServer to Database
     * @param  Request $request
     * @return Redirect
     */
    public function store(Game $game, Request $request)
    {
        $rules = [
            'name'              => 'required',
            'options'           => 'required'
        ];
        $messages = [
            'name.required'           => 'Parameter name is required',
            'options.required'        => 'Parameteroptions are required',
        ];

        $this->validate($request, $rules, $messages);

        $gameServerCommandParameter                 = new GameServerCommandParameter();
        $gameServerCommandParameter->name           = $request->name;
        $gameServerCommandParameter->game_id        = $game->id;
        $gameServerCommandParameter->options        = $request->options;

        if (!$gameServerCommandParameter->save()) {
            Session::flash('alert-danger', 'Could not save GameServerCommandParameter!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully saved GameServerCommandParameter!');
        return Redirect::back();
    }

    /**
     * Update Game
     * @param  GameServerCommand $gameServerCommand
     * @param  Request $request
     * @return Redirect
     */
    public function update(Game $game, GameServerCommand $gameServerCommand, Request $request)
    {
        // $rules = [
        //     'name'              => 'filled',
        //     'active'            => 'in:true,false',
        //     'image_header'      => 'image',
        //     'image_thumbnail'   => 'image',
        // ];
        // $messages = [
        //     'name.required'         => 'Game name is required',
        //     'active.filled'         => 'Active must be true or false',
        //     'image_header.image'    => 'Header image must be a Image',
        //     'image_thumbnail.image' => 'Thumbnail image must be a Image'
        // ];
        // $this->validate($request, $rules, $messages);

        // $game->name         = @$request->name;
        // $game->description  = @(trim($request->description) == '' ? null : $request->description);
        // $game->version      = @(trim($request->version) == '' ? null : $request->version);
        // $game->public       = @($request->public ? true : false);

        // if (!$game->save()) {
        //     Session::flash('alert-danger', 'Could not save Game!');
        //     return Redirect::back();
        // }

        // $destinationPath = '/storage/images/games/' . $game->slug . '/';
        
        // if ((Request::file('image_thumbnail') || Request::file('image_header')) &&
        //     !File::exists(public_path() . $destinationPath)
        // ) {
        //     File::makeDirectory(public_path() . $destinationPath, 0777, true);
        // }

        // if (Request::file('image_thumbnail')) {
        //     Storage::delete($game->image_thumbnail_path);
        //     $imageName  = 'thumbnail.' . Request::file('image_thumbnail')->getClientOriginalExtension();
        //     Image::make(Request::file('image_thumbnail'))
        //         ->resize(500, 500)
        //         ->save(public_path() . $destinationPath . $imageName)
        //     ;
        //     $game->image_thumbnail_path = $destinationPath . $imageName;
        //     if (!$game->save()) {
        //        Session::flash('alert-danger', 'Could not save Game thumbnail!');
        //         return Redirect::back();
        //     }
        // }

        // if (Request::file('image_header')) {
        //     Storage::delete($game->image_header_path);
        //     $imageName  = 'header.' . Request::file('image_header')->getClientOriginalExtension();
        //     Image::make(Request::file('image_header'))
        //         ->resize(1600, 400)
        //         ->save(public_path() . $destinationPath . $imageName)
        //     ;
        //     $game->image_header_path = $destinationPath . $imageName;
        //     if (!$game->save()) {
        //         Session::flash('alert-danger', 'Could not save Game Header!');
        //         return Redirect::back();
        //     }
        // }
        // Session::flash('alert-success', 'Successfully saved Game!');
        // return Redirect::to('admin/games/' . $game->slug);

        Session::flash('alert-danger', 'Could not save GameServer!');
        return Redirect::back();
   }

    /**
     * Delete GameServerCommandParameter from Database
     * @param  Game  $game
     * @param  GameServerCommandParameter  $gameServerCommandParameter
     * @return Redirect
     */
    public function destroy(Game $game, GameServerCommandParameter $gameServerCommandParameter)
    {
        if (!$gameServerCgameServerCommandParameterommand->delete()) {
            Session::flash('alert-danger', 'Cannot delete GameServerCommandParameter!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully deleted GameServerCommandParameter!');
        return Redirect::back();
    }
}
