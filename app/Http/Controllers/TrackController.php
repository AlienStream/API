<?php namespace API\Http\Controllers;

use AlienStream\Domain\Contracts\Repositories\TrackRepository;
use AlienStream\Domain\Implementation\Models\Track;
use AlienStream\Domain\Implementation\Models\Community;
use Auth;
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

		if ($user->favoritedTracks !== null && $user->favoritedTracks->contains($track->id)) {
			return $this->respondInvalidInput(
				"Track Has already been favorited",
				$track
			);
		}

		$track->increment('favorite_count');
		$user->favoritedTracks()->save($track);

		return $this->respond(
			"Track Favorited",
			$track
		);
	}

	public function unfavorite($id)
	{
		$track = $this->tracks->find($id);
		$user = Auth::user();

		if ( ! ($user->favoritedTracks !== null &&  $user->favoritedTracks->contains($track->id))) {
			return $this->respondInvalidInput(
				"Track Has Not Been Favorited",
				$track
			);
		}

		$track->decrement('favorite_count');
		$user->favoritedTracks()->detach($track->id);

		return $this->respond(
			"Track Unfavorited",
			$track
		);
	}

	public function flag($id)
	{
		$track = $this->tracks->find($id);
		$user = Auth::user();

		if ($user->flaggedTracks !== null && $user->flaggedTracks->contains($track->id)) {
			return $this->respondInvalidInput(
				"Track Has Already been flagged",
				$track
			);
		}

		$track->increment('content_flags');
		$user->flaggedTracks()->save($track);

		return $this->respond(
			"Track Flagged",
			$track
		);
	}

	public function unflag($id)
	{
		$track = $this->tracks->find($id);
		$user = Auth::user();

		if ( ! ($user->flaggedTracks !== null && $user->flaggedTracks->contains($track->id))) {
			return $this->respondInvalidInput(
				"Track Has Not Been Flagged",
				$track
			);
		}

		$track->decrement('content_flags');
		$user->flaggedTracks()->delete($track);

		return $this->respond(
			"Track Unflagged",
			$track
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

		$tracks = $this->mapFlagsAndFavorites($tracks, Auth::user());

		return $this->respond(
			"Tracks For ". $community->name,
			array_values($tracks->toArray())
		);
	}

	protected function mapFlagsAndFavorites($tracks, $user)
	{
		if ($user !== null) {
			$favorites = $user->favoritedTracks;
			$flags = $user->flaggedTracks;

			$tracks = $tracks->map(function($track) use ($favorites, $flags) {
				$track->favorited = $favorites->contains($track->id);
				$track->flagged = $flags->contains($track->id);
				return $track;
			});
		}

		return $tracks;
	}
}
