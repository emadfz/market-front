<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries';

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
    protected $fillable = ['country_name', 'country_code','status'];
    
    public function getCountry($datatable = false, $id = NULL) {

        if ($datatable == true) {
            $data=$this->get();            
            return getAuthorizedButton($data)->toJson();                                    
        } else if (isset($id) && !empty($id)) {
            return $this->where('id', $id)->first();
        } else {
            return $this->get();
        }
    }    
    public function saveCountry($data,$id='') {
        if(isset($id) && !empty($id)){
            return $this->where('id',$id)->update($data);
        }
        return $this->create($data);        
    }
    public function deleteCountry($id) {
        if (isset($id) && !empty($id)) {
            return $this->where('id', $id)->delete();
        }
        return trans('message.failure');
    }    
}
