<?php namespace API\Http\Controllers;

use AlienStream\Domain\Contracts\Repositories\CommunityRepository;
use AlienStream\Domain\Implementation\Models\Community;
use AlienStream\Domain\Implementation\Models\Genre;
use AlienStream\Domain\Implementation\Models\Source;
use Illuminate\Database\Eloquent\Collection;
use Auth;
use Illuminate\Http\Request;

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

	public function create($name, Request $request)
	{
		$input = $request->all();
		$data = array_merge(
			$input,
			['name' => $name]
		);

		// TODO validation
		$community = Community::create($data);
		Auth::user()->moderatedCommunities()->attach($community);
		foreach ($request->get('sources') as $requestSource) {
			$source = Source::where('url', '=', $requestSource['url'])->first();
			if (empty($source)) {
				$source = Source::create([
					'title' => $requestSource['title'],
					'type' => $this->getSourceType($requestSource['url']),
					'description' => $requestSource['description'],
					'thumbnail' => $requestSource['thumbnail'],
					'url' => $requestSource['url'],
					'importance' => 100,
				]);
			}
			if ( ! $community->sources->contains($source->id)) {
				$community->sources()->attach($source);
			}
		}

		return $this->respond(
			"Community Created",
			$community
		);
	}

	public function update($name, Request $request)
	{
		$input = $request::all();
		$data = array_merge(
			$input,
			['name' => $name]
		);

		// TODO validation
		$community = Community::where('name', '=', $name)->first();
		$community->update($data);
		foreach ($request->get('sources') as $requestSource) {
			$source = Source::where('url', '=', $requestSource['url'])->first();
			if (empty($source)) {
				$source = Source::create([
					'title' => $requestSource['title'],
					'type' => $this->getSourceType($requestSource['url']),
					'description' => $requestSource['description'],
					'thumbnail' => $requestSource['thumbnail'],
					'url' => $requestSource['url'],
					'importance' => 100,
				]);
			}
			if ( ! $community->sources->contains($source->id)) {
				$community->sources()->attach($source);
			}
		}

		return $this->respond(
			"Community Updated",
			$community
		);
	}

	protected function getSourceType($url) {
		//TODO other source types
		return "reddit/subreddit";
	}
}
