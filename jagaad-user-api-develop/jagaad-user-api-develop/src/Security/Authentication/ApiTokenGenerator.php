<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Security\Authentication;

use Doctrine\ORM\EntityManagerInterface;
use Jagaad\UserApi\Entity\ApiToken;
use Jagaad\UserApi\Exception\AlreadyExistsException;
use Jagaad\UserApi\Exception\BaseJagaadUserException;

final class ApiTokenGenerator implements ApiTokenGeneratorInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws BaseJagaadUserException
     */
    public function generateTokenForApp(string $application): string
    {
        $existingToken = $this->entityManager
            ->getRepository(ApiToken::class)
            ->findOneBy(['application' => $application]);

        if (null !== $existingToken) {
            throw AlreadyExistsException::create(\sprintf('Api token for %s already exists. Rotate token if you want to change it', $application));
        }

        $token = new ApiToken($application);

        $this->save($token);

        return $token->getUsername();
    }

    public function rotateTokenForApp(string $application): string
    {
        $token = $this->entityManager
            ->getRepository(ApiToken::class)
            ->findOneBy(['application' => $application]);

        if (null === $token) {
            throw new \InvalidArgumentException(\sprintf('Token does not exist for application %s', $application));
        }

        $token->rotate();
        $this->save($token);

        return $token->getUsername();
    }

    private function save(ApiToken $apiToken): void
    {
        $this->entityManager->persist($apiToken);
        $this->entityManager->flush();
    }
}
