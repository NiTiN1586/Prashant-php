<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\ORMException;
use Jagaad\WitcherApi\Entity\Label as LabelEntity;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Label;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationInterface;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\LabelReadApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\LabelRepository;
use Jagaad\WitcherApi\Utils\ValidationConstraintListConvertUtils;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LabelMigration implements MigrationInterface
{
    private LabelReadApiInterface $labelReadApiRepository;
    private LoggerInterface $logger;
    private RendererInterface $renderer;
    private LabelRepository $labelRepository;
    private ValidatorInterface $validator;

    public function __construct(
        LabelReadApiInterface $labelReadApiRepository,
        LabelRepository $labelRepository,
        LoggerInterface $logger,
        RendererInterface $renderer,
        ValidatorInterface $validator
    ) {
        $this->labelReadApiRepository = $labelReadApiRepository;
        $this->logger = $logger;
        $this->renderer = $renderer;
        $this->labelRepository = $labelRepository;
        $this->validator = $validator;
    }

    public static function getPriority(): int
    {
        return \count(self::MIGRATION_PRIORITY) - \array_search(self::MIGRATE_LABEL, self::MIGRATION_PRIORITY, true);
    }

    /**
     * {@inheritdoc}
     */
    public function migrate(Request $request): void
    {
        $this->renderer->renderNotice('Only all labels migration is available.');
        $this->process();
    }

    /**
     * {@inheritdoc}
     */
    public function migrateAll(Request $request): void
    {
        $this->process();
    }

    public function getAlias(): string
    {
        return self::MIGRATE_LABEL;
    }

    private function process(): void
    {
        $current = 0;
        $buffer = 1;

        while ($labelContainer = $this->labelReadApiRepository->getAllPaginated($current, self::BATCH_SIZE)) {
            /** @var Label $labelContainer */
            if (0 === \count($labelContainer->getValues())) {
                break;
            }

            $this->save(\array_unique($labelContainer->getValues()), $buffer);

            if ($labelContainer->isLast()) {
                break;
            }

            $current += self::BATCH_SIZE;
        }

        $this->labelRepository->flush();
    }

    /**
     * @param string[] $labels
     */
    private function save(array $labels, int &$buffer): void
    {
        $existingLabels = $this->labelRepository->findLabelAsStringListByNames($labels, false);

        foreach ($labels as $label) {
            try {
                if (\in_array($label, $existingLabels, true)) {
                    $this->renderer->renderNotice(\sprintf('Label \'%s\' already exists. Skipping...', $label));

                    continue;
                }

                $this->renderer->renderNotice(\sprintf('Persisting label \'%s\'...', $label));

                $createdLabel = LabelEntity::create($label);
                $errors = $this->validator->validate($createdLabel);

                if ($errors->count() > 0) {
                    $this->renderer->renderError(
                        ValidationConstraintListConvertUtils::convertConstraintListToString($errors)
                    );

                    continue;
                }

                $this->labelRepository->save($createdLabel, false);

                if (0 === $buffer % self::BATCH_SIZE) {
                    $this->labelRepository->flush();
                    $buffer = 0;
                }

                ++$buffer;
            } catch (ORMException|UniqueConstraintViolationException $exception) {
                $this->labelRepository->restoreEntityManager();

                $this->renderer->renderError(
                    \sprintf('Exception occurred during migration of \'%s\'. Please see logs for details', $label)
                );

                $this->logger->error($exception->getMessage(), ['error' => $exception]);
            } catch (\Throwable $exception) {
                $this->logger->error($exception->getMessage(), ['error' => $exception]);

                $this->renderer->renderError(
                    \sprintf('Exception occurred during migration of \'%s\'. Please see logs for details', $label)
                );
            }
        }
    }
}
