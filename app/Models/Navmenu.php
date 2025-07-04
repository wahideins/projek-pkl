<?php
// File: app/Models/NavMenu.php
// PERBAIKAN: Memastikan relasi docsContent ada dan buildTree yang benar

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Collection; // Tambahkan ini

class NavMenu extends Model
{
    use HasFactory;

    protected $table = 'navmenu';
    protected $primaryKey = 'menu_id';
    public $timestamps = false;

    protected $fillable = [
        'menu_nama',
        'menu_link',
        'menu_icon',
        'menu_child',
        'menu_order',
        'menu_status',
        'category',
    ];

    /**
     * Relasi ke konten dokumentasi (satu menu punya satu konten).
     */
    public function docsContent()
    {
        return $this->hasOne(DocsContent::class, 'menu_id', 'menu_id');
    }

    /**
     * Relasi ke parent menu.
     */
    public function parent()
    {
        return $this->belongsTo(NavMenu::class, 'menu_child', 'menu_id');
    }

    /**
     * Relasi ke children (sub-menu).
     */
    public function children()
    {
        return $this->hasMany(NavMenu::class, 'menu_child', 'menu_id')->orderBy('menu_order');
    }

    /**
     * Membangun menu hierarkis dari koleksi.
     *
     * @param \Illuminate\Support\Collection $elements Koleksi semua menu item.
     * @param int $parentId ID parent saat ini (default 0 untuk root).
     * @return array Array hierarkis dari objek menu.
     */
    public static function buildTree(Collection $elements, $parentId = 0): array
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element->menu_child == $parentId) {
                // Kloning objek untuk menghindari modifikasi koleksi asli selama rekursi
                $item = clone $element;

                // Atur menu_link
                $pageSlug = Str::slug($item->menu_nama);
                $item->menu_link = route('docs', ['category' => $item->category, 'page' => $pageSlug]);
                
                // Panggil rekursif untuk anak-anaknya
                $children = self::buildTree($elements, $item->menu_id);
                
                // PENTING: Selalu set properti 'children' ke array kosong jika tidak ada anak
                // atau ke array anak yang ditemukan. Ini memastikan $item->children selalu 
                // berupa array PHP biasa, yang dievaluasi dengan benar oleh Blade.
                $item->children = $children; 

                // Ini juga sangat penting: Unset relasi 'children' bawaan Eloquent
                // agar Blade menggunakan properti 'children' yang kita buat manual
                // daripada koleksi Eloquent kosong yang bisa dianggap !empty()
                unset($item->relations['children']); 
                
                $branch[] = $item;
            }
        }
        return $branch;
    }
}