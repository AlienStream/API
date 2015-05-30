<?php namespace API\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{

	use DispatchesCommands, ValidatesRequests;

	protected $statusCode = 200;

	public function respondNotFound($message = 'Not Found!')
	{
		return $this->setStatusCode(404)->respondWithError($message);
	}

	public function respondInvalidInput($message = 'Invalid Input')
	{
		return $this->setStatusCode(422)->respondWithError($message);
	}

	public function respondInternalError($message = 'Server Error')
	{
		return $this->setStatusCode(500)->respondWithError($message);
	}

	public function respondUnauthorized($message = 'Unauthorized')
	{
		return $this->setStatusCode(401)->respondWithError($message);
	}

	public function respondWithError($message) {
		$message = [
			'status' => [
				'code'  => $this->getStatusCode(),
				'message' => $message
			],
			'data' => []
		];
		return response()->json($message, $this->getStatusCode(), []);
	}

	public function respond($message, $data = [], $headers = [])
	{
		$message = [
			'status' => [
				'code'  => $this->getStatusCode(),
				'message' => $message
			],
			'data' => $data
		];
		return response()->json($message, $this->getStatusCode(), $headers);
	}

	/**
	 * @return mixed
	 */
	public function getStatusCode()
	{
		return $this->statusCode;
	}

	/**
	 * @param mixed $statusCode
	 * @return mixed
	 */
	public function setStatusCode($statusCode)
	{
		$this->statusCode = $statusCode;
		return $this;
	}

}
