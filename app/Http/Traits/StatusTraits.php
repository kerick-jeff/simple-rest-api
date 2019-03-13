<?php

namespace App\Http\Traits;

trait StatusTraits {

	/**
	 * Sends an HTTP response including the response code and other data
	 * 
	 * @param array $res
	 * @param string $code
	 */
	protected function respond($res, $code) {
		return response($res, $code);
	}
	
}

/**
 * HTTP status response codes
 */
abstract class StatusCodes {
	const OK = 200;
    const CREATED = 201;
    const NO_CONTENT = 204;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const CONFLICT = 409;
    const INTERNAL_ERROR = 500;
}