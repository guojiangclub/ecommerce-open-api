<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Container\Container;
use Psr\Log\LoggerInterface as Log;
use GuoJiangClub\EC\Open\Backend\Store\Exceptions\Displayers\ArrayDisplayer;
use GuoJiangClub\EC\Open\Backend\Store\Exceptions\Displayers\DebugDisplayer;
use GuoJiangClub\EC\Open\Backend\Store\Exceptions\Displayers\PlainDisplayer;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        //ModelNotFoundException::class,
    ];

    /**
     * The config instance.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * The container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Create a new exception handler instance.
     *
     * @param \Psr\Log\LoggerInterface                  $log
     * @param \Illuminate\Contracts\Config\Repository   $config
     * @param \Illuminate\Contracts\Container\Container $container
     *
     * @return void
     */
    public function __construct(Log $log, Config $config, Container $container)
    {
        $this->config = $config;
        $this->container = $container;

        parent::__construct($log);
    }


    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void

    public function report(Exception $e)
    {
        return parent::report($e);
    }*/

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $flattened = FlattenException::create($e);

        $code = $flattened->getStatusCode();
        $ajax = $request->ajax();
        $debug = $this->config->get('app.debug');

        $content = $this->getContent($e, $code, $ajax, $debug);

        $headers = $flattened->getHeaders();

        if (is_array($content)) {
            return new JsonResponse($content, $code, $headers);
        }

        return new Response($content, $code, $headers);
    }

    /**
     * Get the content associated with the given exception.
     *
     * @param \Exception $exception
     * @param int        $code
     * @param bool       $ajax
     * @param bool       $debug
     *
     * @return string|array
     */
    protected function getContent(Exception $exception, $code, $ajax, $debug)
    {
        if ($ajax) {
            return $this->container->make(ArrayDisplayer::class)->display($exception, $code);
        }

        if ($debug) {
            return $this->container->make(DebugDisplayer::class)->display($exception, $code);
        }

        return $this->container->make(PlainDisplayer::class)->display($exception, $code);
    }
}
