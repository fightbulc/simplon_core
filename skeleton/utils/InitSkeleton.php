<?php

use Symfony\Component\Console\Output\OutputInterface;

class InitSkeleton
{
    /**
     * @param string $pathRoot
     * @param string $pathSkeleton
     *
     * @return callable
     */
    public static function process(string $pathRoot, string $pathSkeleton): callable
    {
        return function ($name, $withView, OutputInterface $output) use ($pathRoot, $pathSkeleton) {

            $baseFolders = [
                'src',
                'public',
                'public/assets',
                'builds',
                'builds/assets',
                'builds/sql',
                'builds/sql/migrations',
                'builds/sql/seeds',
            ];

            foreach ($baseFolders as $folder)
            {
                $newFolder = $pathRoot . '/' . $folder;

                if (!file_exists($newFolder))
                {
                    echo "Created... " . $folder . ">\n";
                    mkdir($newFolder);
                }
            }

            $appFolders = [
                'Components',
                'Configs',
                'Locales',
            ];

            foreach ($appFolders as $folder)
            {
                $newFolder = $pathRoot . '/' . $folder;

                if (!file_exists($newFolder))
                {
                    echo "Created... " . $folder . "\n";
                    mkdir($newFolder);
                }
            }

            $output->writeln('Created...' .
                TemplateUtil::createFrom($pathSkeleton . '/app/bootstrap.php.dist')
                            ->withDestination($pathRoot . '/../public')
                            ->withFileName('index.php')
                            ->build()
            );

            $output->writeln('Created...' .
                TemplateUtil::createFrom($pathSkeleton . '/app/with-config/config.php.dist')
                            ->withDestination($pathRoot . '/Configs')
                            ->withFileName('config.php')
                            ->build()
            );

            $output->writeln('Created...' .
                TemplateUtil::createFrom($pathSkeleton . '/app/with-config/config.php.dist')
                            ->withDestination($pathRoot . '/Configs')
                            ->withFileName('production.php')
                            ->build()
            );

            $output->writeln('Created...' .
                TemplateUtil::createFrom($pathSkeleton . '/app/with-locale/en-locale.php.dist')
                            ->withDestination($pathRoot . '/Locales')
                            ->withFileName('en-locale.php')
                            ->build()
            );

            $output->writeln('Created...' .
                TemplateUtil::createFrom($pathSkeleton . '/app/AppContext.php.dist')
                            ->withDestination($pathRoot)
                            ->withFileName('AppContext.php')
                            ->withParams(['name' => $name])
                            ->build()
            );

            // ##################################

            if ($withView)
            {
                $output->writeln('Created...' .
                    TemplateUtil::createFrom($pathSkeleton . '/app/with-view/bootstrap.php.dist')
                                ->withDestination($pathRoot . '/../public')
                                ->withFileName('index.php')
                                ->build()
                );

                $output->writeln('Created...' .
                    TemplateUtil::createFrom($pathSkeleton . '/app/with-view/AppContext.php.dist')
                                ->withDestination($pathRoot)
                                ->withFileName('AppContext.php')
                                ->build()
                );

                $output->writeln('Created...' .
                    TemplateUtil::createFrom($pathSkeleton . '/app/with-view/Data/GlobalViewData.php.dist')
                                ->withDestination($pathRoot . '/Data')
                                ->withFileName('GlobalViewData.php')
                                ->build()
                );

                $output->writeln('Created...' .
                    TemplateUtil::createFrom($pathSkeleton . '/app/with-view/Views/AppAssets.php.dist')
                                ->withDestination($pathRoot . '/Views')
                                ->withFileName('AppAssets.php')
                                ->build()
                );

                $output->writeln('Created...' .
                    TemplateUtil::createFrom($pathSkeleton . '/app/with-view/Views/AppPageTemplate.phtml.dist')
                                ->withDestination($pathRoot . '/Views')
                                ->withFileName('AppPageTemplate.phtml')
                                ->build()
                );

                $output->writeln('Created...' .
                    TemplateUtil::createFrom($pathSkeleton . '/app/with-view/Views/AppPageView.php.dist')
                                ->withDestination($pathRoot . '/Views')
                                ->withFileName('AppPageView.php')
                                ->build()
                );
            }
        };
    }
}