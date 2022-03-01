<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class JagaadUserProviderExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $pluginConfiguration = current($configs);
        $this->setParameters($pluginConfiguration, $container);
    }

    private function setParameters(array $pluginConfiguration, ContainerBuilder $container): void
    {
        $loginRoute = $pluginConfiguration['login_route'];

        if (null === $loginRoute) {
            throw new InvalidConfigurationException('No login route provided');
        }

        $container->setParameter('jagaad.user_provider_bundle.login_route', $loginRoute);

        $container->setParameter(
            'jagaad.user_provider_bundle.client_jagaad_user_api_token',
            $pluginConfiguration['client_jagaad_user_api_token'] ?? null
        );

        $postLoginRedirectRoute = $pluginConfiguration['post_login_redirect_route'];

        if (null === $postLoginRedirectRoute) {
            throw new InvalidConfigurationException('No post login redirect route provided');
        }

        $container->setParameter('jagaad.user_provider_bundle.post_login_redirect_route', $postLoginRedirectRoute);

        $userApiBaseUrl = $pluginConfiguration['user_api'] ?? null && $pluginConfiguration['user_api']['host'] ?? null
                ? $pluginConfiguration['user_api']['host']
                : null;

        if (null === $userApiBaseUrl) {
            throw new InvalidConfigurationException('No user api base host configured');
        }

        $container->setParameter('jagaad.user_provider_bundle.user_api_base_url', $userApiBaseUrl);

        $googleAuthenticationCallbackRoute =
            $pluginConfiguration['google_authentication'] ?? null
            && $pluginConfiguration['google_authentication']['callback_route'] ?? null
                ? $pluginConfiguration['google_authentication']['callback_route']
                : null;

        $googleAuthenticationCallbackRoute ??= $container->getParameter('jagaad.user_provider_bundle.default_auth_callback_route');

        if (null === $googleAuthenticationCallbackRoute) {
            throw new InvalidConfigurationException('No Google authentication callback route provided');
        }

        $container->setParameter('jagaad.user_provider_bundle.auth_callback_route', $googleAuthenticationCallbackRoute);
    }
}
