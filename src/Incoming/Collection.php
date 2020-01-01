<?php

namespace Ycs77\LaravelLineBot\Incoming;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

class Collection implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * The collection items.
     *
     * @var \Ycs77\LaravelLineBot\Incoming\IncomingMessage[]
     */
    protected $items = [];

    /**
     * Add a incoming message instance.
     *
     * @return self
     */
    public function add(IncomingMessage $incomingMessage)
    {
        $this->items[] = $incomingMessage;

        return $this;
    }

    /**
     * Get the all items.
     *
     * @return \Ycs77\LaravelLineBot\Incoming\IncomingMessage[]
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Get the all items.
     *
     * @return \Ycs77\LaravelLineBot\Incoming\IncomingMessage|null
     */
    public function getFallback()
    {
        $messages = array_values(array_filter($this->items, function (IncomingMessage $incomingMessage) {
            return $incomingMessage->isFallback();
        }));

        return $messages[0] ?? null;
    }

    /**
     * Get the items count.
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->items[$key];
    }

    /**
     * Set the item at a given offset.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }

    /**
     * Get the items external iterator.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
