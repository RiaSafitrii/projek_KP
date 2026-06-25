
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsHashtags extends Model
{
    use HasFactory;
    protected $table = 'hashtags_news';
    protected $guarded=[
        'id'
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'id');
    }

    // Relasi dengan Hashtags
    public function hashtags()
    {
        return $this->belongsTo(Hashtags::class, 'id');
    }

}
