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
        return function ($component, $store, $withModel, $withTable, $withToken, OutputInterface $output) use ($pathApp, $pathSkeleton) {
            if (empty($withModel))
            {
                $withModel = substr($store, 0, -1); // generate model name
            }

            if (empty($withTable))
            {
                $withTable = strtolower($store);
            }

            if ($component && $store && $withModel)
            {
                $component = str_replace(' ', '', ucwords($component));
                $store = str_replace(' ', '', ucwords($store));
                $withModel = str_replace(' ', '', ucwords($withModel));
                $namespace = 'App\Components\\' . $component . '\Stores';
                $prefix = 'src/Components/' . $component . '/Stores';
                $pathApp = $pathApp . '/' . $prefix;

                $params = [
                    'namespace' => $namespace,
                    'store'     => $store,
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