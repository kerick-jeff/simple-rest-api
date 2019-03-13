<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\CRUDTraits;

class PostController extends Controller {
	
    use CRUDTraits;

    const MODEL = 'App\Models\Post';

}
