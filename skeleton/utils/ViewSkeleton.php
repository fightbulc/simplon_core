<?php

use Symfony\Component\Console\Output\OutputInterface;

class ViewSkeleton
{
    /**
     * @param string $pathApp
     * @param string $pathSkeleton
     *
     * @return callable
     */
    public static function process(string $pathApp, string $pathSkeleton): callable
    {
        return function ($component, $name, OutputInterface $output) use ($pathApp, $pathSkeleton) {
            if ($name)
            {
                $component = str_replace(' ', '', ucwords($component));
                $view = str_replace(' ', '', ucwords($name));
                $prefix = 'src/Components/' . $component;
                $pathApp = $pathApp . '/' . $prefix;
                $namespace = 'App\Components\\' . $component;

                $params = [
                    'namespace' => $namespace,
                    'name'      => $component,
                    'view'      => $view,
                ];

                $folder = $pathApp . '/Views/' . $view;

                if (!file_exists($folder))
                {
                    echo "Creating folder... " . $prefix . '/Views/' . $view . "\n";
                    mkdir($folder);
                }

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-view/ViewController.php.dist')
                                ->withDestination($pathApp . '/Controllers')
                                ->withFileName('{view}ViewController.php')
                                ->withParams($params)
                                ->build()
                );

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-view/Views/Foo/FooView.php.dist')
                                ->withDestination($pathApp . '/Views/{view}')
                                ->withFileName('{view}View.php')
                                ->withParams($params)
                                ->build()
                );

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-view/Views/Foo/FooTemplate.phtml.dist')
                                ->withDestination($pathApp . '/Views/{view}')
                                ->withFileName('{view}Template.phtml')
                                ->withParams($params)
                                ->build()
                );
            }
        };
    }
}