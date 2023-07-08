<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Http\Response;

class AnalysisController extends Controller
{
    public function index(Request $request) {
        // Ajax通信なのでJsonで返却する必要がある 
        return response()->json([
            'data' => $request->startDate // 仮設定 
        ], Response::HTTP_OK);
    }
}
