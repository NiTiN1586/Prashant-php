<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Controller;

use Jagaad\WitcherApi\Integration\Application\GitManagement\MessageHandler\GitlabEventHandler;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\MessageHandler\JiraTimeTrackerEventHandler;
use Jagaad\WitcherApi\Integration\Domain\DTO\EventContainer;
use Jagaad\WitcherApi\Integration\Domain\GitManagement\Message\GitEvent;
use Jagaad\WitcherApi\Integration\Domain\Interfaces\EventProcessorInterface;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Message\JiraTimeTrackerEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class WebhooksController extends AbstractController
{
    private LoggerInterface $logger;
    private EventProcessorInterface $eventProcessor;
    private SerializerInterface $serializer;

    public function __construct(
        LoggerInterface $logger,
        EventProcessorInterface $eventProcessor,
        SerializerInterface $serializer
    ) {
        $this->logger = $logger;
        $this->eventProcessor = $eventProcessor;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/webhooks/time_tracker", name="webhook_time_tracker", methods={"POST"})
     */
    public function trackEvent(Request $request, string $trackerToken): Response
    {
        try {
            if ('' === $trackerToken || $request->query->get('trackerToken') !== $trackerToken) {
                throw new \UnexpectedValueException('trackerToken doesn\'t match with request token or is not set');
            }

            $eventContainer = EventContainer::create(
                $this->serializer->deserialize(
                    $request->getContent(),
                    JiraTimeTrackerEvent::class,
                    'tracker_event'
                ),
                JiraTimeTrackerEventHandler::JIRA_ISSUE_TRACKER_EVENT
            );

            $this->eventProcessor->process($eventContainer);
        } catch (\InvalidArgumentException $exception) {
            // Do nothing
        } catch (ValidationFailedException $exception) {
            $this->logger->warning((string) $exception, ['error' => $exception]);
        } catch (\Throwable $exception) {
            $this->logger->error((string) $exception, ['error' => $exception]);
        }

        return new Response(null, Response::HTTP_ACCEPTED);
    }

    /**
     * @Route("/webhooks/git_management", name="webhook_git_management", methods={"POST"})
     */
    public function gitManagementEvent(Request $request, string $gitManagementToken): Response
    {
        try {
            if ('' === $gitManagementToken || $request->headers->get('X-Gitlab-Token') !== $gitManagementToken) {
                throw new \UnexpectedValueException('gitManagementToken doesn\'t match with request token or is not set');
            }

            $eventContainer = EventContainer::create(
                $this->serializer->deserialize(
                    $request->getContent(),
                    GitEvent::class,
                    'git_event'
                ),
                GitlabEventHandler::GITLAB_EVENT
            );

            $this->eventProcessor->process($eventContainer);
        } catch (\InvalidArgumentException $exception) {
            // Do nothing
        } catch (\Throwable $exception) {
            $this->logger->error((string) $exception, ['error' => $exception]);
        }

        return new Response(null, Response::HTTP_ACCEPTED);
    }
}
