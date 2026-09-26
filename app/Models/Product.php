<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'description',
        'image',
        'category_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function imageUrl(): string
    {
        if (filled($this->image)) {
            return str_starts_with($this->image, 'http')
                ? $this->image
                : asset('storage/'.$this->image);
        }

        $seed = rawurlencode(strtolower($this->name));

        return "https://picsum.photos/seed/{$seed}/640/480";
    }
}
