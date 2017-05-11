<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    
    protected $fillable = array(
        'path',
        'imageable_id',
        'imageable_type',
        'file_type',
    );
    public function imageable()
    {        
      return $this->morphTo();
    }

    public function deleteAdfile($id) {
        if (isset($id) && !empty($id)) {
            return $this->where('id', $id)->delete();
        }
        return false;
    }

    public static function updateAdfile($where,$data) {        
        return self::where($where)->update($data);
    }

    public static function getfile($where) {        
        return self::select('*')->where($where)->get();
    }

    public function deleteProductfile($where) {
        if (isset($where) && !empty($where)) {
            return $this->where($where)->delete();
        }
        return false;
    }
}
