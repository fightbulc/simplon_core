<?php

namespace Simplon\Core\Middleware;

use Moment\Moment;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Utils\Exceptions\ClientException;
use Simplon\Core\Utils\Exceptions\ServerException;
use Simplon\Url\Url;
use Whoops\Handler\HandlerInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * @package Simplon\Core\Middleware
 */
class ExceptionMiddleware
{
    /**
     * @var HandlerInterface|null
     */
    protected $handler;
    /**
     * @var callable|null
     */
    protected $callback;
    /**
     * @var bool
     */
    protected $isProduction = false;

    /**
     * @param null|HandlerInterface $handler
     */
    public function __construct(?HandlerInterface $handler = null)
    {
        if ($handler === null)
        {
            $handler = new PrettyPageHandler();
        }

        $this->handler = $handler;
    }

    /**
     * @return bool
     */
    public function isProduction(): bool
    {
        return $this->isProduction === true;
    }

    /**
     * @param bool $isProduction
     *
     * @return ExceptionMiddleware
     */
    public function setIsProduction(bool $isProduction): ExceptionMiddleware
    {
        $this->isProduction = $isProduction;

        return $this;
    }

    /**
     * @param callable $callback
     *
     * @return ExceptionMiddleware
     */
    public function setCallback(callable $callback)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null): ResponseInterface
    {
        try
        {
            return $next($request, $response);
        }
        catch (\Throwable $e)
        {
            $callback = $this->callback;

            if (!$callback)
            {
                $callback = function (ResponseInterface $response, \Throwable $e) { return $this->getDefaultCallback($response, $e); };

                if ($this->isProduction())
                {
                    $callback = $this->getDefaultProductionCallback();
                }
            }

            return $callback($response, $e);
        }
    }

    /**
     * @param ResponseInterface $response
     * @param \Throwable $e
     *
     * @return ResponseInterface
     * @throws \Moment\MomentException
     */
    protected function getDefaultCallback(ResponseInterface $response, \Throwable $e): ResponseInterface
    {
        //
        // trigger error_log
        //

        $this->triggerErrorLog($e);

        //
        // handle whoops
        //

        $whoops = new Run();
        $whoops->allowQuit(false);

        if ($e instanceof ClientException || $e instanceof ServerException)
        {
            $this->handler->addDataTable('PUBLIC DATA', $e->getPublicData());
        }

        $whoops->pushHandler($this->handler);

        ob_start();
        $method = Run::EXCEPTION_HANDLER;
        $whoops->$method($e);
        $errorResponse = ob_get_clean();
        $response->getBody()->write($errorResponse);

        return $response;
    }

    /**
     * @return callable
     */
    protected function getDefaultProductionCallback(): callable
    {
        return function (ResponseInterface $response, \Throwable $e) {
            $this->triggerErrorLog($e);

            return $response;
        };
    }

    /**
     * @param \Throwable $e
     *
     * @throws \Moment\MomentException
     */
    protected function triggerErrorLog(\Throwable $e): void
    {
        error_log(json_encode(
            $this->buildErrorLogData($e)
        ));
    }

    /**
     * @param \Throwable $e
     *
     * @return array
     * @throws \Moment\MomentException
     */
    protected function buildErrorLogData(\Throwable $e): array
    {
        $currentUrl = new Url(Url::getCurrentUrl());
        $env = 'unknown';

        if (getenv('APP_ENV'))
        {
            $env = getenv('APP_ENV');
        }

        $data = [
            'env'       => $env,
            'message'   => $e->getMessage(),
            'trace'     => $e->getTrace(),
            'url'       => [
                'raw'   => $currentUrl->__toString(),
                'host'  => $currentUrl->getHost(),
                'path'  => $currentUrl->getPath(),
                'query' => $currentUrl->getAllQueryParams(),
            ],
            'timestamp' => (new Moment())->format(),
        ];

        if (!empty($_SERVER['HTTP_REFERER']))
        {
            $refererUrl = new Url($_SERVER['HTTP_REFERER']);

            $data['referer'] = [
                'raw'   => $refererUrl->__toString(),
                'host'  => $refererUrl->getHost(),
                'path'  => $refererUrl->getPath(),
                'query' => $refererUrl->getAllQueryParams(),
            ];
        }

        if ($e instanceof ClientException || $e instanceof ServerException)
        {
            $data['http_status'] = $e->getHttpStatusCode();
            $data['public'] = $e->getPublicData();
        }

        return $data;
    }
}