<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Jagaad\WitcherApi\ApiResource\GitEvent;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\Exception\DataProviderException;
use Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Repository\EventReadApiRepository;
use Jagaad\WitcherApi\Integration\Repository\GitManagement\EventReadApiInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class GitEventDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private EventReadApiInterface $eventReadApiRepository;
    private RequestStack $requestStack;
    private LoggerInterface $logger;

    public function __construct(
        EventReadApiInterface $eventReadApiRepository,
        RequestStack $requestStack,
        LoggerInterface $logger
    ) {
        $this->eventReadApiRepository = $eventReadApiRepository;
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }

    /**
     * @return iterable<GitEvent>
     *
     * @throws DataProviderException
     */
    public function getCollection(string $resourceClass, string $operationName = null): iterable
    {
        try {
            $request = $this->requestStack->getCurrentRequest();

            if (null === $request) {
                throw new BadRequestHttpException();
            }

            $dateBefore = null !== $request->query->get('before')
                ? new \DateTimeImmutable($request->query->get('before'))
                : null;

            $dateAfter = null !== $request->query->get('after')
                ? new \DateTimeImmutable($request->query->get('after'))
                : null;

            if (!$this->eventReadApiRepository instanceof EventReadApiRepository) {
                throw DataProviderException::create('Incorrect instance type of read repository passed');
            }

            return $this->eventReadApiRepository->getEvents(
                new Request(
                    [
                        'before' => $dateBefore,
                        'after' => $dateAfter,
                        'eventType' => $request->attributes->get('eventType'),
                        'targetTypeId' => (int) $request->attributes->get('targetTypeId'),
                    ],
                    $request->query->getInt('page', 1)
                )
            );
        } catch (\RuntimeException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['error' => $exception]);
            throw DataProviderException::create('Git commits can\'t be retrieved due to error. Please see logs for details');
        }
    }

    /**
     * @param array<mixed> $context
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return GitEvent::class === $resourceClass;
    }
}
