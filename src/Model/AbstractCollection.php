<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
abstract class AbstractCollection implements \ArrayAccess, \Countable, \Iterator
{
    private array $items = [];
    private int $key;
    private int $count;

    public function current()
    {
        return $this->items[$this->key];
    }

    public function next(): void
    {
        ++$this->key;
    }

    public function key()
    {
        if ($this->key >= $this->count) {
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

    public function offsetGet($offset)
    {
        if (!isset($this->items[$offset])) {
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
