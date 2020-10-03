<?php

namespace App\Http\Controllers\Admin\Events;

use DB;
use Auth;
use Session;
use Storage;
use Input;
use Image;
use File;
use Helpers;

use App\Event;
use App\EventTournament;
use App\EventTournamentServer;
use App\Game;
use App\GameServer;
use App\GameServerCommand;

use App\Http\Requests;
use App\Http\Controllers\Controller;    

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class TournamentsServerController extends Controller
{
 
    /**
     * Store TournamentsServer to Database
     * @param  Event            $event
     * @param  EventTournament  $tournament
     * @param  GameServer $gameServer
     * @param  Request $request
     * @return Redirect
     */
    public function store(Event $event, EventTournament $tournament, int $challongeMatchId, Request $request)
    {
        $tournamentServer                 = new EventTournamentServer();
        $tournamentServer->challonge_match_id        = $challongeMatchId;
        $tournamentServer->game_server_id = $request->gameServer;
        
        if (!$tournamentServer->save()) {
            Session::flash('alert-danger', 'Could not save tournamentServer!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully saved tournamentServer!');
        return Redirect::back();
    }


    // /**
    //  * update TournamentsServer to Database
    //  * @param  Event            $event
    //  * @param  EventTournament  $tournament
    //  * @param  GameServer $gameServer
    //  * @param  Request $request
    //  * @return Redirect
    //  */
    // public function update(Event $event, EventTournament $tournament, GameServer $gameServer, Request $request)
    // {
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

//         Session::flash('alert-danger', 'Could not save tournamentServer!');
//         return Redirect::back();
//    }

    // /**
    //  * Store TournamentsServer to Database
    //  * @param  Event            $event
    //  * @param  EventTournament  $tournament
    //  * @param  GameServer $gameServer
    //  * @param  Request $request
    //  * @return Redirect
    //  */
    // public function destroy(Event $event, EventTournament $tournament, GameServer $gameServer, Request $request)
    // {
    //     // if ($game->eventTournaments && !$game->eventTournaments->isEmpty()) {
    //     //     Session::flash('alert-danger', 'Cannot delete game with tournaments!');
    //     //     return Redirect::back();
    //     // }

    //     // if (!$tournamentServer->delete()) {
    //     //     Session::flash('alert-danger', 'Cannot delete tournamentServer!');
    //     //     return Redirect::back();
    //     // }

    //     Session::flash('alert-success', 'Successfully deleted tournamentServer!');
    //     return Redirect::back();
    // }

   
}
