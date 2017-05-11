<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Faq;
use DB;
use App\Models\FaqTopics;
use App\Models\TermAndConditions;

use App\Http\Requests;

class FaqController extends Controller
{
    public function index()
    {
        $topics = FaqTopics::get();
        return view('front.faq.index', compact('topics'));
    }
    public function helpcenter($id)
    {
        //$questions = FaqTopics::find($id)->questions;
        // $topic = FaqTopics::find($id);
        $topics = FaqTopics::with('questions')->get();        
         
        $topic = $topics->where('id', (int)$id)->first();
        
        //$questions = $topics->where('id', (int)$id);
 

        //return view('front.faq.single', compact('questions', 'topics', 'topic'));
        return view('front.faq.single', compact('topics','topic'));
    }
    public function termscond()
    {
        $terms = TermAndConditions::where('status', 'published')->get();

        $topics = FaqTopics::get();
           return view('front.faq.terms', compact( 'topics', 'topic','terms'));

        
    }
}
