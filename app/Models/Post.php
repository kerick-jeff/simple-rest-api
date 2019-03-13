<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model {

    use Sluggable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'user_id',
    	'title',
    	'description'
    ];

    /**
     * Validation rules to be used when storing the model
     * 
     * @var array
     */
    public static $rules = [
        'user_id' => 'required|integer',
        'title' => 'required|string|max:128',
        'description' => 'required|string'
    ];

    /**
     * Validation rules to be used when updating the model
     * 
     * @var array
     */
    public static $updateRules = [
        'user_id' => 'sometimes|required|integer',
        'title' => 'sometimes|required|string|max:128',
        'description' => 'sometimes|required|string'
    ];

    /**
     * Get the sluggable configuration array for this model
     * 
     * @return array
     */
    public function sluggable() {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * Get the user that owns the post
     */
    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
