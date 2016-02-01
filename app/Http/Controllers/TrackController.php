<?php namespace API\Http\Controllers;

use AlienStream\Domain\Contracts\Repositories\TrackRepository;
use AlienStream\Domain\Implementation\Models\Track;
use AlienStream\Domain\Implementation\Models\Community;
use DateTime;
use DB;
use Input;
use Illuminate\Support\Collection;

class TrackController extends Controller
{
	protected $tracks;

	public function __construct(TrackRepository $tracks)
	{
		$this->tracks = $tracks;
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
		return $this->respond(
			"Track Found",
			$this->tracks->find($id)
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
			->firstOrFail();

		$rawTrackQuery = Track::query()
			->join('source_track', 'tracks.id', '=', 'source_track.track_id')
			->join('community_source', 'source_track.source_id', '=', 'community_source.source_id')
			->where('community_id', '=', $community->id)
			->with('embeddable', 'channel.artist');

		if ($sort === "top" && ! empty($time)) {
			$filteredTrackQuery = $rawTrackQuery
				->selectRAW("*")
				->orderBy('created_at', 'DESC')
				->whereRAW('created_at >= DATE_SUB(NOW(), INTERVAL ? HOUR)', [$time]);
		} else {
			$filteredTrackQuery = $rawTrackQuery
				->selectRAW("*, tracks.rank / TIMESTAMPDIFF(HOUR, tracks.created_at, NOW()) as hotness")
				->orderBy('hotness', 'DESC')
				->limit(250);
		}

		$tracks = $filteredTrackQuery->get();

		return $this->respond(
			"Tracks For ". $community->name,
			array_values($tracks->toArray())
		);
	}
}
