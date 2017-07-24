<?php

use Symfony\Component\Console\Output\OutputInterface;

class ComponentSkeleton
{
    /**
     * @param string $pathRoot
     * @param string $pathSkeleton
     *
     * @return callable
     */
    public static function process(string $pathRoot, string $pathSkeleton): callable
    {
        return function ($name, $withRest, $withView, OutputInterface $output) use ($pathRoot, $pathSkeleton) {
            if ($name)
            {
                $pathRoot = $pathRoot . '/Components';
                $pathSkeleton = $pathSkeleton . '/templates';
                $name = str_replace(' ', '', ucwords($name));
                $view = str_replace(' ', '', ucwords($withView));
                $namespace = 'App\Components\\' . $name;

                $params = [
                    'namespace' => $namespace,
                    'name'      => $name,
                    'view'      => $view,
                ];

                if (!file_exists($pathRoot))
                {
                    mkdir($pathRoot);
                }

                if (!file_exists($pathRoot . '/' . $name))
                {
                    mkdir($pathRoot . '/' . $name);
                }

                $output->writeln('Created...' .
                    TemplateUtil::createFrom($pathSkeleton . '/component/with-config/config.php.dist')
                                ->withDestination($pathRoot . '/' . $name . '/Configs')
                                ->withFileName('config.php')
                                ->withParams($params)
                                ->build()
                );

                $output->writeln('Created...' .
                    TemplateUtil::createFrom($pathSkeleton . '/component/with-config/production.php.dist')
                                ->withDestination($pathRoot . '/' . $name . '/Configs')
                                ->withFileName('production.php')
                                ->withParams($params)
                                ->build()
                );

                $output->writeln('Created...' .
                    TemplateUtil::createFrom($pathSkeleton . '/component/with-locale/en-locale.php.dist')
                                ->withDestination($pathRoot . '/' . $name . '/Locales')
                                ->withFileName('en-locale.php')
                                ->withParams($params)
                                ->build()
                );

                $output->writeln('Created...' .
                    TemplateUtil::createFrom($pathSkeleton . '/component/Context.php.dist')
                                ->withDestination($pathRoot . '/' . $name)
                                ->withFileName('{name}Context.php')
                                ->withParams($params)
                                ->build()
                );

                $output->writeln('Created...' .
                    TemplateUtil::createFrom($pathSkeleton . '/component/Registry.php.dist')
                                ->withDestination($pathRoot . '/' . $name)
                                ->withFileName('{name}Registry.php')
                                ->withParams($params)
                                ->build()
                );

                // ######################################

                if ($withRest)
                {
                    $output->writeln('Created...' .
                        TemplateUtil::createFrom($pathSkeleton . '/component/with-rest/Registry.php.dist')
                                    ->withDestination($pathRoot . '/' . $name)
                                    ->withFileName('{name}Registry.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Created...' .
                        TemplateUtil::createFrom($pathSkeleton . '/component/with-rest/Routes.php.dist')
                                    ->withDestination($pathRoot . '/' . $name)
                                    ->withFileName('{name}Routes.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Created...' .
                        TemplateUtil::createFrom($pathSkeleton . '/component/with-rest/BaseRestController.php.dist')
                                    ->withDestination($pathRoot . '/' . $name . '/Controllers')
                                    ->withFileName('BaseRestController.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Created...' .
                        TemplateUtil::createFrom($pathSkeleton . '/component/with-rest/RestController.php.dist')
                                    ->withDestination($pathRoot . '/' . $name . '/Controllers')
                                    ->withFileName('{name}RestController.php')
                                    ->withParams($params)
                                    ->build()
                    );
                }

                // ######################################

                if ($withView)
                {
                    $output->writeln('Created...' .
                        TemplateUtil::createFrom($pathSkeleton . '/component/with-view/Registry.php.dist')
                                    ->withDestination($pathRoot . '/' . $name)
                                    ->withFileName('{name}Registry.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Created...' .
                        TemplateUtil::createFrom($pathSkeleton . '/component/with-view/Routes.php.dist')
                                    ->withDestination($pathRoot . '/' . $name)
                                    ->withFileName('{name}Routes.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Created...' .
                        TemplateUtil::createFrom($pathSkeleton . '/component/with-view/BaseViewController.php.dist')
                                    ->withDestination($pathRoot . '/' . $name . '/Controllers')
                                    ->withFileName('BaseViewController.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Created...' .
                        TemplateUtil::createFrom($pathSkeleton . '/component/with-view/ViewController.php.dist')
                                    ->withDestination($pathRoot . '/' . $name . '/Controllers')
                                    ->withFileName('{name}ViewController.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Created...' .
                        TemplateUtil::createFrom($pathSkeleton . '/component/with-view/Data/ComponentViewData.php.dist')
                                    ->withDestination($pathRoot . '/' . $name . '/Data')
                                    ->withFileName('ComponentViewData.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Created...' .
                        TemplateUtil::createFrom($pathSkeleton . '/component/with-view/Views/Assets.php.dist')
                                    ->withDestination($pathRoot . '/' . $name . '/Views')
                                    ->withFileName('{name}Assets.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Created...' .
                        TemplateUtil::createFrom($pathSkeleton . '/component/with-view/Views/PageTemplate.phtml.dist')
                                    ->withDestination($pathRoot . '/' . $name . '/Views')
                                    ->withFileName('{name}PageTemplate.phtml')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Created...' .
                        TemplateUtil::createFrom($pathSkeleton . '/component/with-view/Views/PageView.php.dist')
                                    ->withDestination($pathRoot . '/' . $name . '/Views')
                                    ->withFileName('{name}PageView.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Created...' .
                        TemplateUtil::createFrom($pathSkeleton . '/component/with-view/Views/Foo/FooView.php.dist')
                                    ->withDestination($pathRoot . '/' . $name . '/Views/{view}')
                                    ->withFileName('{view}View.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Created...' .
                        TemplateUtil::createFrom($pathSkeleton . '/component/with-view/Views/Foo/FooTemplate.phtml.dist')
                                    ->withDestination($pathRoot . '/' . $name . '/Views/{view}')
                                    ->withFileName('{view}Template.phtml')
                                    ->withParams($params)
                                    ->build()
                    );
                }
            }
        };
    }
}