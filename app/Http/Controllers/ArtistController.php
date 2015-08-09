<?php namespace API\Http\Controllers;


use AlienStream\Domain\Contracts\Repositories\ArtistRepository;

class ArtistController extends Controller
{
    protected $artists;

    public function __construct(ArtistRepository $artists)
    {
        $this->artists = $artists;
    }

    public function byId($id)
    {
        return $this->respond(
            "Artist Found",
            $this->artists->find($id)
        );
    }

    public function index()
    {
        return $this->respond(
            "All Artists",
            $this->artists->all()
        );
    }

    public function trending()
    {
        return $this->respond(
            "Trending Artists",
            $this->artists->trending()
        );
    }

    public function popular()
    {
        return $this->respond(
            "Popular Artists",
            $this->artists->popular()
        );
    }

    public function newest()
    {
        return $this->respond(
            "Newest Artists",
            $this->artists->newest()
        );
    }
}
