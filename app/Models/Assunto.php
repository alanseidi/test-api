<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assunto extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'assunto';
    protected $primaryKey = 'codAs';
    protected $fillable = [
        'descricao'
    ];
}
