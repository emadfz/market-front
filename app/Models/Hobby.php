<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hobby extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hobbies';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
    
    public function getHobby($datatable = false, $id = NULL) {

        if ($datatable == true) {
            $data=$this->get();            
            return getAuthorizedButton($data)->toJson();                                    
        } else if (isset($id) && !empty($id)) {
            return $this->where('id', $id)->first();
        } else {
            return $this->get();
        }
    }    
    public function saveHobby($data,$id='') {
        if(isset($id) && !empty($id)){
            return $this->where('id',$id)->update($data);
        }
        return $this->create($data);        
    }
    public function deleteHobby($id) {
        if (isset($id) && !empty($id)) {
            return $this->where('id', $id)->delete();
        }
        return trans('message.failure');
    }    
}
