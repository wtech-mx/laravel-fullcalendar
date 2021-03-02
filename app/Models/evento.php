<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class evento extends Model
{
    use HasFactory;

    protected $table = "eventos";
    protected $primarykey = "id";

    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'color',
        'textColor',
        'start',
        'end',
    ];

}
