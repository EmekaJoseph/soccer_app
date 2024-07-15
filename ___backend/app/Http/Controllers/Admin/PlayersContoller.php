<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PlayerRequest;
use App\Models\PlayerModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\TournamentModel;
use App\Models\TeamModel;
use Illuminate\Support\Facades\Auth;
use stdClass;

class PlayersContoller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private $folder_name = 'players_img';

    // create Tournament
    public function store(PlayerRequest $req)
    {
        $image = null;

        if ($req->hasFile("image")) {
            $image = $req->file("image");
            $image = HelperUploadImageAndResize($this->folder_name, $image, 50, 50, 'img_');
        }

        $team = TeamModel::find($req->team_id);

        PlayerModel::create(array_merge(
            $req->validated(),
            ['image' => $image, 'tour_id' => $team->tour_id]
        ));

        return response()->json('saved', 200);
    }


    // get Tournaments
    public function players(Request $req, $tour_id)
    {
        $players = PlayerModel::with(['team'])->where('tour_id', $tour_id)

            ->when($req->search, function ($query) use ($req) {
                $query->where(function ($subquery) use ($req) {
                    $subquery->where('first_name', 'LIKE', "%{$req->search}%")
                        ->orWhere('last_name', 'LIKE', "%{$req->search}%");
                });
            })

            ->when($req->team_id, function ($query) use ($req) {
                $query->where('team_id', $req->team_id);
            })
            ->get();

        return response()->json($players, 200);
    }


    public function updateImage(Request $req, $player_id)
    {
        $player = PlayerModel::find($player_id);
        $image = null;
        if ($player->tour_logo)
            HelperUnlinkFile($this->folder_name, $player->tour_logo);

        if ($req->hasFile("image")) {
            $image = $req->file("image");
            $image = HelperUploadImageAndResize($this->folder_name, $image, 50, 50, 'logo_');
        }

        $player->tour_logo = $image;
        $player->save();
    }


    // update
    public function update(PlayerRequest $req, $player_id)
    {
        PlayerModel::where('player_id', $player_id)->update($req->validated());
        return response()->json('updated', 200);
    }

    public function destroy($player_id)
    {
        $player = PlayerModel::find($player_id);

        if ($player->image)
            HelperUnlinkFile($this->folder_name, $player->image);

        $player->delete();

        return response()->json('deleted', 200);
    }
}
