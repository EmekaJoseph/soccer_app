<?php

namespace App\Interfaces;

interface MatchResultsServiceInterface
{
    public function saveResult($request);
    public function undoResult($request);
}
