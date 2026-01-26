<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Models\Photo;

use ArrayIterator;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use IteratorAggregate;

/**
 * @implements Arrayable<string, mixed>
 * @implements IteratorAggregate<int, Photo>
 */
final class PhotoCollection implements Arrayable, Countable, IteratorAggregate
{
    /**
     * @param  array<int, Photo>  $items
     */
    public function __construct(
        public readonly array $items,
        public readonly ?int $total = null,
        public readonly ?int $totalPages = null
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $items = [];
        foreach ($this->items as $item) {
            $items[] = $item->toArray();
        }

        return array_filter([
            'items' => $items,
            'total' => $this->total,
            'total_pages' => $this->totalPages,
        ], static function ($value): bool {
            return $value !== null;
        });
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return ArrayIterator<int, Photo>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }
}
