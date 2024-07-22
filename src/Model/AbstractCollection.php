<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @template T
 *
 * @template-implements \ArrayAccess<int, T>
 * @template-implements \Iterator<int, T>
 */
abstract class AbstractCollection implements \ArrayAccess, \Countable, \Iterator
{
    /** @var array<int, T> */
    private array $items = [];

    private int $key;
    private int $count;

    /**
     * @return null|T
     */
    public function current()
    {
        return $this->offsetGet($this->key);
    }

    public function next(): void
    {
        ++$this->key;
    }

    public function key(): ?int
    {
        if (!$this->valid()) {
            return null;
        }

        return $this->key;
    }

    public function valid(): bool
    {
        return $this->key < $this->count;
    }

    public function rewind(): void
    {
        $this->key = 0;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * @param int $offset
     *
     * @return T
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new \RuntimeException(sprintf('Key "%s" does not exist in collection', $offset));
        }

        return $this->items[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        throw new \RuntimeException('Cannot set value on READ ONLY collection');
    }

    public function offsetUnset($offset): void
    {
        throw new \RuntimeException('Cannot unset value on READ ONLY collection');
    }

    public function count(): int
    {
        return $this->count;
    }

    protected function setItems(array $items): void
    {
        if ($this->items !== []) {
            throw new \LogicException('AbstractCollection::setItems can only be called once.');
        }

        $this->items = array_values($items);
        $this->count = count($items);
        $this->key = 0;
    }
}
