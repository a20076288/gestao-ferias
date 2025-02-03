<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ferias extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'data_inicio',
        'data_fim',
        'status',
        'observacoes',
    ];

    /**
     * Relacionamento: cada pedido de férias pertence a um utilizador.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
