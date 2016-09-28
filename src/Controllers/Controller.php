<?php

namespace Simplon\Core\Controllers;

use Simplon\Core\Interfaces\AppContextInterface;
use Simplon\Core\Interfaces\ControllerInterface;
use Simplon\Core\Utils\Config;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Controller
 * @package Simplon\Core\Controllers
 */
abstract class Controller implements ControllerInterface
{
    /**
     * @var Config[]
     */
    protected $configCache = [];
    /**
     * @var AppContextInterface
     */
    protected $appContext;
    /**
     * @var ServerRequestInterface
     */
    protected $request;
    /**
     * @var ResponseInterface
     */
    protected $response;
    /**
     * @var string
     */
    protected $workingDir;

    /**
     * @param array $key
     *
     * @return mixed
     */
    public function getConfig(array $key = [])
    {
        $md5WorkingDir = md5($this->workingDir);

        if (empty($this->configCache[$md5WorkingDir]))
        {
            $config = $this->getAppContext()->getConfig();

            if (file_exists($this->workingDir . '/Configs/config.php'))
            {
                /** @noinspection PhpIncludeInspection */
                $config->addConfig(require $this->workingDir . '/Configs/config.php');

                if (getenv('APP_ENV') === 'production')
                {
                    if (file_exists($this->workingDir . '/Configs/production.php'))
                    {
                        /** @noinspection PhpIncludeInspection */
                        $config->addConfig(require $this->workingDir . '/Configs/production.php');
                    }
                }
            }

            $this->configCache[$md5WorkingDir] = $config;
        }

        if (empty($key))
        {
            return $this->configCache[$md5WorkingDir];
        }

        return $this->configCache[$md5WorkingDir]->get($key);
    }

    /**
     * @return AppContextInterface
     */
    public function getAppContext(): AppContextInterface
    {
        return $this->appContext;
    }

    /**
     * @param AppContextInterface $appContext
     *
     * @return ControllerInterface
     */
    public function setAppContext(AppContextInterface $appContext): ControllerInterface
    {
        $this->appContext = $appContext;

        return $this;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ControllerInterface
     */
    public function setRequest(ServerRequestInterface $request): ControllerInterface
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return ControllerInterface
     */
    public function setResponse(ResponseInterface $response): ControllerInterface
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return string
     */
    public function getWorkingDir(): string
    {
        return $this->workingDir;
    }

    /**
     * @param string $workingDir
     *
     * @return ControllerInterface
     */
    public function setWorkingDir(string $workingDir): ControllerInterface
    {
        $this->workingDir = $workingDir;

        return $this;
    }
}