<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


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
 * @method static findOrFail($id)
 * @method static create(array $data)
 * @method static whereNotNull(string $string)
 * @method static updateOrCreate(array $array, array $video)
 * @method static first()
 * @method static skip(int $int)
 */

class Video extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'url', 'previous', 'next', 'series_id', 'published_at', 'user_id'];

    // Relació amb l'usuari
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @var array<string> */
    protected array $dates = ['published_at'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

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

    /**
     * Relació 1:N amb la Serie.
     */
    public function serie(): BelongsTo
    {
        return $this->belongsTo(Serie::class, 'series_id');
    }

}
