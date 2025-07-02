<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DocsContent;

class NavMenu extends Model
{
    use HasFactory;

    protected $table = 'navmenu'; // Nama tabel
    protected $primaryKey = 'menu_id'; // Primary key
    public $timestamps = false; // Karena tabel tidak memiliki kolom created_at dan updated_at

    protected $fillable = [
        'menu_nama',
        'menu_link',
        'menu_icon',
        'menu_child',
        'menu_order',
        'menu_status',
        'menu_content',
        'category', // Tambahkan ini
    ];

    public function content(){
        return $this->hasOne(DocsContent::class, 'menu_id', 'menu_id');}
    // Jika ada relasi parent-child untuk menu, kamu bisa definisikan di sini
    public function parent()
    {
        return $this->belongsTo(NavMenu::class, 'menu_child', 'menu_id');
    }

    public function children()
    {
        return $this->hasMany(NavMenu::class, 'menu_child', 'menu_id');
    }
}
