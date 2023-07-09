<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Services\AnalysisService;

class AnalysisController extends Controller
{
    public function index(Request $request) {
        $subQuery = Order::betweenDate($request->startDate, $request->endDate);

        if($request->type === 'perDay') {
            list($data, $labels, $totals) = AnalysisService::perDay($subQuery);
        }
        if($request->type === 'perMonth') {
            list($data, $labels, $totals) = AnalysisService::perMonth($subQuery); 
        }
        if($request->type === 'perYear') {
            list($data, $labels, $totals) = AnalysisService::perYear($subQuery); 
        }
        return response()->json([ 
            'data' => $data,
            'labels' => $labels,
            'totals' => $totals,
            'type' => $request->type 
        ], Response::HTTP_OK);
        
        // Ajax通信なのでJsonで返却する必要がある 
        // return response()->json([
        //     'data' => $request->startDate // 仮設定 
        // ], Response::HTTP_OK);
    }
}
