<?php namespace API\Http\Controllers;

use AlienStream\Domain\Contracts\Repositories\TrackRepository;
use AlienStream\Domain\Implementation\Models\Track;
use AlienStream\Domain\Implementation\Models\Community;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;

class TrackController extends Controller
{
	protected $tracks;

	public function __construct(TrackRepository $tracks)
	{
		$this->tracks = $tracks;
	}

	public function index()
	{
		return $this->tracks->all();
	}

	public function byCommunity($name) {
		$sort = Input::get('sort');
		$time = Input::get('t');
		$community = Community::query()
			->where('name', '=', $name)
			->with('sources.tracks.embeddable', 'sources.tracks.channel.artist')
			->first();

		$tracks = Collection::make();
		foreach ($community->sources as $source) {
			$tracks = $tracks->merge($source->tracks);
		}

		// default: Filter by hotness
		if (empty($sort) || $sort === "hot") {
			$tracks = $tracks->keyBy('id')->sortBy(function ($track) {
				$now = new DateTime();
				$diff = (new DateTime($track->created_at))->diff($now);
				$hours = $diff->h;
				$hours = $hours + ($diff->days * 24);
				$hotness = $track->rank / ($hours+1);
				return $hotness;
			});
		}

		// Filter by Date
		if ($sort === "top" && ! empty($time)) {
			$tracks = $tracks->filter(function($track) use ($time) {
				$now = new DateTime();
				$diff = (new DateTime($track->created_at))->diff($now);
				$hours = $diff->h;
				$hours = $hours + ($diff->days * 24);
				return $hours <= $time;
			});
		}
		return $this->respond(
			"Tracks For ". $community->name,
			array_values($tracks->toArray())
		);
	}
}
