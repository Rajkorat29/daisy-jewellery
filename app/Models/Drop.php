<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Drop extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'title',
        'subtitle',
        'drop_date',
        'drop_time',
        'metal_label',
        'weight_label',
        'price_label',
        'image',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Resolve the associated Product by foreign key, slug, or title match.
     */
    public function resolvedProduct(): ?Product
    {
        if ($this->relationLoaded('product') && $this->product) {
            return $this->product;
        }

        if ($this->product_id) {
            $found = Product::with('images')->find($this->product_id);
            if ($found) {
                return $found;
            }
        }

        $slug = Str::slug($this->title);

        return Product::with('images')
            ->where('slug', $slug)
            ->orWhere('name', $this->title)
            ->first();
    }

    /**
     * Resolve a real jewellery image URL for this drop.
     */
    public function imageUrl(): string
    {
        if (! empty($this->image)) {
            return asset('storage/' . ltrim($this->image, '/'));
        }

        $product = $this->resolvedProduct();
        if ($product && $cover = $product->coverImage()) {
            return asset('storage/' . ltrim($cover, '/'));
        }

        $slug = Str::slug($this->title);
        $candidate = "products/{$slug}.jpg";
        if (file_exists(public_path("storage/{$candidate}")) || file_exists(storage_path("app/public/{$candidate}"))) {
            return asset("storage/{$candidate}");
        }

        $haystack = strtolower($this->title . ' ' . $this->subtitle . ' ' . $this->metal_label);

        if (str_contains($haystack, 'earring') || str_contains($haystack, 'jhumka') || str_contains($haystack, 'chaand') || str_contains($haystack, 'drop')) {
            return asset('storage/products/chaand-drop-earrings.jpg');
        }
        if (str_contains($haystack, 'bangle') || str_contains($haystack, 'kada') || str_contains($haystack, 'cuff')) {
            return asset('storage/products/meena-bangle-set.jpg');
        }
        if (str_contains($haystack, 'necklace') || str_contains($haystack, 'haar') || str_contains($haystack, 'choker') || str_contains($haystack, 'pendant')) {
            return asset('storage/products/rani-haar-necklace.jpg');
        }
        if (str_contains($haystack, 'pearl') || str_contains($haystack, 'moti') || str_contains($haystack, 'locket')) {
            return asset('storage/products/moti-strand.jpg');
        }

        return asset('storage/products/bandhan-ring-stack.jpg');
    }

    /**
     * Resolve the storefront link for this drop.
     */
    public function shopUrl(): string
    {
        $product = $this->resolvedProduct();
        if ($product) {
            return route('product.show', $product->slug);
        }

        $haystack = strtolower($this->title . ' ' . $this->subtitle . ' ' . $this->metal_label);

        if (str_contains($haystack, 'earring') || str_contains($haystack, 'jhumka')) {
            return route('earrings');
        }
        if (str_contains($haystack, 'bangle') || str_contains($haystack, 'kada') || str_contains($haystack, 'cuff')) {
            return route('bangles');
        }
        if (str_contains($haystack, 'necklace') || str_contains($haystack, 'haar') || str_contains($haystack, 'choker')) {
            return route('necklaces');
        }
        if (str_contains($haystack, 'pearl') || str_contains($haystack, 'moti')) {
            return route('pearls');
        }

        return route('rings');
    }
}
