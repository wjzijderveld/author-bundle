<?php

namespace Qandidate\AuthorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class QandidateAuthorExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration;
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('qandidate_author.paths', $config['paths']);
        if (isset($config['permalink'])) {
            $container->setParameter('qandidate_author.permalink', $config['permalink']);
        }
        if (isset($config['layout'])) {
            $container->setParameter('qandidate_author.layout', $config['layout']);
        } else {
            $container->setParameter('qandidate_author.layout', null);
        }
    }
}
