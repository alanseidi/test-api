<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Autor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'autor';
    protected $primaryKey = 'codAu';

    protected $fillable = ['nome'];


    public function livros(): BelongsToMany
    {
        return $this->belongsToMany(Livro::class, 'livro_autor', 'autor_codAu', 'livro_codL');
    }
}
