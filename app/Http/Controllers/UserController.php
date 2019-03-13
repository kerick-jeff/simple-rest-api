<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\CRUDTraits;
use App\Http\Traits\StatusCodes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

    use CRUDTraits;

    const MODEL = 'App\Models\User';

    /**
     * Save a user record in the database
     * 
     * @param  Request $request
     * @return mixed
     */
    public function store(Request $request) {
    	$this->validate($request, User::$updateRules);

    	$user = new User();
    	$user->first_name = $request->input('first_name');
    	$user->last_name = $request->input('last_name');
    	$user->email = $request->input('email');
    	$user->password = Hash::make($request->input('password'));

    	try {
    		$user->save();

    		$res['status'] = true;
    		$res['data'] = $user;

    		return $this->respond($res, StatusCodes::CREATED);
    	} catch (\Illuminate\Database\QueryException $e) {
    		$res['status'] = false;
    		$res['error'] = $e->getMessage();

    		return $this->respond($res, StatusCodes::BAD_REQUEST);
    	}
    }

    /**
     * Update a user record in the datatabase
     * 
     * @param  Request $request
     * @return mixed
     */
    public function update(Request $request) {
        $this->validate($request, User::$updateRules);

        $user = Auth::user();

        try {
            $user->update($request->all());

            $res['status'] = true;
            $res['data'] = $user;

            return $this->respond($res, StatusCodes::OK);
        } catch (\Illuminate\Database\QueryException $e) {
            $res['status'] = false;
            $res['message'] = $e->getMessage();

            return $this->respond($res, StatusCodes::BAD_REQUEST);
        }
    }

    /**
     * Delete a user record from the database
     * 
     * @return mixed
     */
    public function remove() {
    	$user = Auth::user();

        try {
            $user->delete();

            $res['status'] = true;
            $res['data'] = $user;

            return $this->respond($res, StatusCodes::OK);
        } catch (\Illuminate\Database\QueryException $e) {
            $res['status'] = false;
            $res['message'] = $e->getMessage();

            return $this->respond($res, StatusCodes::BAD_REQUEST);
        }
    }
}
