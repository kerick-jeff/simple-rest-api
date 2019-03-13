<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class User extends Model {

    use Sluggable;

    /**
     * The attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = [
    	'first_name',
    	'last_name',
    	'email',
    	'password'
    ];

    /**
     * The attributes excluded from the model's JSON form
     * 
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

     /**
     * Validation rules to be used when storing the model
     * 
     * @var array
     */
    public static $rules = [
        'first_name' => 'required|string|max:64',
        'last_name' => 'required|string|max:64',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8|confirmed',
        'password_confirmation' => 'required|same:password'
    ];

    /**
     * Validation rules to be used when updating the model
     * 
     * @var array
     */
    public static $updateRules = [
    	'first_name' => 'sometimes|required|string|max:64',
        'last_name' => 'sometimes|required|string|max:64',
        'email' => 'sometimes|required|email|unique:users',
        'password' => 'sometimes|required|min:8|confirmed',
        'password_confirmation' => 'sometimes|required|same:password'
    ];

    /**
     * Get the sluggable configuration array for this model
     * 
     * @return array
     */
    public function sluggable() {
        return [
            'slug' => [
                'source' => ['first_name', 'last_name']
            ]
        ];
    }

    /**
     * Get the user's posts
     */
    public function posts() {
        return $this->hasMany('App\Models\Post');
    }
}
