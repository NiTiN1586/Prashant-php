<?php

declare(strict_types=1);

namespace Jagaad\UserApi\DataLoader;

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
     * @return mixed
     */
    public function getApiCustomOperationsDocumentationData()
    {
        return $this->loadDataFromFile($this->buildYamlFilePath('api', 'custom_operations_api_doc'));
    }

    /**
     * @return mixed
     */
    private function loadDataFromFile(string $filePath)
    {
        return Yaml::parseFile($filePath);
    }

    private function buildYamlFilePath(string $category, string $fileName): string
    {
        return sprintf('%s/config/%s/%s.yaml', $this->projectRootDirectory, $category, $fileName);
    }
}
