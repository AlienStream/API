<?php namespace ProjectName\Repositories\Boundary;

/**
 * Interface AbstractRepository
 * @package ProjectName\Repositories\Boundary
 */
interface AbstractRepository {

	/**
	 * Get All The Entities
	 * @return mixed
	 */
	public function all();

	/**
	 * Find An Entity By ID
	 * @param int $id
	 * @return mixed
	 */
	public function find($id);
}