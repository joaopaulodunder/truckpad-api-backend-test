<?php

namespace App\Http\Controllers;

use App\TerminalCheckin;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TerminalCheckinController extends Controller
{
    public function createCheckin(Request $request)
    {
        try{
            TerminalCheckin::createCheckin($request);
            return response()->json('{"status":"OK"}')->setStatusCode(200);

        }catch(\Exception $e){
            return response()->json(sprintf('{"status":"ERROR", "message":"%s"}', $e->getMessage()))->setStatusCode(400);
        }
    }

    public function searchCheckin(Request $request)
    {
        $dtStart = (is_null($request->dtStart)) ? Carbon::now()->format('Y-m-d') : $request->dtStart;
        $dtEnd = (is_null($request->dtEnd)) ? Carbon::now()->format('Y-m-d') : $request->dtEnd;

        $loaded = ($request->carregado == 'true') ? 'YES' : 'NO';

        try {
            return response()->json(TerminalCheckin::getCheckinByPeriodAndLoaded($dtStart, $dtEnd, $loaded))->setStatusCode(200);
        }catch (\Exception $e){
            return response()->json(sprintf('{"status":"ERROR", "message":"%s"}', $e->getMessage()))->setStatusCode(400);
        }
    }

    public function searchCheckinSourceAndDestiny(Request $request)
    {
        $dtStart = (is_null($request->dtStart)) ? Carbon::now()->format('Y-m-d') : $request->dtStart;
        $dtEnd = (is_null($request->dtEnd)) ? Carbon::now()->format('Y-m-d') : $request->dtEnd;

        try{
            return response()->json(TerminalCheckin::getCheckinSourceAndDestinyByPeriod($dtStart, $dtEnd))->setStatusCode(200);
        }catch (\Exception $e){
            return response()->json(sprintf('{"status":"ERROR", "message":"%s"}', $e->getMessage()))->setStatusCode(400);
        }
    }
}
