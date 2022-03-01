<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\DataLoader;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

class YamlDataLoader
{
    private string $projectRootDirectory;

    public function __construct(KernelInterface $kernel)
    {
        $this->projectRootDirectory = $kernel->getProjectDir();
    }

    /**
     * @return array<int, array<mixed>>|null
     */
    public function getApiCustomOperationsDocumentationData(): ?array
    {
        return $this->loadDataFromFile($this->buildYamlFilePath('api', 'custom_operations_api_doc'));
    }

    /**
     * @return array<int, array<mixed>>|null
     */
    private function loadDataFromFile(string $filePath): ?array
    {
        $data = Yaml::parseFile($filePath);

        return \is_array($data) ? $data : null;
    }

    private function buildYamlFilePath(string $category, string $fileName): string
    {
        return \sprintf('%s/config/%s/%s.yaml', $this->projectRootDirectory, $category, $fileName);
    }
}
