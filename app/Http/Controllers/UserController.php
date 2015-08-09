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
}
