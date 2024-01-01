<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class click extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;

    public $timestamps=false;
    protected $fillable=['link_id','counter','click_date'];
}
