<?php

namespace Ycs77\LaravelLineBot\Test\Incoming;

use Mockery as m;
use Ycs77\LaravelLineBot\Incoming\Collection;
use Ycs77\LaravelLineBot\Incoming\IncomingMessage;
use Ycs77\LaravelLineBot\Test\TestCase;

class CollectionTest extends TestCase
{
    public function testAddMessage()
    {
        /** @var \Ycs77\LaravelLineBot\Incoming\IncomingMessage $message */
        $message = m::mock(IncomingMessage::class);
        $collection = new Collection();

        $this->assertCount(0, $collection);

        $collection->add($message);

        $this->assertCount(1, $collection);
    }

    public function testGetAllMessages()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Incoming\IncomingMessage $message1 */
        $message1 = m::mock(IncomingMessage::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Incoming\IncomingMessage $message2 */
        $message2 = m::mock(IncomingMessage::class);

        $messages = [$message1, $message2];

        $collection = new Collection();
        $collection
            ->add($message1)
            ->add($message2);

        $this->assertCount(2, $collection);
        $this->assertSame($messages, $collection->all());
    }

    public function testArrayAccess()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Incoming\IncomingMessage $message1 */
        $message1 = m::mock(IncomingMessage::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Incoming\IncomingMessage $message2 */
        $message2 = m::mock(IncomingMessage::class);

        $collection = new Collection();
        $collection
            ->add($message1)
            ->add($message2);

        // test array offset exists
        $this->assertTrue(isset($collection[0]));
        $this->assertFalse(isset($collection[2]));

        // test array get
        $this->assertSame($message1, $collection[0]);

        // test array set
        $this->assertFalse(isset($collection[2]));
        $collection[2] = $message1;
        $this->assertTrue(isset($collection[2]));
        $this->assertFalse(isset($collection[3]));
        $collection[] = $message1;
        $this->assertTrue(isset($collection[3]));

        // test array unset
        $this->assertTrue(isset($collection[0]));
        unset($collection[0]);
        $this->assertFalse(isset($collection[0]));
    }

    public function testIterateMessages()
    {
        /** @var \Ycs77\LaravelLineBot\Incoming\IncomingMessage $message */
        $message = m::mock(IncomingMessage::class);
        $collection = new Collection();

        $collection
            ->add($message)
            ->add($message);

        $this->assertCount(2, $collection);

        foreach ($collection as $message) {
            $this->assertInstanceOf(IncomingMessage::class, $message);
        }
    }

    public function testGetFallbackMessage()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Incoming\IncomingMessage $message1 */
        $message1 = m::mock(IncomingMessage::class);
        $message1->shouldReceive('isFallback')
            ->once()
            ->andReturn(true);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Incoming\IncomingMessage $message2 */
        $message2 = m::mock(IncomingMessage::class);
        $message2->shouldReceive('isFallback')
            ->once()
            ->andReturn(false);

        $collection = new Collection();

        $collection
            ->add($message1)
            ->add($message2);

        $this->assertSame($message1, $collection->getFallback());
    }
}
