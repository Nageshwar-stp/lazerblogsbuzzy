<?php

namespace App;

use App\Traits\Messages\Messagable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword as ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements CanResetPassword, MustVerifyEmail
{
    use Notifiable, ResetPassword, Messagable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['usertype', 'username', 'username_slug', 'name', 'surname', 'genre', 'about', 'facebookurl', 'twitterurl', 'weburl', 'email', 'icon', 'splash', 'password', 'remember_token'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany('App\Post', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Comments', 'user_id');
    }

    public function vote()
    {
        return $this->hasMany('App\PollVotes', 'user_id');
    }

    public function followers()
    {
        return $this->hasMany('App\Followers', 'followed_id');
    }

    public function following()
    {
        return $this->hasMany('App\Followers', 'user_id');
    }

    public static function findByUsernameOrFail($username, $columns = array('*'))
    {
        if (!is_null($user = static::where('username_slug', $username)->first($columns))) {
            return $user;
        }

        abort(404);
    }

    public function isAdmin()
    {
        return $this->usertype == 'Admin';
    }

    public function isStaff()
    {
        return $this->usertype == 'Staff';
    }

    /**
     * Check user owns related model
     *
     * @param $related
     * @return bool
     */
    public function userifowns($related)
    {
        return $this->id == $related->user_id;
    }

    public function userifhaveyourowns($relateduser)
    {
        return $this->id == $relateduser->id;
    }

    public function setUsernameSlugAttribute($username)
    {
        return $this->attributes['username_slug'] = str_slug($username, '-');
    }

    /**
     * Force a hard delete user.
     *
     * @return bool|null
     */
    public function userDelete()
    {
        $this->posts()->forceDelete();
        $this->followers()->delete();
        $this->following()->delete();

        $this->messages()->forceDelete();
        $this->participants()->forceDelete();
        $this->threads()->forceDelete();
        $this->delete();
    }
}
