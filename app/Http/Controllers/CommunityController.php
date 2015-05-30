<?php namespace API\Http\Controllers;

use AlienStream\Domain\Contracts\Repositories\CommunityRepository;
use AlienStream\Domain\Implementation\Models\Community;
use Illuminate\Database\Eloquent\Collection;

class CommunityController extends Controller
{
	protected $communities;

	public function __construct(CommunityRepository $communities)
	{
		$this->communities = $communities;
	}

	public function byName($name)
	{
		return $this->respond(
			"Community Found",
			$this->communities->byName($name)
		);
	}

	public function byName_Tracks($name)
	{
		$community = $this->communities
			->byName($name)
			->with('sources.tracks.embeddable')
			->first();

		$tracks = Collection::make();
		foreach ($community->sources as $source) {
			$tracks = $tracks->merge($source->tracks);
		}

		return $this->respond(
			"Tracks For ". $community->name,
			$tracks
		);
	}

	public function index()
	{
		return $this->respond(
			"All Communities",
			$this->communities->all()
		);
	}

	public function trending()
	{
		return $this->respond(
			"Trending Communities",
			$this->communities->trending()
		);
	}

	public function popular()
	{
		return $this->respond(
			"Popular Communities",
			$this->communities->popular()
		);
	}

	public function newest()
	{
		return $this->respond(
			"Newest Communities",
			$this->communities->newest()
		);
	}
}
