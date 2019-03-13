<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

trait CRUDTraits {

	use StatusTraits;

	/**
	 * Retrieve all records from the database
	 * 
	 * @return mixed
	 */
	public function getAll(Request $request) {
		$limit = $request->query('limit', 10);

		$model = static::MODEL;

		$res['status'] = true;
		$res['data'] = $model::paginate($limit); // Return 10 items per page

		return $this->respond($res, StatusCodes::OK);
	}

	/**
	 * Retrieve a single record from the database by its ID
	 * 
	 * @param  integer $id
	 * @return mixed
	 */
	public function getById($id) {
		$model = static::MODEL;

		try {
			$record = $model::findOrFail($id);

			$res['status'] = true;
			$res['data'] = $record;

			return $this->respond($res, StatusCodes::OK);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			$res['status'] = false;
			$res['error'] = $e->getMessage();

			return $this->respond($res, StatusCodes::NOT_FOUND);
		}
	}

	/**
	 * Retrieve a single record from the database by its slug
	 * 
	 * @param  string $slug
	 * @return mixed
	 */
	public function getBySlug($slug) {
		$model = static::MODEL;

		try {
			$record = $model::where('slug', $slug)->firstOrFail();

			$res['status'] = true;
			$res['data'] = $record;

			return $this->respond($res, StatusCodes::OK);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			$res['status'] = false;
			$res['error'] = $e->getMessage();

			return $this->respond($res, StatusCodes::NOT_FOUND);
		}
	}

	/**
	 * Save a record in the database
	 * 
	 * @param  Request $request
	 * @return mixed
	 */
	public function store(Request $request) {
		$model = static::MODEL;

		$this->validate($request, $model::$rules);

		try {
			$res['status'] = true;
			$res['data'] = $model::create($request->all());

			return $this->respond($res, StatusCodes::CREATED);
		} catch (\Illuminate\Database\QueryException $e) {
			$res['status'] = false;
			$res['error'] = $e->getMessage();

			return $this->respond($res, StatusCodes::BAD_REQUEST);
		}
	}

	/**
	 * Update a record in the database
	 * 
	 * @param  Request $request
	 * @param  integer $id
	 * @return mixed
	 */
	public function update(Request $request, $id) {
		$model = static::MODEL;

		$this->validate($request, $model::$updateRules);

		try {
			$record = $model::findOrFail($id);

			if (Gate::allows('update-delete', $record)) {
				try {
					$record->update($request->all());

					$res['status'] = true;
					$res['data'] = $record;

					return $this->respond($res, StatusCodes::CREATED);
				} catch (\Illuminate\Database\QueryException $e) {
					$res['status'] = false;
					$res['error'] = $e->getMessage();

					return $this->respond($res, StatusCodes::BAD_REQUEST);
				}
			} else {
				$res['status'] = false;
				$res['error'] = 'You are not authorized to perform this action';

				return $this->respond($res, StatusCodes::FORBIDDEN);
			}
			
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			$res['status'] = false;
			$res['error'] = $e->getMessage();

			return $this->respond($res, StatusCodes::NOT_FOUND);
		}
	}

	/**
	 * Delete a record from the database
	 * 
	 * @param  integer $id
	 * @return mixed
	 */
	public function remove($id) {
		$model = static::MODEL;

		try {
			$record = $model::findOrFail($id);

			if (Gate::allows('update-delete', $record)) {
				try {
					$record->delete();

					$res['status'] = true;
					$res['data'] = $record;

					return $this->respond($res, StatusCodes::OK);
				} catch (\Illuminate\Database\QueryException $e) {
					$res['status'] = false;
					$res['error'] = $e->getMessage();

					return $this->respond($res, StatusCodes::BAD_REQUEST);
				}
			} else {
				$res['status'] = false;
				$res['error'] = 'You are not authorized to perform this action';

				return $this->respond($res, StatusCodes::FORBIDDEN);
			}
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			$res['status'] = false;
			$res['error'] = $e->getMessage();

			return $this->respond($res, StatusCodes::NOT_FOUND);
		}
	}

}
