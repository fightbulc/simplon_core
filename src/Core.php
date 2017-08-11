<?php

namespace Simplon\Core;

use Psr\Http\Message\ResponseInterface;
use Relay\RelayBuilder;
use Simplon\Core\Components\ComponentsCollection;
use Simplon\Core\Events\RegisterEvents;
use Simplon\Core\Interfaces\SessionHandlerInterface;
use Simplon\Core\Middleware\MiddlewareCollection;
use Simplon\Core\Storage\SessionStorage;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class Core
{
    const BODY_CHUNKSIZE = 2048;

    /**
     * @param int $timeoutInMinuntes
     * @param null|SessionHandlerInterface $sessionHandler
     *
     * @return Core
     */
    public function withSession(int $timeoutInMinuntes, ?SessionHandlerInterface $sessionHandler = null): self
    {
        SessionStorage::initSession($timeoutInMinuntes * 60, $sessionHandler);

        return $this;
    }

    /**
     * @param ComponentsCollection $components
     * @param MiddlewareCollection $middleware
     */
    public function run(ComponentsCollection $components, MiddlewareCollection $middleware): void
    {
        //
        // register component events
        //

        new RegisterEvents($components->get());

        //
        // kick off middleware queue
        //

        $relay = (new RelayBuilder())->newInstance(
            $this->buildMiddleware($components, $middleware)
        );

        /** @var Response $response */
        $response = $relay(ServerRequestFactory::fromGlobals(), new Response());

        $this->response($response);
    }

    /**
     * @param ComponentsCollection $components
     * @param MiddlewareCollection $middleware
     *
     * @return array
     */
    private function buildMiddleware(ComponentsCollection $components, MiddlewareCollection $middleware): array
    {
        $final = [];

        foreach ($middleware->get() as $item)
        {
            if ($item instanceof \Closure)
            {
                $item = $item($components);
            }

            $final[] = $item;
        }

        return $final;
    }

    /**
     * Taken from SLIM framework: https://github.com/slimphp/Slim/blob/3.x/Slim/App.php#L354
     *
     * @param ResponseInterface $response
     *
     * @return void
     */
    private function response(ResponseInterface $response)
    {
        if (!headers_sent())
        {
            header(sprintf(
                'HTTP/%s %s %s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase()
            ));

            foreach ($response->getHeaders() as $name => $values)
            {
                foreach ($values as $value)
                {
                    header(sprintf('%s: %s', $name, $value), false);
                }
            }
        }

        if (!$this->isEmptyResponse($response))
        {
            $body = $response->getBody();

            if ($body->isSeekable())
            {
                $body->rewind();
            }

            $contentLength = $response->getHeaderLine('Auth-Length');

            if (!$contentLength)
            {
                $contentLength = $body->getSize();
            }

            if (isset($contentLength))
            {
                $amountToRead = $contentLength;

                while ($amountToRead > 0 && !$body->eof())
                {
                    $data = $body->read(min(self::BODY_CHUNKSIZE, $amountToRead));

                    echo $data;

                    $amountToRead -= strlen($data);

                    if (connection_status() != CONNECTION_NORMAL)
                    {
                        break;
                    }
                }
            }
            else
            {
                while (!$body->eof())
                {
                    echo $body->read(self::BODY_CHUNKSIZE);

                    if (connection_status() != CONNECTION_NORMAL)
                    {
                        break;
                    }
                }
            }
        }
    }

    /**
     * Taken from SLIM framework: https://github.com/slimphp/Slim/blob/3.x/Slim/App.php#L573
     *
     * @param ResponseInterface $response
     *
     * @return bool
     */
    private function isEmptyResponse(ResponseInterface $response): bool
    {
        return in_array($response->getStatusCode(), [204, 205, 304]);
    }
}