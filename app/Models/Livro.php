<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Livro extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'livro';
    protected $primaryKey = 'codL';
    protected $fillable = [
        'titulo',
        'editora',
        'edicao',
        'anoPublicacao',
    ];


    public function autores(): BelongsToMany
    {
        return $this->belongsToMany(Autor::class, 'livro_autor', 'livro_codL', 'autor_codAu');
    }
}
