<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model ;

class UnspalshUsers extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $collection = 'UnsplashUsers';

    protected $primaryKey = 'id';

    protected  $guarded =[];

}
