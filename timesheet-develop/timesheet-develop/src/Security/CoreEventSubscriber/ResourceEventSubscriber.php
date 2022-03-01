<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\CoreEventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Doctrine\ORM\EntityManagerInterface;
use Jagaad\WitcherApi\Exception\ResourceAccessDeniedException;
use Jagaad\WitcherApi\Security\AccessCheckerContextInterface;
use Jagaad\WitcherApi\Security\Event\GraphQLEvent;
use Jagaad\WitcherApi\Security\SupportedResourceInterface;
use Jagaad\WitcherApi\Utils\ClassHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ResourceEventSubscriber implements EventSubscriberInterface
{
    private AccessCheckerContextInterface $accessCheckerContext;
    private SupportedResourceInterface $supportedResource;
    private EntityManagerInterface $entityManager;

    public function __construct(
        AccessCheckerContextInterface $accessCheckerContext,
        SupportedResourceInterface $supportedResource,
        EntityManagerInterface $entityManager
    ) {
        $this->accessCheckerContext = $accessCheckerContext;
        $this->supportedResource = $supportedResource;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => [
                ['preWriteApiAction', EventPriorities::PRE_WRITE],
                ['postReadApiAction', EventPriorities::POST_READ],
            ],
            GraphQLEvent::class => [['graphQLAction', 20]],
        ];
    }

    public function postReadApiAction(ViewEvent $event): void
    {
        if (Request::METHOD_GET !== $event->getRequest()->getMethod()) {
            return;
        }

        $this->preWriteApiAction($event);
    }

    public function preWriteApiAction(ViewEvent $event): void
    {
        $data = $event->getControllerResult();

        $this->processResource(
            $data,
            $event->getRequest()->attributes->get('_api_resource_class'),
            $event->getRequest()->getMethod(),
            $this->entityManager->getUnitOfWork()->getOriginalEntityData($data)
        );
    }

    public function graphQLAction(GraphQLEvent $requestEvent): void
    {
        $this->processResource(
            $requestEvent->getData(),
            $requestEvent->getResourceClass(),
            $requestEvent->getOperationName(),
            $requestEvent->getOriginalData()
        );
    }

    /**
     * @param array<mixed> $originalData
     */
    private function processResource(?object $resource, string $resourceClass, string $operation, array $originalData = []): void
    {
        $entity = $resource;

        if (null === $entity) {
            $entity = $resourceClass;
        }

        if (!$this->supportedResource->supports($entity)) {
            return;
        }

        if (!$this->accessCheckerContext->hasAccess($entity, $operation, $originalData)) {
            throw ResourceAccessDeniedException::create(\sprintf('You don\'t have permissions to invoke operation on resource \'%s\'', ClassHelper::getClass($entity)));
        }
    }
}
