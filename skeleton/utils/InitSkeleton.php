<?php

use Symfony\Component\Console\Output\OutputInterface;

class InitSkeleton
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
         * @param $withView
         * @param OutputInterface $output
         *
         * @throws Exception
         */
        return function ($name, $withView, OutputInterface $output) use ($pathApp, $pathSkeleton) {

            $baseFolders = [
                'src',
                'src/Components',
                'src/Configs',
                'src/Locales',
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
                $newFolder = $pathApp . '/' . $folder;

                if (!file_exists($newFolder))
                {
                    echo "Creating folder... " . $folder . "\n";
                    mkdir($newFolder);
                }
            }

            $output->writeln('Creating file... ' .
                TemplateUtil::createFrom($pathSkeleton . '/templates/app/bootstrap.php.dist')
                            ->withDestination($pathApp . '/public')
                            ->withFileName('index.php')
                            ->build()
            );

            $output->writeln('Creating file... ' .
                TemplateUtil::createFrom($pathSkeleton . '/templates/app/with-config/config.php.dist')
                            ->withDestination($pathApp . '/src/Configs')
                            ->withFileName('config.php')
                            ->build()
            );

            $output->writeln('Creating file... ' .
                TemplateUtil::createFrom($pathSkeleton . '/templates/app/with-config/config.php.dist')
                            ->withDestination($pathApp . '/src/Configs')
                            ->withFileName('production.php')
                            ->build()
            );

            $output->writeln('Creating file... ' .
                TemplateUtil::createFrom($pathSkeleton . '/templates/app/with-locale/en-locale.php.dist')
                            ->withDestination($pathApp . '/src/Locales')
                            ->withFileName('en-locale.php')
                            ->build()
            );

            $output->writeln('Creating file... ' .
                TemplateUtil::createFrom($pathSkeleton . '/templates/app/AppContext.php.dist')
                            ->withDestination($pathApp . '/src')
                            ->withFileName('AppContext.php')
                            ->withParams(['name' => $name])
                            ->build()
            );

            // ##################################

            if ($withView)
            {
                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/app/with-view/bootstrap.php.dist')
                                ->withDestination($pathApp . '/public')
                                ->withFileName('index.php')
                                ->build()
                );

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/app/with-view/AppContext.php.dist')
                                ->withDestination($pathApp . '/src')
                                ->withFileName('AppContext.php')
                                ->withParams(['name' => $name])
                                ->build()
                );

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/app/with-view/Data/GlobalViewData.php.dist')
                                ->withDestination($pathApp . '/src/Data')
                                ->withFileName('GlobalViewData.php')
                                ->build()
                );

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/app/with-view/Views/AppAssets.php.dist')
                                ->withDestination($pathApp . '/src/Views')
                                ->withFileName('AppAssets.php')
                                ->build()
                );

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/app/with-view/Views/AppPageTemplate.phtml.dist')
                                ->withDestination($pathApp . '/src/Views')
                                ->withFileName('AppPageTemplate.phtml')
                                ->build()
                );

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/app/with-view/Views/AppPageView.php.dist')
                                ->withDestination($pathApp . '/src/Views')
                                ->withFileName('AppPageView.php')
                                ->build()
                );
            }
        };
    }
}