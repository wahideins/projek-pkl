<?php
// File: app/Models/NavMenu.php
// PERBAIKAN: Memastikan relasi docsContent ada

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
     */
    public static function buildTree($elements, $parentId = 0): array
    {
        $branch = [];
        foreach ($elements as $element) {
            if ($element->menu_child == $parentId) {
                $pageSlug = Str::slug($element->menu_nama);
                $element->menu_link = route('docs', ['category' => $element->category, 'page' => $pageSlug]);
                
                $children = self::buildTree($elements, $element->menu_id);
                if ($children) {
                    $element->children = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
}
