<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Source extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function listOptions(): Collection
    {
        return self::query()
            ->whereHas('articles')
            ->get()
            ->map(fn (self $source) => [
                'label' => $source->name,
                'value' => $source->id,
            ]);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
