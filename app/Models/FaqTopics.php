<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqTopics extends Model
{
    protected $table = "faq_topics";

    public function questions()
    {
        return $this->hasMany('App\Faq', 'faq_topic_id', 'id');
    }
}