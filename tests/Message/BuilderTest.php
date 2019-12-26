<?php

namespace Ycs77\LaravelLineBot\Test\Message;

use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\Response;
use LINE\LINEBot\TemplateActionBuilder;
use Mockery as m;
use Ycs77\LaravelLineBot\Action;
use Ycs77\LaravelLineBot\Exceptions\LineRequestErrorException;
use Ycs77\LaravelLineBot\LineBot;
use Ycs77\LaravelLineBot\Message\Builder;
use Ycs77\LaravelLineBot\Message\Template;
use Ycs77\LaravelLineBot\Message\Text;
use Ycs77\LaravelLineBot\QuickReplyBuilder;
use Ycs77\LaravelLineBot\Test\TestCase;

class BuilderTest extends TestCase
{
    public function messageProvider()
    {
        return [
            'text' => [
                'text',
                ['message'],
                Text::class,
            ],
            'template' => [
                'template',
                ['altText', function () {
                    //
                }],
                Template::class,
            ],
        ];
    }

    /**
     * @dataProvider messageProvider
     */
    public function testAddMessage($name, $arguments, $expectedClass)
    {
        /** @var \Ycs77\LaravelLineBot\LineBot $bot */
        $bot = m::mock(LineBot::class);

        $builder = new Builder($bot);

        $builder->{$name}(...$arguments);

        $this->assertInstanceOf($expectedClass, $builder->getMessage());
    }

    public function testAddQuickReplyForQuickReplyBuilder()
    {
        /** @var \Ycs77\LaravelLineBot\LineBot $bot */
        $bot = m::mock(LineBot::class);

        /** @var \Ycs77\LaravelLineBot\QuickReplyBuilder $quickReplyBuilder */
        $quickReplyBuilder = m::mock(QuickReplyBuilder::class);

        $builder = new Builder($bot);

        $builder->quickReply($quickReplyBuilder);

        $this->assertSame($quickReplyBuilder, $builder->getQuickReply());
    }

    public function testAddQuickReplyForClosure()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Action $action */
        $action = m::mock(Action::class);
        $action->shouldReceive('message')
            ->with('hello', null)
            ->once()
            ->andReturn(m::mock(TemplateActionBuilder::class));

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\LineBot $bot */
        $bot = m::mock(LineBot::class);
        $bot->shouldReceive('action')
            ->once()
            ->andReturn($action);

        $builder = new Builder($bot);

        $builder->quickReply(function (QuickReplyBuilder $action) {
            $action->message('hello');
        });

        $this->assertInstanceOf(QuickReplyBuilder::class, $builder->getQuickReply());
    }

    public function testGetMessageBuilder()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\LineBot $bot */
        $bot = m::mock(LineBot::class);

        $builder = new Builder($bot);
        $builder->text('message');

        $this->assertInstanceOf(MessageBuilder::class, $builder->getMessageBuilder());
    }

    public function testGetMessageBuilderAndSetQuickReply()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Action $action */
        $action = m::mock(Action::class);
        $action->shouldReceive('message')
            ->with('hello', null)
            ->once()
            ->andReturn(m::mock(TemplateActionBuilder::class));

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\LineBot $bot */
        $bot = m::mock(LineBot::class);
        $bot->shouldReceive('action')
            ->once()
            ->andReturn($action);

        $builder = new Builder($bot);
        $builder->text('message');
        $builder->quickReply(function (QuickReplyBuilder $action) {
            $action->message('hello');
        });

        $this->assertInstanceOf(MessageBuilder::class, $builder->getMessageBuilder());
    }

    public function testGetMessageBuilderThrowException()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\LineBot $bot */
        $bot = m::mock(LineBot::class);

        $builder = new Builder($bot);

        $this->expectException(LineRequestErrorException::class);
        $this->expectExceptionMessage('The builder message must implements Ycs77\LaravelLineBot\Contracts\Message interface');

        $builder->getMessageBuilder();
    }

    public function testReply()
    {
        $messageBuilder = m::mock(MessageBuilder::class);

        $response = m::mock(Response::class);
        $response->shouldReceive('isSucceeded')
            ->once()
            ->andReturn(true);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\LineBot $bot */
        $bot = m::mock(LineBot::class);
        $bot->shouldReceive('reply')
            ->with($messageBuilder)
            ->once()
            ->andReturn($response);

        $builder = m::mock(Builder::class . '[getMessageBuilder]', [$bot]);
        $builder->shouldReceive('getMessageBuilder')
            ->once()
            ->andReturn($messageBuilder);

        $this->assertSame($bot, $builder->reply());
    }

    public function testReplyThrowException()
    {
        $messageBuilder = m::mock(MessageBuilder::class);

        $response = m::mock(Response::class);
        $response->shouldReceive('isSucceeded')
            ->once()
            ->andReturn(false);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\LineBot $bot */
        $bot = m::mock(LineBot::class);
        $bot->shouldReceive('reply')
            ->with($messageBuilder)
            ->once()
            ->andReturn($response);
        $bot->shouldReceive('getConfig')
            ->once()
            ->andReturn(false);

        $builder = m::mock(Builder::class . '[getMessageBuilder]', [$bot]);
        $builder->shouldReceive('getMessageBuilder')
            ->once()
            ->andReturn($messageBuilder);

        $this->expectException(LineRequestErrorException::class);
        $this->expectExceptionMessage('The request error!');

        $builder->reply();
    }
}
