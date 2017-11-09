<?php

use Symfony\Component\Console\Output\OutputInterface;

class ComponentSkeleton
{
    /**
     * @param string $pathApp
     * @param string $pathSkeleton
     *
     * @return callable
     */
    public static function process(string $pathApp, string $pathSkeleton): callable
    {
        /**
         * @param $name
         * @param $withRest
         * @param $withView
         * @param OutputInterface $output
         *
         * @throws Exception
         */
        return function ($name, $withRest, $withView, OutputInterface $output) use ($pathApp, $pathSkeleton) {
            if ($name)
            {
                $prefix = 'src/Components';
                $pathApp = $pathApp . '/' . $prefix;
                $name = str_replace(' ', '', ucwords($name));
                $view = str_replace(' ', '', ucwords($withView));
                $namespace = 'App\Components\\' . $name;

                $params = [
                    'namespace' => $namespace,
                    'name'      => $name,
                    'view'      => $view,
                ];

                $folder = $pathApp . '/' . $name;

                if (!file_exists($folder))
                {
                    echo "Creating folder... " . $prefix . '/' . $name . "\n";
                    mkdir($folder, 0777, true);
                }

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-config/config.php.dist')
                                ->withDestination($pathApp . '/' . $name . '/Configs')
                                ->withFileName('config.php')
                                ->withParams($params)
                                ->build()
                );

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-config/production.php.dist')
                                ->withDestination($pathApp . '/' . $name . '/Configs')
                                ->withFileName('production.php')
                                ->withParams($params)
                                ->build()
                );

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-locale/en-locale.php.dist')
                                ->withDestination($pathApp . '/' . $name . '/Locales')
                                ->withFileName('en-locale.php')
                                ->withParams($params)
                                ->build()
                );

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/component/Context.php.dist')
                                ->withDestination($pathApp . '/' . $name)
                                ->withFileName('{name}Context.php')
                                ->withParams($params)
                                ->build()
                );

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/component/Registry.php.dist')
                                ->withDestination($pathApp . '/' . $name)
                                ->withFileName('{name}Registry.php')
                                ->withParams($params)
                                ->build()
                );

                // ######################################

                if ($withRest)
                {
                    $params['rest'] = $withRest;

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-rest/BaseRestController.php.dist')
                                    ->withDestination($pathApp . '/' . $name . '/Controllers')
                                    ->withFileName('BaseRestController.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-rest/Registry.php.dist')
                                    ->withDestination($pathApp . '/' . $name)
                                    ->withFileName('{name}Registry.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-rest/Routes.php.dist')
                                    ->withDestination($pathApp . '/' . $name)
                                    ->withFileName('{name}Routes.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-rest/RestController.php.dist')
                                    ->withDestination($pathApp . '/' . $name . '/Controllers')
                                    ->withFileName('{rest}RestController.php')
                                    ->withParams($params)
                                    ->build()
                    );
                }

                // ######################################

                if ($withView)
                {
                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-view/BaseViewController.php.dist')
                                    ->withDestination($pathApp . '/' . $name . '/Controllers')
                                    ->withFileName('BaseViewController.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-view/Data/ComponentViewData.php.dist')
                                    ->withDestination($pathApp . '/' . $name . '/Data')
                                    ->withFileName('ComponentViewData.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-view/Views/Assets.php.dist')
                                    ->withDestination($pathApp . '/' . $name . '/Views')
                                    ->withFileName('{name}Assets.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-view/Views/PageTemplate.phtml.dist')
                                    ->withDestination($pathApp . '/' . $name . '/Views')
                                    ->withFileName('{name}PageTemplate.phtml')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-view/Views/PageView.php.dist')
                                    ->withDestination($pathApp . '/' . $name . '/Views')
                                    ->withFileName('{name}PageView.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $params['view'] = ucwords($withView);

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-view/Registry.php.dist')
                                    ->withDestination($pathApp . '/' . $name)
                                    ->withFileName('{name}Registry.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-view/Routes.php.dist')
                                    ->withDestination($pathApp . '/' . $name)
                                    ->withFileName('{name}Routes.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-view/ViewController.php.dist')
                                    ->withDestination($pathApp . '/' . $name . '/Controllers')
                                    ->withFileName('{view}ViewController.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-view/Views/Foo/FooView.php.dist')
                                    ->withDestination($pathApp . '/' . $name . '/Views/{view}')
                                    ->withFileName('{view}View.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/component/with-view/Views/Foo/FooTemplate.phtml.dist')
                                    ->withDestination($pathApp . '/' . $name . '/Views/{view}')
                                    ->withFileName('{view}Template.phtml')
                                    ->withParams($params)
                                    ->build()
                    );
                }
            }
        };
    }
}