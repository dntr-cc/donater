<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonateRequest;
use App\Http\Resources\DonateResource;
use App\Models\Donate;
use Symfony\Component\HttpFoundation\Response;
use Bhaktaraz\RSSGenerator\Channel;
use Bhaktaraz\RSSGenerator\Feed;
use Bhaktaraz\RSSGenerator\Item;

class DonateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', Donate::class);
        $donates = Donate::paginate(30)->fragment('donates');

        return view('donates', compact('donates'));
    }

    public function rss()
    {
        $feed = new Feed();
        $channel = new Channel();
        $channel->title('Донати на donater.com.ua')
            ->description('Сповіщення про те, що українці донатять')
            ->url('https://donater.com.ua/donates')
            ->appendTo($feed);

        foreach (Donate::latest()->limit(20)->get() as $donate) {
            $item = new Item();
            $user = $donate->donater->first();
            $item
                ->title(strtr('Новий донат в :volunteer', [':volunteer' => $donate->volunteer->getName()]))
                ->description(strtr('Користувач :name задонатив на збір :volunteer', [':name' => $user->getFullName(), ':volunteer' => $donate->volunteer->getName()]))
                ->url($user->getUserLink())
                ->appendTo($channel);
        }

        return new Response($feed, Response::HTTP_OK, ['Content-type' => 'text/xml;charset=UTF-8']);
    }

    public function store(DonateRequest $request)
    {
        $this->authorize('create', Donate::class);

        Donate::create($request->validated());

        return redirect(route('my'), Response::HTTP_FOUND)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function create()
    {
        return view('donate');
    }

    public function show(Donate $donate)
    {
        $this->authorize('view', $donate);

        return new DonateResource($donate);
    }

    public function update(DonateRequest $request, Donate $donate)
    {
        $this->authorize('update', $donate);

        $donate->update($request->validated());

        return new DonateResource($donate);
    }

    public function destroy(Donate $donate)
    {
        $this->authorize('delete', $donate);

        $donate->delete();

        return response()->json();
    }
}
