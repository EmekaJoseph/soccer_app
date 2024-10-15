<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\TournamentModel;
use App\Models\UserModel;
use App\Models\TeamModel;
use Illuminate\Support\Facades\Auth;
use stdClass;

class TournamentController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private $folder_name = 'tour_logos';

    // create Tournament
    public function createTournament(Request $req)
    {
        // validate varibles
        $rules = [
            'tour_title' => 'required',
            'tour_type' => 'required | in:cup,league',
        ];

        $validator = Validator::make($req->all(),  $rules);

        if ($validator->fails())
            return response()->json($validator->errors(), 422);


        $tour_title = $req->input('tour_title');
        $tour_type = $req->input('tour_type');
        $tour_desc = $req->input('tour_desc', null);
        $tour_logo = null;

        if (TournamentModel::where(['user_id' => Auth::id(), 'tour_title' => $tour_title])->exists())
            return response()->json('exists', 203);


        if ($req->hasFile("tour_logo")) {
            $image = $req->file("tour_logo");
            $tour_logo = HelperUploadImageAndResize($this->folder_name, $image, 100, 100, 'logo_');
        }

        TournamentModel::create([
            'tour_title' => $tour_title,
            'user_id' => Auth::id(),
            'tour_type' =>   $tour_type,
            'tour_logo' =>   $tour_logo,
            'tour_desc' =>   $tour_desc,
        ]);

        $user = UserModel::find(Auth::id());

        if ($tour_type == 'league')
            $user->increment('no_of_leagues');
        else
            $user->increment('no_of_cups');

        return response()->json('saved', 200);
    }


    // get Tournaments
    public function getTournaments(Request $req)
    {
        $tournaments = UserModel::find(Auth::id())->relatedTournaments;

        if ($tournaments) {
            foreach ($tournaments as  $list) {
                $list->id = $list->tour_id;
                $list->title = $list->tour_title;
                $list->type = $list->tour_type;
                $list->created = Carbon::parse($list->created_at)->diffForHumans();
            }
        }

        return response()->json($tournaments, 200);
    }


    public function updateImage(Request $req, $tour_id)
    {
        $tournament = TournamentModel::find($tour_id);
        $tour_logo = null;
        if ($tournament->tour_logo)
            HelperUnlinkFile($tournament->tour_logo);

        if ($req->hasFile("tour_logo")) {
            $image = $req->file("tour_logo");
            $tour_logo = HelperUploadImageAndResize($this->folder_name, $image, 50, 50, 'logo_');
        }

        $tournament->tour_logo = $tour_logo;
        $tournament->save();
    }


    // update Tournament
    public function update(Request $req)
    {
        $tour_title = $req->input('tour_title');
        $tour_desc = $req->input('tour_desc', null);
        $tour_type = $req->input('tour_type', null);
        $tour_id = $req->tour_id;


        if (TournamentModel::where('user_id', Auth::id())
            ->whereNot('tour_id', $tour_id)
            ->where('tour_title', $tour_title)
            ->exists()
        ) {
            return response()->json('exists', 203);
        }

        $tournament = TournamentModel::find($tour_id);
        $tournament->update([
            'tour_title' => $tour_title,
            'tour_desc' => $tour_desc,
            'tour_type' => $tour_type,
        ]);

        if ($req->hasFile("tour_logo")) {
            // remove existing file
            HelperUnlinkFile($tournament->tour_logo);

            // add new file
            $image = $req->file("tour_logo");
            $tour_logo = HelperUploadImageAndResize($this->folder_name, $image, 100, 100, 'logo_');
            $tournament->update(['tour_logo' => $tour_logo]);
        }

        return response()->json('updated', 200);
    }


    public function deleteTournament($tour_id)
    {
        $tournamentHasTeams = TeamModel::where('tour_id', $tour_id)->first();
        if ($tournamentHasTeams)
            return response()->json('tournament has teams', 203);

        $tour = TournamentModel::find($tour_id);

        if ($tour->tour_logo)
            HelperUnlinkFile($tour->tour_logo);

        $tour->delete();

        return response()->json('deleted', 200);
    }
}
