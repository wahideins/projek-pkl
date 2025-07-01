<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Navmenu extends Model
{
    use HasFactory;

    protected $table = 'navmenu';
    protected $primaryKey = 'menu_id';
    public $timestamps = false; // Tabel ini tidak memiliki kolom created_at/updated_at

    protected $fillable = [
        'menu_nama',
        'menu_link',
        'menu_icon',
        'menu_child',
        'menu_order',
        'menu_status',
    ];
}
