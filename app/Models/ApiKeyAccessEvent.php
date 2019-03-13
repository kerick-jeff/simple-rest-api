<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKeyAccessEvent extends Model {
    
	/**
     * Get the related ApiKey record
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apiKey() {
        return $this->belongsTo('App\Models\ApiKey');
    }

}
