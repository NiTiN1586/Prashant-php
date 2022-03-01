<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Command;

use Jagaad\UserApi\Security\Authentication\ApiTokenGeneratorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ApplicationApiTokenCreationCommand extends Command
{
    private const TOKEN_CREATE = 'create';
    private const TOKEN_ROTATE = 'rotate';

    private ApiTokenGeneratorInterface $apiTokenGenerator;

    protected static $defaultName = 'api_token:create';

    public function __construct(ApiTokenGeneratorInterface $apiTokenGenerator, string $name = null)
    {
        parent::__construct($name);

        $this->apiTokenGenerator = $apiTokenGenerator;
    }

    protected function configure(): void
    {
        $this->addOption(
            self::TOKEN_CREATE,
            null,
            InputOption::VALUE_REQUIRED,
            'Create API token'
        )->addOption(
            self::TOKEN_ROTATE,
            null,
            InputOption::VALUE_REQUIRED,
            'Rotate api token for application'
        )
            ->setDescription('Invokes API Token generation')
            ->setHelp(<<<EOT
                <info>%command.full_name% --create="Witcher"</info> - generate token for <comment>Witcher</comment> application
                <info>%command.full_name% --rotate="Witcher"</info> - rotates token for <comment>Witcher</comment> application
            EOT);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $application = $input->getOption(self::TOKEN_CREATE);

        if (is_string($application) && '' !== $application) {
            $token = $this->apiTokenGenerator->generateTokenForApp($application);

            $io->success(
                \sprintf(
                    'Token \'%s\' was generated for application \'%s\'',
                    $token,
                    $application
                )
            );

            return self::SUCCESS;
        }

        $application = $input->getOption(self::TOKEN_ROTATE);

        if (is_string($application) && '' !== $application) {
            $token = $this->apiTokenGenerator->rotateTokenForApp($application);

            $io->success(
                \sprintf(
                    'Token rotated to \'%s\' for application \'%s\'',
                    $token,
                    $application
                )
            );

            return self::SUCCESS;
        }

        $io->getErrorStyle()
            ->error(
                \sprintf(
                    'One of the following options should be passed \'--%s="app name"\', \'--%s="app name"\'',
                    self::TOKEN_CREATE,
                    self::TOKEN_ROTATE
                )
        );

        return self::FAILURE;
    }
}
