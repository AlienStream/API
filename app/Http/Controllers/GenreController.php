<?php namespace API\Http\Controllers;

use AlienStream\Domain\Contracts\Repositories\GenreRepository;

class GenreController extends Controller
{
    protected $genres;

    public function __construct(GenreRepository $genres)
    {
        $this->genres = $genres;
    }

    public function index() {
        return $this->respond(
            "All Genres",
            $this->genres->all()
        );
    }

    public function byId($id) {
        return $this->respond(
            "Genre Found",
            $this->genres->find($id)
        );
    }

}
