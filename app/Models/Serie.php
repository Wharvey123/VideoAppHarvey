<?php

namespace App\Models;

use Database\Factories\SeriesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Serie extends Model
{
    use HasFactory;

    // Tell Laravel which factory to use.
    protected static function newFactory(): SeriesFactory
    { return SeriesFactory::new(); }

    protected $fillable = ['title', 'description', 'image', 'user_name', 'user_photo_url', 'published_at'];

    public function testedBy(): string
    { return self::class . 'Test'; }

    public function videos(): HasMany
    { return $this->hasMany(Video::class, 'series_id'); }

    public function getFormattedCreatedAtAttribute(): ?string
    { return $this->created_at ? Carbon::parse($this->created_at)->isoFormat('D [de] MMMM [de] YYYY') : null;}

    public function getFormattedForHumansCreatedAtAttribute(): ?string
    { return $this->created_at ? Carbon::parse($this->created_at)->diffForHumans() : null; }

    public function getCreatedAtTimestampAttribute(): ?int
    { return $this->created_at ? Carbon::parse($this->created_at)->timestamp : null; }
}
