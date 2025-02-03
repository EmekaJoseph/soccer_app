<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\MatchResultsServiceInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Carbon\Carbon;

use Exception;

class ResultsController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $matchResultService;

    public function __construct(MatchResultsServiceInterface $matchResultService)
    {
        $this->matchResultService = $matchResultService;
    }

    public function saveResult(Request $req)
    {
        try {
            $result = $this->matchResultService->saveResult($req);
            if (isset($result['status']))
                return response()->json(['message' => $result['message']], $result['status']);
            return response()->json($result, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }


    public function undoResult(Request $req)
    {
        try {
            $result = $this->matchResultService->undoResult($req);
            if (isset($result['status']))
                return response()->json(['message' => $result['message']], $result['status']);
            return response()->json($result, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
