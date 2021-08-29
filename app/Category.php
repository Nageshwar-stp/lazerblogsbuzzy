<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['name', 'name_slug', 'posturl_slug', 'disabled', 'icon', 'menu_icon_show', 'description', 'language', 'type', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('name', 'asc');
    }

    /**
     * The posts that belong to the category.
     */
    public function posts()
    {
        return $this->belongsToMany('App\Post', 'post_categories', 'category_id', 'post_id');
    }

    public function scopeByMain($query)
    {
        return $query->whereNull("parent_id");
    }

    public function scopeBySub($query)
    {
        return $query->whereNotNull("parent_id");
    }

    public function scopeByType($query, $type)
    {
        return $query->where("type", $type);
    }

    public function scopeByLanguage($query, $language = null)
    {
        if ($language) {
            return $query->where('language', $language);
        }

        if (get_buzzy_config('p_multilanguage') == 'on') {
            return $query->where('language', app()->getLocale());
        }

        return $query->where('language', get_buzzy_config('sitedefaultlanguage', 'en'));
    }

    public function scopeByActive($query)
    {
        return $query->where("disabled", "0");
    }

    public function scopeByOrder($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Force a hard delete user.
     *
     * @return bool|null
     */
    public function delete()
    {
        //remove sub categories
        $this->children()->delete();

        //remove category
        parent::delete();
    }
}
