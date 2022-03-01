<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Repository;

use Jagaad\WitcherApi\Integration\Application\Exception\IntegrationApiRequestException;
use Jagaad\WitcherApi\Integration\Domain\ApiClient\ApiClientInterface;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Label;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\LabelReadApiInterface;

final class LabelReadApiRepository implements LabelReadApiInterface
{
    private ApiClientInterface $apiClient;

    public function __construct(ApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function getAllPaginated(int $current = 0, int $maxItems = 20): Label
    {
        try {
            $labels = $this->apiClient->request(
                '/label',
                Label::class,
                null,
                [
                    'query' => ['startAt' => $current, 'maxResults' => $maxItems],
                ]
            );

            if (!$labels instanceof Label) {
                throw new \LogicException('Label API call returned unexpected result type.');
            }

            return $labels;
        } catch (\LogicException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            throw IntegrationApiRequestException::create($exception->getMessage(), 0, $exception);
        }
    }
}
