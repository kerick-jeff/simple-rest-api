<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\Http\Traits\StatusCodes;
use App\Http\Traits\StatusTraits;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
	
	use StatusTraits;

	/**
	 * A user controller instance
	 * 
	 * @var App\Http\Controllers\UserController
	 */
	protected $userController;

	/**
	 * Create a new AuthController instance
	 * 
	 * @param  UserController $userController
	 * @return void
	 */
	public function __construct(UserController $userController) {
		$this->userController = $userController;
	}

	/**
	 * Create a JSON web token
	 * 
	 * @param  User   $user
	 * @return string
	 */
    protected function jwt(User $user) {
    	$payload = [
    		'issuer'	=> 'simple-jwt', // Issuer of the token
    		'id'		=> $user->id, // Subject of the token
    		'issued_at'	=> time(), // Time when the JWT was issued
    		'expires'	=> (time() + (60 * 60 * 24)) // Expiration time (default: 24 hours)
    	];

    	// Return encoded jwt. JWT_SECRET will also be used in the future to decode the token
    	return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * Authenticate a user by its email and password
     * 
     * @param  Request $request
     * @return mixed
     */
    public function login(Request $request) {
    	$validationRules = [
            'email' => 'required|email',
            'password' => 'required|string'
        ];

        $this->validate($request, $validationRules);

        try { // Check if email exists
        	$user = User::where('email', $request->input('email'))->firstOrFail();
        
        	// Verify the password and generate the token
        	if (Hash::check($request->input('password'), $user->password)) {
        		$res['status'] = true;
        		$res['data'] = $user;
        		$res['token'] = $this->jwt($user);

        		return $this->respond($res, StatusCodes::OK);
        	} else { // Passwords do not match
        		$res['status'] = false;
        		$res['error'] = 'Passwords do not match!';

        		return $this->respond($res, StatusCodes::UNAUTHORIZED);
        	}
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        	$res['status'] = false;
        	$res['error'] = 'Email does not exist!';

        	return $this->respond($res, StatusCodes::UNAUTHORIZED);
        }
    }

    /**
     * Register a new user in the database
     * 
     * @param  Request $request
     * @return mixed
     */
    public function register(Request $request) {
    	return $this->userController->store($request);
    }
}
