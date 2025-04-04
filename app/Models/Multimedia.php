<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $path
 * @property string $type
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $full_url
 * @property-read User $user
 * @method static create(array $array)
 * @method static findOrFail(mixed $file_id)
 */
class Multimedia extends Model
{
    use HasFactory;
    protected $casts = [];
    protected $fillable = ['id', 'title', 'description', 'path', 'type', 'user_id'];
    protected $appends = ['full_url'];

    public function getFullUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Anonymous',
            'profile_photo_url' => asset('default-avatar.png')
        ]);
    }

    /** Esborrat físic del fitxer en cascada */
    protected static function booted(): void
    {
        static::deleting(function (Multimedia $multimedia) {
            if (Storage::disk('public')->exists($multimedia->path)) {
                Storage::disk('public')->delete($multimedia->path);
            }
        });
    }
}
