<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Mail;

class TestController extends Controller
{
    
    public function index()
    {	
    	$user = \Auth::user();

    	Mail::send('front.email_templates.reminder', ['user' => $user], function ($m) use ($user) {
                $m->from('emad.keriakos@indianic.com', 'Your Application');

                $m->to('emad.keriakos@indianic.com', 'Emad Zaki')->subject('New Advertisment Request');
            }); 
    	return "done";
    }
}
