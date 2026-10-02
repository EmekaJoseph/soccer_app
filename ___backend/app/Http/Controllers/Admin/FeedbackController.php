<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedbackModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /** Fan feedback across all of the owner's tournaments, newest first. */
    public function index(Request $request): JsonResponse
    {
        $tournaments = $this->account($request)->tournaments()->pluck('tour_title', 'tour_id');

        $feedback = FeedbackModel::whereIn('tour_id', $tournaments->keys())
            ->orderByDesc('created_at')
            ->limit(500)
            ->get()
            ->map(fn (FeedbackModel $f) => [...$f->toArray(), 'tour_title' => $tournaments[$f->tour_id] ?? null]);

        return response()->json($feedback);
    }
}
