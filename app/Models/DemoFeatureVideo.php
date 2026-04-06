<?php

namespace App\Models;

use App\Support\DemoVideoRatio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DemoFeatureVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'position',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_mime',
        'file_size',
        'youtube_url',
        'youtube_id',
        'video_ratio',
        'uploaded_by',
    ];

    protected $casts = [
        'position' => 'integer',
        'file_size' => 'integer',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getHasUploadedVideoAttribute(): bool
    {
        return filled($this->file_path) && Storage::disk('local')->exists($this->file_path);
    }

    public function getHasYoutubeVideoAttribute(): bool
    {
        return filled($this->youtube_id);
    }

    public function getEmbedUrlAttribute(): ?string
    {
        if (! $this->youtube_id) {
            return null;
        }

        return 'https://www.youtube-nocookie.com/embed/'.$this->youtube_id.'?rel=0';
    }

    public function getWatchUrlAttribute(): ?string
    {
        if (! $this->youtube_id) {
            return null;
        }

        return 'https://www.youtube.com/watch?v='.$this->youtube_id;
    }

    public function getResolvedVideoRatioAttribute(): string
    {
        return DemoVideoRatio::normalize($this->video_ratio, DemoVideoRatio::LANDSCAPE);
    }

    public function getVideoRatioLabelAttribute(): string
    {
        return DemoVideoRatio::label($this->video_ratio, DemoVideoRatio::LANDSCAPE);
    }
}
