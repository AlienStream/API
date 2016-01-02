<?php namespace API\Http\Controllers;

use AlienStream\Domain\Contracts\Repositories\PostRepository;
use AlienStream\Domain\Contracts\Repositories\TrackRepository;
use AlienStream\Domain\Implementation\Models\Post;
use AlienStream\Domain\Implementation\Models\Track;
use AlienStream\Domain\Implementation\Models\Community;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;

class TrackController extends Controller
{
	protected $tracks;
	protected $posts;

	public function __construct(TrackRepository $tracks, PostRepository $posts)
	{
		$this->tracks = $tracks;
		$this->posts = $posts;
	}

	public function index()
	{
		return $this->respond(
			"All Tracks",
			$this->tracks->all()
		);
	}

	public function trending()
	{
		return $this->respond(
			"Trending Tracks",
			$this->tracks->trending()
		);
	}

	public function popular()
	{
		return $this->respond(
			"Popular Tracks",
			$this->tracks->popular()
		);
	}

	public function newest()
	{
		return $this->respond(
			"Newest Tracks",
			$this->tracks->newest()
		);
	}

	public function byId($id)
	{
		$track = $this->tracks->find($id);
		$track->posts = $this->posts->byUrl($track->embeddable->url);

		return $this->respond(
			"Track Found",
			$track
		);
	}

	public function favorite($id)
	{
		$track = $this->tracks->find($id);
		$user = Auth::user();

		return $this->respond(
			"Track Favorited",
			$this->tracks->find($id)
		);
	}
	public function flag($id)
	{
		$track = $this->tracks->find($id);
		$user = Auth::user();

		return $this->respond(
			"Track Flagged",
			$this->tracks->find($id)
		);
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
			$tracks = $tracks->keyBy('id')->map(function ($track) {
				$now = new DateTime();
				$diff = (new DateTime($track->created_at))->diff($now);
				$hours = $diff->h;
				$hours = $hours + ($diff->days * 24);
				$track->rank = $track->rank / ($hours+1);
				return $track;
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

		$tracks->keyBy('id')->sortByDesc('rank');
		return $this->respond(
			"Tracks For ". $community->name,
			array_values($tracks->toArray())
		);
	}
}
