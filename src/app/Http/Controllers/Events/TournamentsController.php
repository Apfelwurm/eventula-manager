<?php

namespace App\Http\Controllers\Events;

use DB;
use Auth;
use Session;
use DateTime;

use App\User;
use App\Event;
use App\EventParticipant;
use App\EventTournament;
use App\EventTournamentParticipant;
use App\EventTournamentTeam;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class TournamentsController extends Controller
{
    /**
     * Show Tournaments
     * @param  Event $event
     * @return Tournaments
     */
    public function index(Event $event)
    {
        return $event->tournaments;
    }

    /**
     * Show Tournaments Page
     * @param  Event            $event
     * @param  EventTournament  $tournament
     * @param  Request          $request
     * @return View
     */
    public function show(Event $event, EventTournament $tournament, Request $request)
    {
        if (!$user = Auth::user()) {
            Redirect::to('/');
        }

        if (!empty($user)) {
            if ($event->only_signedin)
            {
                $user->setActiveEventParticipantSignedIn($event->id);
            }
            else
            {
                $user->setActiveEventParticipant($event->id);
            }
            
        }

        return view('events.tournaments.show')
            ->withTournament($tournament)
            ->withEvent($event)
            ->withUser($user)
        ;
    }

    public function matchConfig(Event $event, EventTournament $tournament, int $challongeMatchId){
        $match = $tournament->getMatch($challongeMatchId);
        if(!$match)
        {
            return "No Match found for $challongeMatchId";
        }

        $team1 = $tournament->getTeamByChallongeId($match->player1_id);
        $team2 = $tournament->getTeamByChallongeId($match->player2_id);

        $result = new \stdClass();
        $result->matchid = "Match $challongeMatchId";
        $result->num_maps = 1;
        $result->players_per_team = 1;
        $result->min_players_to_ready= 1;
        $result->min_spectators_to_ready= 0;
        $result->skip_veto= false;
        $result->veto_first= "team1";
        $result->side_type= "standard";
        $result->maplist = array("de_cache",
                                "de_dust2",
                                "de_inferno",
                                "de_mirage",
                                "de_nuke",
                                "de_overpass",
                                "de_train");
        $result->team1 = new \stdClass();
        $result->team1->name = $team1->name;
        $result->team1->tag = $team1->name;
        $result->team1->flag = "DE";
        $result->team1->players = new \stdClass();
        foreach ($team1->tournamentParticipants as $key => $team1Participant)
        {
            $eventParticipant = $team1Participant->eventParticipant;
            $user = $eventParticipant->user;
            $result->team1->players->{$user->steamid} = $user->steamname;
        }

        $result->team2 = new \stdClass();
        $result->team2->name = $team2->name;
        $result->team2->tag = $team2->name;
        $result->team2->flag = "DE";
        $result->team2->players = new \stdClass();
        foreach ($team2->tournamentParticipants as $key => $team2Participants)
        {
            $eventParticipant = $team2Participants->eventParticipant;
            $user = $eventParticipant->user;
            $result->team2->players->{$user->steamid} = $user->steamname;
        }

        $result->cvars = new \stdClass();
        $result->cvars->hostname = "lan2play";

        return response()->json($result);
    }

    /**
     * Register to Tournament
     * @param  Event           $event
     * @param  EventTournament $tournament
     * @param  Request         $request
     * @return [type]
     */
    public function registerSingle(Event $event, EventTournament $tournament, Request $request)
    {
        if ($tournament->status != 'OPEN') {
            Session::flash('alert-danger', 'Signups not permitted at this time.');
            return Redirect::back();
        }
     
        if (!$tournament->event->eventParticipants()->where('id', $request->event_participant_id)->first()) {
            Session::flash('alert-danger', 'You are not signed in to this event.');
            return Redirect::back();
        }

        if ($tournament->getParticipant($request->event_participant_id)) {
            Session::flash('alert-danger', 'You are already signed up to this tournament.');
            return Redirect::back();
        }

        if (isset($request->event_tournament_team_id) &&
            $tournamentTeam = $tournament->tournamentTeams()->where('id', $request->event_tournament_team_id)->first()
        ) {
            if ($tournamentTeam->tournamentParticipants->count() == substr($tournament->team_size, 0, 1)) {
                Session::flash('alert-danger', 'This team is full.');
                return Redirect::back();
            }
        }

        // TODO - Refactor
        $tournamentParticipant                              = new EventTournamentParticipant();
        $tournamentParticipant->event_participant_id        = $request->event_participant_id;
        $tournamentParticipant->event_tournament_id         = $tournament->id;
        $tournamentParticipant->event_tournament_team_id    = @$request->event_tournament_team_id;

        if (!$tournamentParticipant->save()) {
            Session::flash('alert-danger', 'Cannot add participant. Please try again.');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully Registered!');
        return Redirect::back();
    }

    /**
     * Register Team to Tournament
     * @param  Event           $event
     * @param  EventTournament $tournament
     * @param  Request         $request
     * @return Redirect
     */
    public function registerTeam(Event $event, EventTournament $tournament, Request $request)
    {
        if ($tournament->status != 'OPEN') {
            Session::flash('alert-danger', 'Signups not permitted at this time.');
            return Redirect::back();
        }
     
        if (!$tournament->event->eventParticipants()->where('id', $request->event_participant_id)->first()) {
            Session::flash('alert-danger', 'You are not signed in to this event.');
            return Redirect::back();
        }

        if ($tournament->getParticipant($request->event_participant_id)) {
            Session::flash('alert-danger', 'You are already signed up to this tournament.');
            return Redirect::back();
        }

        $tournamentTeam                         = new EventTournamentTeam();
        $tournamentTeam->event_tournament_id    = $tournament->id;
        $tournamentTeam->name                   = $request->team_name;

        if (!$tournamentTeam->save()) {
            Session::flash('alert-danger', 'Cannot add Team. Please try again.');
            return Redirect::back();
        }

        // TODO - Refactor
        $tournamentParticipant                              = new EventTournamentParticipant();
        $tournamentParticipant->event_participant_id        = $request->event_participant_id;
        $tournamentParticipant->event_tournament_id         = $tournament->id;
        $tournamentParticipant->event_tournament_team_id    = $tournamentTeam->id;

        if (!$tournamentParticipant->save()) {
            Session::flash('alert-danger', 'Cannot add participant. Please try again.');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Team Successfully Created!');
        return Redirect::back();
    }

    /**
     * Register Pug to Tournament
     * @param  Event           $event
     * @param  EventTournament $tournament
     * @param  Request         $request
     * @return Redirect
     */
    public function registerPug(Event $event, EventTournament $tournament, Request $request)
    {
        if ($tournament->status != 'OPEN') {
            Session::flash('alert-danger', 'Signups not permitted at this time.');
            return Redirect::back();
        }
     
        if (!$tournament->event->eventParticipants()->where('id', $request->event_participant_id)->first()) {
            Session::flash('alert-danger', 'You are not signed in to this event.');
            return Redirect::back();
        }

        if ($tournament->getParticipant($request->event_participant_id)) {
            Session::flash('alert-danger', 'You are already signed up to this tournament.');
            return Redirect::back();
        }

        $tournamentParticipant                          = new EventTournamentParticipant();
        $tournamentParticipant->event_participant_id    = $request->event_participant_id;
        $tournamentParticipant->event_tournament_id     = $tournament->id;
        $tournamentParticipant->pug                     = true;

        if (!$tournamentParticipant->save()) {
            Session::flash('alert-danger', 'Cannot add PUG. Please try again.');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully Registered!');
        return Redirect::back();
    }

    /**
     * Unregister from Tournament
     * @param  Event           $event
     * @param  EventTournament $tournament
     * @param  Request         $request
     * @return Redirect
     */
    public function unregister(Event $event, EventTournament $tournament, Request $request)
    {
        if (!$tournamentParticipant = $tournament->getParticipant($request->event_participant_id)) {
            Session::flash('alert-danger', 'You are not signed up.');
            return Redirect::back();
        }

        if (!$tournamentParticipant->delete()) {
            Session::flash('alert-danger', 'Cannot remove. Please try again.');
            return Redirect::back();
        }

        Session::flash('alert-success', 'You have been successfully removed from the Tournament.');
        return Redirect::back();
    }
}
