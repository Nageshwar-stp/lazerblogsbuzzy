<?php

namespace App;

use Carbon\Carbon;
use App\Managers\UploadManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $table = 'posts';

    protected $fillable = [
        'slug', 'title', 'body', 'user_id', 'pagination', 'shared',
        'tags', 'type', 'ordertype', 'thumb', 'approve', 'show_in_homepage', 'language',
        'show_in_homepage', 'featured_at', 'published_at', 'deleted_at'
    ];

    protected $dates = ['created_at', 'featured_at', 'published_at', 'deleted_at'];

    protected $casts = ['shared', 'categories'];

    protected $softDelete = true;

    /**
     * Post belongs to user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Post has many entries
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entries()
    {
        return $this->hasMany('App\Entry', 'post_id');
    }

    /**
     * Post belongs to many category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany('App\Category', 'post_categories', 'post_id', 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    /**
     * Post has many poll options
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pollvotes()
    {
        return $this->hasMany('App\PollVotes', 'post_id');
    }

    /**
     * Post has many poll options
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reactions()
    {
        return $this->hasMany('App\Reactions', 'post_id');
    }

    /**
     * Get Post All comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->morphMany('App\Comments', 'content');
    }

    /**
     * Get post stats
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function popularityStats()
    {
        return $this->morphOne('App\Stats', 'trackable');
    }

    public function hit()
    {
        //check if a polymorphic relation can be set
        if ($this->exists) {
            $stats = $this->popularityStats()->first();

            if (empty($stats)) {
                //associates a new Stats instance for this instance
                $stats = new Stats();
                $this->popularityStats()->save($stats);
            }

            return $stats->updateStats();
        }
        return false;
    }

    /**
     * Get posts by stats
     */
    public function scopeGetStats($query, $days = 'one_day_stats', $orderType = 'DESC', $limit = 10)
    {
        $query->select('posts.*')
            ->leftJoin('popularity_stats', 'popularity_stats.trackable_id', '=', 'posts.id')
            ->where($days, '!=', 0)
            ->take($limit)
            ->orderBy($days, $orderType);

        return $query;
    }

    /**
     * Get cached posts
     */
    public function scopeGetCached($query, $key, $cacheTime = 60)
    {
        $posts = Cache::get('posts_' . $key);
        if (!empty($posts)) {
            return $posts;
        }

        $posts = $query->get();

        Cache::put('posts_' . $key, $posts, $cacheTime);

        return $posts;
    }

    /**
     * Get posts by type
     *
     * @param  $type
     * @return mixed
     */
    public function scopeByType($query, $type)
    {
        if ($type == 'all') {
            return $query;
        }
        return $query->where('type', $type);
    }

    /**
     * Get posts by category and its childs
     *
     * @param  $query
     * @param  $category_id
     * @return mixed
     */
    public function scopeByCategoryRecursively($query, $category_id)
    {
        return $query->whereHas('categories', function ($query) use ($category_id) {
            $query->whereIn('categories.id', get_category_ids_recursively($category_id));
        });
    }

    /**
     * Get posts by category
     *
     * @param  $query
     * @param  $category_id
     * @return mixed
     */
    public function scopeByCategory($query, $category_id)
    {
        return $query->whereHas('categories', function ($query) use ($category_id) {
            $query->where('categories.id', $category_id);
        });
    }

    /**
     * Get approval posts
     *
     * @param  $type
     * @return mixed
     */
    public function scopeApprove($query, $type)
    {
        return $query->where('approve', $type);
    }

    /**
     * Get approval posts
     *
     * @param  $type
     * @return mixed
     */
    public function scopeByApproved($query)
    {
        return $query->where('approve', 'yes');
    }

    /**
     * Get post by category
     *
     * @param  $query
     * @param  $categoryid
     * @return mixed
     */
    public function scopeByPublished($query)
    {
        return $query->whereNotNull("published_at")
            ->where("published_at", '<=', Carbon::now()->format('Y-m-d H:i:s'))
            ->latest('published_at');
    }

    /**
     * Get post by language
     *
     * @param  $query
     * @param  $language
     * @return mixed
     */
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

    /**
     * Get post by featured
     *
     * @param  $query
     * @return mixed
     */
    public function scopeByFeatured($query)
    {
        return $query->whereNotNull("featured_at")
            ->latest("featured_at");
    }

    /**
     * Get post for home
     *
     * @param  $query
     * @param  $categoryid
     * @return mixed
     */
    public function scopeForHome($query, $features = null)
    {
        if ($features !== null || get_buzzy_config('AutoInHomepage') == 'no') {
            return $query->where("show_in_homepage", 'yes');
        }

        return $query;
    }

    public function scopeByActiveTypes($query)
    {
        if (get_buzzy_config('p_buzzynews') == 'off') {
            $query->where("posts.type", '!=', 'news');
        }
        if (get_buzzy_config('p_buzzylists') == 'off') {
            $query->where("posts.type", '!=', 'list');
        }
        if (get_buzzy_config('p_buzzypolls') == 'off') {
            $query->where("posts.type", '!=', 'poll');
        }
        if (get_buzzy_config('p_buzzyquizzes') == 'off') {
            $query->where("posts.type", '!=', 'quiz');
        }
        if (get_buzzy_config('p_buzzyvideos') == 'off') {
            $query->where("posts.type", '!=', 'video');
        }

        return $query;
    }

    public function scopeByAcceptedTypes($query, $types)
    {
        $types = json_decode($types);
        $only_types = [];
        $only_ids = [];

        foreach ($types as $type) {
            if ($type == 'news' || $type == 'list' || $type == 'quiz' || $type == 'poll' || $type == 'video') {
                $only_types[] = $type;
            } else {
                $only_ids[] = intval($type);
            }
        }

        if (!empty($only_types)) {
            $query->whereIn("type",  $only_types);
        } elseif (!empty($only_ids)) {
            $query->byCategoryRecursively($only_ids);
        }

        return $query;
    }

    public function getSharedAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Force a hard delete on a soft deleted model.
     *
     * This method protects developers from running forceDelete when trait is missing.
     *
     * @return bool|null
     */
    public function forceDelete()
    {
        $this->forceDeleting = true;

        // @TODO move this to repository
        if (!empty($this->thumb)) {
            $imageM = new UploadManager();
            $imageM->delete(makepreview($this->thumb, 'b', 'posts'));
            $imageM->delete(makepreview($this->thumb, 's', 'posts'));
        }

        $this->entries()->withTrashed()->forceDelete();

        $this->reactions()->forceDelete();
        $this->pollvotes()->forceDelete();
        $this->popularityStats()->forceDelete();
        $this->categories()->detach();
        $this->tags()->detach();

        $this->delete();

        $this->forceDeleting = false;
    }
}
