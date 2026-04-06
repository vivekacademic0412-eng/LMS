<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\CarbonInterface;

class DemoTaskSubmission extends Model
{
    use HasFactory;

    public const SHARED_DEMO_COOLDOWN_SECONDS = 30;

    protected $fillable = [
        'demo_task_assignment_id',
        'participant_name',
        'participant_email',
        'participant_phone',
        'video_rating',
        'answer_text',
        'file_path',
        'file_name',
        'file_mime',
        'file_size',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'video_rating' => 'integer',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(DemoTaskAssignment::class, 'demo_task_assignment_id');
    }

    public function sharedCooldownEndsAt(): ?CarbonInterface
    {
        return $this->submitted_at?->copy()->addSeconds(self::SHARED_DEMO_COOLDOWN_SECONDS);
    }

    public function sharedCooldownRemainingSeconds(?CarbonInterface $now = null): int
    {
        $endsAt = $this->sharedCooldownEndsAt();
        $now ??= now();

        if (! $endsAt || $now->greaterThanOrEqualTo($endsAt)) {
            return 0;
        }

        return (int) $now->diffInSeconds($endsAt);
    }

    public function isInSharedCooldown(?CarbonInterface $now = null): bool
    {
        return $this->sharedCooldownRemainingSeconds($now) > 0;
    }
}
