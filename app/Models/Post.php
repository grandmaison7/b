<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $fillable =['title', 'excerpt', 'body'];

    protected $guarded = [];
    protected $with = ['category', 'author'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, fn($query, $search) =>
            $query
                ->where('title', 'like', '%' . $search . '%')
                ->orWhere('body', 'like', '%' . $search . '%')
            );
            $query->when($filters['category'] ?? false, fn($query, $category) =>
                $query->whereHas('category', fn ($query) =>
                    $query->where('slug', $category)
            )
        );
        
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function author(){
        return $this->BelongsTo(User::class, 'user_id');
    }

}

