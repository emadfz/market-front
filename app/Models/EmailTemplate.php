<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model {
    //

    /**
     * Get the field associated with template
     */
    public function templateField() {
        return $this->hasMany('App\Models\EmailTemplateField');
    }

    /*
     * Get template data
     */
    public static function getTemplate($where = []) {
        return self::with('templateField')->where($where)->first()->toArray();
    }

    /*
     * Update template
     */
    public static function updateTemplate($request, $id) {
        $data = $request->except(['_method', '_token']);
        //$data['updated_at'] = Carbon::now();
        return self::where('id', $id)->update($data);
    }

}
