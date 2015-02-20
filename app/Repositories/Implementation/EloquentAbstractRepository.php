<?php namespace Manager\Repositories\Implementation;

use Manager\Repositories\Boundary\AbstractRepository;

/**
 * Class EloquentAbstractRepository
 * @package Manager\Repositories\Implementation
 */
class EloquentAbstractRepository implements AbstractRepository
{


	/**
	 * Uses Eloquent to get All The Entities
	 * @return mixed
	 */
	public function all()
	{
		return $this->model->all();
	}

	/**
	 * Uses Eloquent to find a Object by ID
	 * @param int $id
	 * @return mixed
	 */
	public function find($id)
	{
		return $this->model->find($id);
	}
}
