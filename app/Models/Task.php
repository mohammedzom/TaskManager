<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'user_id', 'description', 'priority'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_task');
    }

    public function favoriteByUser()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}
