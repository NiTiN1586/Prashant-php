<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('jagaad_user_provider');

        $treeBuilder
            ->getRootNode()->children()
                ->arrayNode('user_api')->children()
                    ->scalarNode('host')->end()
                ->end()->end()
                ->arrayNode('google_authentication')->children()
                    ->scalarNode('callback_route')->end()
                ->end()->end()
                ->scalarNode('login_route')->end()
                ->scalarNode('client_jagaad_user_api_token')->end()
                ->scalarNode('post_login_redirect_route')->end()
            ->end();

        return $treeBuilder;
    }
}
