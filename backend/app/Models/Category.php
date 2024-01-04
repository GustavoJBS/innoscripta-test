<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function listOptions(): Collection
    {
        return self::query()->get()
            ->map(fn (self $category) => [
                'label' => str($category->name)->replace('-', ' ')->title(),
                'value' => $category->id,
            ]);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
