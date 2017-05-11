<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplateField extends Model {
    
    
    public function emailTemplate(){
        return $this->belongsTo('email_templates')->select('id');
    }
}
