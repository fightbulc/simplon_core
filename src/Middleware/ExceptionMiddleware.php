<?php

namespace Simplon\Core\Middleware;

use Moment\Moment;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Utils\Exceptions\ClientException;
use Simplon\Core\Utils\Exceptions\ServerException;
use Simplon\Url\Url;
use Whoops\Handler\HandlerInterface;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Zend\Diactoros\Response;

/**
 * @package Simplon\Core\Middleware
 */
class ExceptionMiddleware
{
    const DEFAULT_HTTP_STATUS = 500;

    /**
     * @var HandlerInterface
     */
    protected $handler;
    /**
     * @var bool
     */
    protected $isProduction = false;
    /**
     * @var string
     */
    protected $errorRedirectUrl;

    /**
     * @param HandlerInterface $handler
     * @param bool $isProduction
     */
    public function __construct(HandlerInterface $handler, bool $isProduction = false)
    {
        $this->isProduction = $isProduction;
        $this->handler = $handler;
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
            //
            // handle exception
            //

            $callback = $this->getDefaultCallback();

            if ($this->isProduction === true)
            {
                $callback = $this->getDefaultProductionCallback();
            }

            //
            // determine http status
            //

            $httpStatus = self::DEFAULT_HTTP_STATUS;

            if ($e instanceof ClientException || $e instanceof ServerException)
            {
                $httpStatus = $e->getHttpStatusCode();
            }

            //
            // process error
            //

            $response = $callback($response->withStatus($httpStatus), $e);

            //
            // redirect to error page if production
            //

            if ($this->isProduction && $url = $this->getErrorRedirectUrl())
            {
                $url = str_replace('{status}', substr($httpStatus, 0, 2) . 'x', $url);
                $response = (new Response())->withAddedHeader('Location', $url);
            }

            return $response;
        }
    }

    /**
     * @param null|string $errorRedirectUrl
     *
     * @return ExceptionMiddleware
     */
    public function setAsProduction(?string $errorRedirectUrl = null): self
    {
        $this->isProduction = true;
        $this->errorRedirectUrl = $errorRedirectUrl;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return ExceptionMiddleware
     */
    public function redirectOnError(string $url): self
    {
        $this->errorRedirectUrl = $url;

        return $this;
    }

    /**
     * @return null|string
     */
    protected function getErrorRedirectUrl(): ?string
    {
        return $this->errorRedirectUrl;
    }

    /**
     * @return callable
     */
    protected function getDefaultCallback(): callable
    {
        return function (ResponseInterface $response, \Throwable $e) {
            //
            // trigger error_log
            //

            $this->triggerErrorLog($response, $e);

            //
            // fetch error response
            //

            $errorResponse = $this->fetchErrorResponse($e);

            //
            // handle error response
            //

            if ($this->getHandler() instanceof JsonResponseHandler)
            {
                $response = $response->withAddedHeader('Content-type', 'application/json; charset=utf-8');

                $errorResponse = json_decode($errorResponse, true);
                $errorResponse['error']['trace'] = $e->getTrace();

                if ($e instanceof ClientException || $e instanceof ServerException)
                {
                    $errorResponse = [
                        'error' => [
                            'message' => $e->getMessage(),
                            'data'    => $e->getPublicData(),
                        ],
                    ];
                }

                $errorResponse = json_encode($errorResponse);
            }

            $response->getBody()->write($errorResponse);

            return $response;
        };
    }

    /**
     * @return callable
     */
    protected function getDefaultProductionCallback(): callable
    {
        return function (ResponseInterface $response, \Throwable $e) {
            $this->triggerErrorLog($response, $e);

            return $response;
        };
    }

    /**
     * @param \Throwable $e
     *
     * @return string
     */
    protected function fetchErrorResponse(\Throwable $e): string
    {
        $whoops = new Run();
        $whoops->allowQuit(false);

        if ($e instanceof ClientException || $e instanceof ServerException)
        {
            if ($this->getHandler() instanceof PrettyPageHandler)
            {
                /** @var PrettyPageHandler $handler */
                $handler = $this->getHandler();
                $handler->addDataTable('PUBLIC DATA', $e->getPublicData());
            }
        }

        $whoops->pushHandler($this->handler);

        ob_start();
        $method = Run::EXCEPTION_HANDLER;
        $whoops->$method($e);

        return ob_get_clean();
    }

    /**
     * @param ResponseInterface $response
     * @param \Throwable $e
     *
     * @throws \Moment\MomentException
     */
    protected function triggerErrorLog(ResponseInterface $response, \Throwable $e): void
    {
        error_log(json_encode(
            $this->buildErrorLogData($response, $e)
        ));
    }

    /**
     * @param ResponseInterface $response
     * @param \Throwable $e
     *
     * @return array
     * @throws \Moment\MomentException
     */
    protected function buildErrorLogData(ResponseInterface $response, \Throwable $e): array
    {
        $currentUrl = new Url(Url::getCurrentUrl());
        $env = 'unknown';

        if (getenv('APP_ENV'))
        {
            $env = getenv('APP_ENV');
        }

        $data = [
            'http_status' => $response->getStatusCode(),
            'env'         => $env,
            'message'     => $e->getMessage(),
            'source'      => [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ],
            'trace'       => $e->getTrace(),
            'url'         => [
                'raw'   => $currentUrl->__toString(),
                'host'  => $currentUrl->getHost(),
                'path'  => $currentUrl->getPath(),
                'query' => $currentUrl->getAllQueryParams(),
            ],
            'timestamp'   => (new Moment())->format(),
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
            $data['public'] = $e->getPublicData();
        }

        return $data;
    }

    /**
     * @return HandlerInterface
     */
    private function getHandler(): HandlerInterface
    {
        return $this->handler;
    }
}