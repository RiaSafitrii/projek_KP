<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;
    protected $table = 'news';
    protected $guarded=[
        'id'
    ];

    /*
    $news = News::find($newsId);
    */
    // Menambahkan kategori ke berita


    public static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            $slug = Str::slug($news->title);
            $count = News::where('slug', 'like', $slug . '%')->count();

            if ($count) {
                $slug .= '-' . ($count + 1);
            }

            $news->slug = $slug;
        });

        static::updating(function ($news) {
            if ($news->isDirty('title')) {
                $slug = Str::slug($news->title);
                $count = News::where('slug', 'like', $slug . '%')
                            ->where('id', '!=', $news->id)
                            ->count();

                if ($count) {
                    $slug .= '-' . ($count + 1);
                }

                $news->slug = $slug;
            }
        });
    }


    public function hashtags()
    {
        return $this->belongsToMany(Hashtags::class, 'hashtags_news', 'news_id', 'hashtag_id');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class, 'news_id'); // Pastikan `news_id` adalah kolom yang menghubungkan `comments` dengan `news`
    }

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }
}
