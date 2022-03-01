<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Repository;

use Gitlab\Client;
use Jagaad\WitcherApi\ApiResource\GitEvent;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Converter\ConverterInterface;
use Jagaad\WitcherApi\Integration\Application\Transformer\RequestTransformerInterface;
use Jagaad\WitcherApi\Integration\Repository\GitManagement\EventReadApiInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class EventReadApiRepository implements EventReadApiInterface
{
    public const EVENT_PROJECT = 'PROJECT';
    public const EVENT_USER = 'USER';

    private Client $gitlabClient;
    private DenormalizerInterface $denormalizer;
    private RequestTransformerInterface $transformer;
    private ConverterInterface $converter;

    public function __construct(
        Client $gitlabClient,
        DenormalizerInterface $denormalizer,
        RequestTransformerInterface $transformer,
        ConverterInterface $converter
    ) {
        $this->gitlabClient = $gitlabClient;
        $this->denormalizer = $denormalizer;
        $this->transformer = $transformer;
        $this->converter = $converter;
    }

    /**
     * @return iterable<GitEvent>
     */
    public function getEvents(Request $request): iterable
    {
        $eventType = \strtoupper($request->getRequestParam('eventType', ''));
        $targetTypeId = (int) $request->getRequestParam('targetTypeId');

        if (!\in_array($eventType, [self::EVENT_PROJECT, self::EVENT_USER], true)) {
            throw new \InvalidArgumentException(\sprintf('Event type is not supported. Should be %s', \implode(',', [self::EVENT_PROJECT, self::EVENT_USER])));
        }

        if ($targetTypeId <= 0) {
            throw new \InvalidArgumentException('Target Type Id has incorrect value');
        }

        $apiClientResource = self::EVENT_USER === $eventType ? $this->gitlabClient->users() : $this->gitlabClient->projects();
        $events = $apiClientResource->events($targetTypeId, $this->transformer->transform($request));

        $authors = \array_column(
            \array_column($events, 'author'),
            'username',
            'id'
        );

        $this->converter->convert($authors, $events);

        foreach ($events as $event) {
            yield $this->denormalizer->denormalize(
                $event,
                GitEvent::class
            );
        }
    }
}
