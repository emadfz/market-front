<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportAbuse extends Model {

    protected $fillable = ['ref_id', 'user_id', 'type', 'report_value', 'something_else'];

    

}
