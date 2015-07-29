<?php

namespace Mangati\BaseBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mangati_base');
        $this->addPicker($rootNode);
        return $treeBuilder;
    }
    
}