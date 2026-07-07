<?php

namespace App\Models;

use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Link extends Model
{
    protected $fillable = [
        'user_id',
        'original_url',
        'short_url',
        'click_count',
    ];

    protected $withCount = [
        'clicks'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Link $link) {
            $link->short_url = self::generateUniqueShortLink();
        });
    }

    public static function generateUniqueShortLink(int $length = 6): string
    {
        do {
            $shortLink = Str::random($length);
        } while (self::where('short_url', $shortLink)->exists());

        return $shortLink;
    }
}
