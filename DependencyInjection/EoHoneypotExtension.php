<?php

/*
 * This file is part of the EoHoneypotBundle package.
 *
 * (c) Eymen Gunay <eymen@egunay.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eo\HoneypotBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EoHoneypotExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        // Set db
        if ($config['storage']['database']['enabled']) {
            $db = match ($config['storage']['database']['driver']) {
                'orm' => new Reference('doctrine.orm.entity_manager'),
                'mongodb' => new Reference('doctrine.odm.mongodb.document_manager'),
                default => throw new \LogicException("Invalid db driver given"),
            };
            $container->getDefinition('eo_honeypot.manager')->addMethodCall('setObjectManager', [$db]);
        }
        $container->setParameter('eo_honeypot.options', $config);

        // Remove RedirectListener if redirect is disabled
        if (!$config['redirect']['enabled']) {
            $container->removeDefinition('eo_honeypot.redirect_listener');
        }
    }
}
