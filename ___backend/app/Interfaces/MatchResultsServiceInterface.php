<?php

namespace App\Interfaces;

interface MatchResultsServiceInterface
{
    public function saveResult($request, $matchType);
    public function undoResult($request, $matchType);
}
