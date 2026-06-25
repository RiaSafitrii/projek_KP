<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hashtags extends Model
{
    use HasFactory;
    protected $table = 'hashtags';
    protected $guarded=[
        'id'
    ];

    public function news()
    {
        return $this->belongsToMany(News::class, 'hashtags_news', 'hashtag_id', 'news_id');
    }

    public function newsHashtags()
    {
        return $this->hasMany(NewsHashtags::class, 'hashtag_id');
    }


    // @foreach($article->hashtags as $hashtag)
    // <a href="{{ route('articles.hashtag', ['hashtag' => $hashtag->hashtag]) }}">#{{ $hashtag->hashtag }}</a>
    // @endforeach

    // public function showByHashtag($hashtag)
    // {
    //     $hashtag = Hashtag::where('hashtag', $hashtag)->firstOrFail();
    //     $articles = $hashtag->articles()->get();
    //     return view('articles.index', compact('articles'));
    // }

    // <h2>Artikel dengan hashtag #{{ $hashtag->hashtag }}</h2>
    // @foreach($articles as $article)
    //     <h3>{{ $article->title }}</h3>
    //     <p>{{ $article->content }}</p>
    // @endforeach

}
