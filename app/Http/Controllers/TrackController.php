<?php namespace API\Http\Controllers;

use AlienStream\Domain\Contracts\Repositories\TrackRepository;
use AlienStream\Domain\Implementation\Models\Track;

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
}
