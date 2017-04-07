<?php

namespace Simplon\Core\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Utils\Exceptions\ClientException;
use Simplon\Core\Utils\Exceptions\ServerException;
use Whoops\Handler\HandlerInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * @package Simplon\Core\Middleware
 */
class ExceptionMiddleware
{
    /**
     * @var HandlerInterface
     */
    protected $handler;

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
        catch (\Error $e)
        {
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
    }
}