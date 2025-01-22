<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


/**
 * App\Models\Video
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $url
 * @property string|null $previous
 * @property string|null $next
 * @property int $series_id
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string|null $formatted_published_at
 * @property-read string|null $formatted_for_humans_published_at
 * @property-read int|null $published_at_timestamp
 */

class Video extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'url', 'previous', 'next', 'series_id', 'published_at',];

    /** @var array<string> */
    protected array $dates = ['published_at'];

    /** @return string|null */
    public function getFormattedPublishedAtAttribute(): ?string
    {
        if ($this->published_at) {
            return Carbon::parse($this->published_at)->isoFormat('D [de] MMMM [de] YYYY');
        }
        return null;
    }

    /** @return string|null */
    public function getFormattedForHumansPublishedAtAttribute(): ?string
    {
        if ($this->published_at) {
            return Carbon::parse($this->published_at)->diffForHumans();
        }
        return null;
    }

    /** @return int|null */
    public function getPublishedAtTimestampAttribute(): ?int
    {
        if ($this->published_at) {
            return Carbon::parse($this->published_at)->timestamp;
        }
        return null;
    }
}
