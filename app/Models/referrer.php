<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class referrer extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $fillable=['website_id','path','query','counter','visit_date'];
}
