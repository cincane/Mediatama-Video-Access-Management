<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'video_id', 'status', 'valid_until'])]
class VideoAccess extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'valid_until' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'approved' && $this->valid_until && $this->valid_until->isFuture();
    }
}
