<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Shoppingcart extends Model {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shoppingcart';
    /**
    * The database primary key value.
    *
    * @var string
    */
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['identifier', 'instance', 'user_id', 'content','created_at','updated_at'];
    
    public function saveShoppingcart($data, $content)
    {
        $shoppingcart = new $this;
        $shoppingcart->identifier = $data['ref_no'];
        $shoppingcart->user_id = \Auth::id();
        $shoppingcart->instance = 'shopping';
        $shoppingcart->content =  serialize($content);
        $shoppingcart->save();
        return $shoppingcart->id;
    }
}
