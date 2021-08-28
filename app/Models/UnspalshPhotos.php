<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model ;;

class UnspalshPhotos extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $collection = 'UnsplashPhotos';

    protected $primaryKey = 'id';

    protected  $guarded =[];
}
