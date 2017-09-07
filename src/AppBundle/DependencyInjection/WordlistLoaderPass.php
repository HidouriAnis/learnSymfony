<?php

namespace AppBundle\DependencyInjection;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ExtensionCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class WordlistLoaderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if(!$container->hasDefinition('app.wordlist')) {
            return;
        }
        $definition = $container->findDefinition('app.wordlist');

        foreach($container->findTaggedServiceIds('app.wordlist.loader') as $loaderId => $loaderAttributes) {
            $definition->addMethodCall('addLoader',[
                $loaderAttributes[0]['type'], new Reference($loaderId)
            ]);
        }

        // Reverse calls in order to add dictionary loaders before loading dictionaries
        $calls = $definition->getMethodCalls();
        $definition->setMethodCalls(array_reverse($calls));
    }
}