<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DynamoDbModel extends \BaoPham\DynamoDb\DynamoDbModel
{
    protected $table = 'Treatment_center_data'; //table name
}
