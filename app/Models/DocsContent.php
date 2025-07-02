<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Navmenu;

class DocsContent extends Model
{
    use HasFactory;

    protected $table = 'docs';
    protected $primaryKey = 'docs_id';

    protected $fillable = [
        'content',
    ];

    public function menu() {
        return $this->belongsTo(Navmenu::class, 'menu_id', 'menu_id');
    }
}
