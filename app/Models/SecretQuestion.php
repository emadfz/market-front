<?php

// Copied from Admin

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SecretQuestion extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'secret_questions';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['secret_question', 'status', 'admin_user_id', 'created_at', 'deleted_at'];
    public $timestamps = false;

    public function getSecretQuestion($id = '', $dropdown = false) {
        if (isset($id) && $id != '') {
            return $this->where('id', $id)->first()->toArray();
        }
        
        if($dropdown){
            return [''=>trans('form.common.select_secret_question')]+$this->pluck('secret_question','id')->all();
        }
        
        return $this->get()->toArray();
    }

}
