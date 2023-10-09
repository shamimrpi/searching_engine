<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SearchHistory extends Model
{
    use HasFactory;
    protected $table = 'search_histories';
    protected $fillable = [
        'user_id', 'keyword', 'search_results', 'searched_at', 'ip'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
