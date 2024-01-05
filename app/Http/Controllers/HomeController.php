<?php

namespace App\Http\Controllers;

use App\Collections\RowCollection;
use App\Services\ChartService;
use App\Services\GoogleServiceSheets;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $rows = $charts = $charts2 = $charts3 = null;
        $user = auth()->user();
        if (auth()?->user()?->can('update', $user)) {
            $rows = new RowCollection();
            $service = app(GoogleServiceSheets::class);
            foreach ($user->getFundraisings() as $fundraising) {
                $rows->push(...$service->getRowCollection($fundraising->getSpreadsheetId(), $fundraising->getId())->all());
            }
            $chartsService = app(ChartService::class);
            $charts = $chartsService->getChartPerDay($rows);
            $charts2 = $chartsService->getChartPerAmount($rows);
            $charts3 = $chartsService->getChartPerSum($rows);
        }
        return view('user', [
            'dntr' => null,
            'user' => $user,
            'rows' => $rows,
            'charts' => $charts,
            'charts2' => $charts2,
            'charts3' => $charts3,
        ]);
    }
}
