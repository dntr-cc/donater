<?php

namespace App\Http\Controllers;

use App\Http\Requests\FundraisingRequest;
use App\Models\Fundraising;
use App\Services\ChartService;
use App\Services\GoogleServiceSheets;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FundraisingController extends Controller
{
    public function store(FundraisingRequest $request)
    {
        $this->authorize('create', Fundraising::class);

        $attributes = $request->validated();

        if ((int)$attributes['user_id'] !== $request->user()->getId() && !$request->user()->isSuperAdmin()) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $fundraising = Fundraising::create($attributes);

        return new JsonResponse(['url' => route('fundraising.show', compact('fundraising'))]);
    }

    public function create()
    {
        return view('fundraising.create');
    }

    public function storeAvatar(Request $request): JsonResponse
    {
        $this->authorize('create', Fundraising::class);

        $uploadedFiles = $request->file('FILE');
        $fileName = $uploadedFiles->getClientOriginalName();
        $username = $request->user()->getUsername();
        $directory = public_path('/images/banners/' . $username);
        $uploadedFiles->move($directory, $fileName);
        $avatar = '/images/banners/' . $username . '/' . $fileName;

        return new JsonResponse(['avatar' => url($avatar), 'csrf' => $this->getNewCSRFToken()]);
    }

    public function checkKey(Request $request): JsonResponse
    {
        $this->authorize('create', Fundraising::class);

        $status = Fundraising::query()->where('key', '=', $request->get('key', ''))->count() > 0 ? Response::HTTP_CONFLICT : Response::HTTP_OK;

        return new JsonResponse(['csrf' => $this->getNewCSRFToken()], $status);
    }

    public function spreadsheet(Request $request): JsonResponse
    {
        $this->authorize('create', Fundraising::class);
        app(GoogleServiceSheets::class)->getRowCollection($request->get('spreadsheet_id'));

        return new JsonResponse(['csrf' => $this->getNewCSRFToken()], Response::HTTP_OK);
    }

    public function edit(Fundraising $fundraising)
    {
        $this->authorize('update', $fundraising);

        return view('fundraising.edit', compact('fundraising'));
    }

    public function show(Fundraising $fundraising)
    {
        $this->authorize('view', $fundraising);
        $rows = $charts = $charts2 = null;
        $data = '{}';
        try {
            $rows = app(GoogleServiceSheets::class)->getRowCollection($fundraising->getSpreadsheetId(), $fundraising->getId());
            $perDay = $rows->perDay();
            $perSum = $rows->perSum();
            $charts = app(ChartService::class)->chart()->name('pieChartTest')
                ->name('pieChartTest')
                ->type('line')
                ->labels(array_keys($perDay))
                ->datasets([
                    [
                        'borderColor' => "rgb(255, 99, 132)",
                        'fill'        => false,
                        'data'        => array_map(fn(array $item, string $key) => ['x' => $key, 'y' => $item['amount']], array_values($perDay), array_keys($perDay)),
                    ],
                ])
                ->optionsRaw(
                    "{
                    plugins: {
                      legend: false
                    },
                    scales: {
                      x: {
                        type: 'linear'
                      }
                    }
                }"
                );
            $colors = [
                '#4dc9f6',
                '#f67019',
                '#f53794',
                '#537bc4',
                '#acc236',
                '#166a8f',
                '#00a950',
                '#58595b',
                '#8549ba'
            ];
            $charts2 = app(ChartService::class)->chart()->name('pieChartTest')
                ->name('pieChartTest2')
                ->type('pie')
                ->size(['width' => 400, 'height' => 200])
                ->labels(array_keys($perSum))
                ->datasets([
                    [
                        'backgroundColor'      => array_slice($colors, 0, count($perSum)),
                        'hoverBackgroundColor' => array_slice($colors, 0, count($perSum)),
                        'data'                 => array_values($perSum),
                    ],
                ])
                ->optionsRaw(
                    " {
                    responsive: true,
                    plugins: {
                      legend: {
                        position: 'top',
                      },
                      title: {
                        display: true,
                        text: 'Донати по сумі'
                      }
                    }
                  }"
                );
        } catch (\Throwable $throwable) {
            Log::critical($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
        }

        return view('fundraising.show', compact('fundraising', 'rows', 'charts', 'charts2'));
    }

    public function start(Fundraising $fundraising)
    {
        $this->authorize('update', $fundraising);

        $fundraising->update(['is_enabled' => true]);

        return $this->getRedirectResponse($fundraising);
    }

    public function update(FundraisingRequest $request, Fundraising $fundraising)
    {
        $this->authorize('update', $fundraising);

        $fundraising->update($request->validated());

        return new JsonResponse(['url' => route('fundraising.show', compact('fundraising'))]);
    }

    /**
     * @param Fundraising $fundraising
     * @return RedirectResponse
     */
    protected function getRedirectResponse(Fundraising $fundraising): RedirectResponse
    {
        return redirect(
            route('fundraising.show', compact('fundraising')),
            Response::HTTP_FOUND
        )->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function stop(Fundraising $fundraising)
    {
        $this->authorize('update', $fundraising);

        $fundraising->update(['is_enabled' => false]);

        return $this->getRedirectResponse($fundraising);
    }

    public function raffle(Fundraising $fundraising)
    {
        $this->authorize('update', $fundraising);

        return view('fundraising.raffle', compact('fundraising'));
    }

    public function destroy(Fundraising $fundraising)
    {
        $this->authorize('delete', $fundraising);

        $fundraising->delete();

        return response()->json();
    }
}
