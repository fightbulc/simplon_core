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
        return function ($component, $store, $model, $withToken, OutputInterface $output) use ($pathApp, $pathSkeleton) {
            if ($component && $store && $model)
            {
                $component = str_replace(' ', '', ucwords($component));
                $store = str_replace(' ', '', ucwords($store));
                $model = str_replace(' ', '', ucwords($model));
                $namespace = 'App\Components\\' . $component . '\Stores';
                $prefix = 'src/Components/' . $component . '/Stores';
                $pathApp = $pathApp . '/' . $prefix;

                $params = [
                    'namespace' => $namespace,
                    'store'     => $store,
                    'model'     => $model,
                ];

                if (!file_exists($pathApp))
                {
                    echo "Creating folder... " . $prefix . "\n";
                    mkdir($pathApp);
                }

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/stores/CrudStore.php.dist')
                                ->withDestination($pathApp)
                                ->withFileName('{store}Store.php')
                                ->withParams($params)
                                ->build()
                );

                $output->writeln('Creating file... ' .
                    TemplateUtil::createFrom($pathSkeleton . '/templates/stores/CrudModel.php.dist')
                                ->withDestination($pathApp)
                                ->withFileName('{model}Model.php')
                                ->withParams($params)
                                ->build()
                );

                if($withToken)
                {

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/stores/with-token/CrudStore.php.dist')
                                    ->withDestination($pathApp)
                                    ->withFileName('{store}Store.php')
                                    ->withParams($params)
                                    ->build()
                    );

                    $output->writeln('Creating file... ' .
                        TemplateUtil::createFrom($pathSkeleton . '/templates/stores/with-token/CrudModel.php.dist')
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