<?php namespace API\Repositories\Implementation;

use API\Domain\Implementation\User;
use API\Repositories\Boundary\UserRepository;

/**
 * Class EloquentUserRepository
 * @package API\Repositories\Implementation
 */
class EloquentUserRepository extends EloquentAbstractRepository implements UserRepository
{

	protected $model;

	function __construct(User $model)
	{
		$this->model = $model;
	}

	/**
	 * Get A User By Their Username
	 * @param string $username
	 * @return mixed
	 */
	public function getUserByUsername($username)
	{
		$this->model->where('username', '=', $username);
	}
}
