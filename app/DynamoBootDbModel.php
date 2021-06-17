<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DynamoBootDbModel extends \BaoPham\DynamoDb\DynamoDbModel
{
    protected $table = 'treatment_center_boot'; //table name
}
