<?php namespace API\Http\Controllers;

use AlienStream\Domain\Contracts\Repositories\UserRepository;
use Auth;

class UserController extends Controller
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function me() {
        return $this->respond(
            "Logged In",
            Auth::user()
        );
    }

    public function favoritedTracks($userId) {
        $user = $this->users->find($userId);

        $tracks = $user->favoritedTracks->map(function($track) {
            $track->favorited = true;
            return $track;
        });

        return $this->respond(
            'User Favorited Tracks',
            $tracks
        );

    }

    public function favoritedCommunities($userId) {
        $user = $this->users->find($userId);
        return $this->respond(
            'User Favorited Communities',
            $user->favoritedCommunities
        );

    }

    public function moderatedCommunities($userId) {
        $user = $this->users->find($userId);
        return $this->respond(
            'User Moderated Communities',
            $user->moderatedCommunities
        );
    }
}
