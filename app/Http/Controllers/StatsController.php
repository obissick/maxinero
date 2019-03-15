<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function __construct()
    {
        ProcessStats::dispatch(new Setting);
    }
}
