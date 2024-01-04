<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\{Builder, Model};

class Article extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = [
        'source',
        'category',
    ];

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters, function (Builder $query, array $filters) {
            foreach ($filters as $prop => $value) {
                if (empty($value)) {
                    continue;
                }

                match ($prop) {
                    'language' => $query->whereIn('language', $value),
                    'source'   => $query->whereIn('source_id', $value),
                    'category' => $query->whereIn('category_id', $value),
                    default    => $query->where($prop, 'LIKE', "%$value%"),
                };
            }
        });
    }

    public function scopeFilterByPreference(Builder $query): Builder
    {
        $preference = auth()->user()->preference;

        return $query
            ->when(
                $preference->languages,
                fn (Builder $preferenceQuery) => $preferenceQuery->whereIn('language', $preference->languages)
            )->when(
                $preference->sources,
                fn (Builder $preferenceQuery) => $preferenceQuery->whereIn('source_id', $preference->sources)
            )->when(
                $preference->categories,
                fn (Builder $preferenceQuery) => $preferenceQuery->whereIn('category_id', $preference->categories)
            );
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
