<?php

use Symfony\Component\Console\Output\OutputInterface;

class StoreSkeleton
{
    /**
     * @param string $pathApp
     * @param string $pathSkeleton
     *
     * @return callable
     */
    public static function process(string $pathApp, string $pathSkeleton): callable
    {
        return function ($component, $withStore, $withModel, $withTable, $withToken, OutputInterface $output) use ($pathApp, $pathSkeleton) {
            if ($component)
            {
                //
                // format names
                //

                $component = str_replace(' ', '', ucwords($component));
                $namespace = 'App\Components\\' . $component . '\Stores';
                $prefix = 'src/Components/' . $component . '/Stores';
                $pathApp = $pathApp . '/' . $prefix;

                if ($withStore)
                {
                    $withStore = str_replace(' ', '', ucwords($withStore));
                }

                if ($withModel)
                {
                    $withModel = str_replace(' ', '', ucwords($withModel));
                }

                //
                // auto derive names
                //

                if (empty($withStore))
                {
                    $withStore = $component; // generate model name
                }

                if (empty($withModel))
                {
                    $withModel = substr($withStore, 0, -1); // generate model name
                }

                if (empty($withTable))
                {
                    $withTable = strtolower($withStore);
                }

                $params = [
                    'namespace' => $namespace,
                    'store'     => $withStore,
                    'table'     => $withTable,
                    'model'     => $withModel,
                ];

                if (!file_exists($pathApp))
                {
                    echo "Creating folder... " . $prefix . "\n";
                    mkdir($pathApp);
                }

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/store/CrudStore.php.dist')
                                ->withDestination($pathApp)
                                ->withFileName('{store}Store.php')
                                ->withParams($params)
                                ->build()
                );

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/store/CrudModel.php.dist')
                                ->withDestination($pathApp)
                                ->withFileName('{model}Model.php')
                                ->withParams($params)
                                ->build()
                );

                if ($withToken)
                {

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/store/with-token/CrudStore.php.dist')
                                    ->withDestination($pathApp)
                                    ->withFileName('{store}Store.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/store/with-token/CrudModel.php.dist')
                                    ->withDestination($pathApp)
                                    ->withFileName('{model}Model.php')
                                    ->withParams($params)
                                    ->build()
                    );
                }
            }
        };
    }
}