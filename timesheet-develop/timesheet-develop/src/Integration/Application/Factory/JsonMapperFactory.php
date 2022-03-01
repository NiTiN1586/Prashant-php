<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\Factory;

use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueField;

final class JsonMapperFactory
{
    public function __invoke(): \JsonMapper
    {
        $mapper = new \JsonMapper();
        $mapper->undefinedPropertyHandler = static function (object $filledObject, string $propertyName, $propertyValue): void {
            if ($filledObject instanceof IssueField && 1 === \preg_match('/^(customfield_\d+)$/', $propertyName)) {
                $filledObject->addCustomField($propertyName, $propertyValue);
            }
        };

        return $mapper;
    }
}
