<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\occasions;

class OccasionsController extends Controller
{    
    public $occasion;

    public function __construct() {
        $this->occasions = new occasions();
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $occasions=$this->occasions->getOccasions();                
        return view('front.occasions.index',  compact('occasions'));
    }

   
}
