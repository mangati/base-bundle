<?php

namespace Mangati\BaseBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CrudGenerateCommand extends ContainerAwareCommand
{
    
    protected function configure()
    {
        $this
            ->setName('mangati:generate:crud');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rootDir = realpath($this->getContainer()->getParameter('kernel.root_dir').'/..');
        
        $entities = [];
        $files = glob($rootDir . '/src/AppBundle/Entity/*.php');
        foreach ($files as $file) {
            $entities[] = str_replace('/', '\\', str_replace('.php', '', str_replace($rootDir . '/src/', '', $file)));
        }
        
        $helper = $this->getHelper('question');
        
        $question = new Question('<info>Entity name (fully namespace)</info> (ex AppBundle\\Entity\\Post): ');
        $question->setAutocompleterValues($entities);
        $entityName = $helper->ask($input, $output, $question);
        
        if (empty($entityName)) {
            $output->writeln('<error>Entity name is required</error>');
            return;
        }
        
        $default = str_replace('\\Entity\\', '\\Form\\', $entityName) . 'Type';
        
        $question = new Question('<info>Entity name (fully namespace)</info> [' . $default . ']: ', $default);
        $formType = $helper->ask($input, $output, $question);
        
        $tokens = explode('\\', $entityName);
        $default = end($tokens) . 's';
        
        $question = new Question('<info>Controller name (without Controller suffix)</info> [' . $default . ']: ', $default);
        $controllerName = $helper->ask($input, $output, $question);
        
        $default = strtolower($controllerName);
        
        $question = new Question('<info>Controller route prefix</info> [' . $default . ']: ', $default);
        $routePrefix = $helper->ask($input, $output, $question);
        
        $default = preg_replace("/[^\w\d\s]/", "_", $routePrefix);
        
        $question = new Question('<info>Route name prefix</info> [' . $default . ']: ', $default);
        $routeNamePrefix = $helper->ask($input, $output, $question);
        
        $default = $routePrefix;
        
        $question = new Question('<info>Template path</info> [' . $default . ']: ', $default);
        $templatePath = $helper->ask($input, $output, $question);
        
        $params = [
            'entityName'      => $entityName,
            'formType'        => $formType,
            'controllerName'  => $controllerName,
            'routePrefix'     => $routePrefix,
            'routeNamePrefix' => $routeNamePrefix,
            'templatePath'    => $templatePath,
        ];
        
        $builder = new \Mangati\BaseBundle\Builder\CrudControllerBuilder();
        
        $classContent = $builder->controller($params);
        $filename = $rootDir . '/src/AppBundle/Controller/' . $controllerName . 'Controller.php';
        file_put_contents($filename, $classContent);
        
        $output->writeln('<info>Generated controller class in </info>' . $filename);
        
        $baseDir = $rootDir . '/app/Resources/views/' . $templatePath;
        
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0777, true);
        }
        
        $indexTemplate = $builder->indexTemplate($params);
        $filename = $baseDir . '/index.html.twig';
        file_put_contents($filename, $indexTemplate);
        
        $output->writeln('<info>Generated index template in </info>' . $filename);
        
        $editTemplate = $builder->editTemplate($params);
        $filename = $baseDir . '/edit.html.twig';
        file_put_contents($filename, $editTemplate);
        
        $output->writeln('<info>Generated edit template in </info>' . $filename);
    }

}