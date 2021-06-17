<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErrorCode extends Model
{
    protected $table = 'json_error_code';


    static function  getCodeValueByCode($code)
    {
        $getErrorCodeData = ErrorCode::select('value')->where('code',$code)->first();
        return $getErrorCodeData->value;
    }
}
