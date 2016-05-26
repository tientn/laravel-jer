<?php

namespace LaravelSoft\JER;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

trait ExceptionHandlerTrait
{
    protected static $HTTP_STATUS_CODES = [
        100, 101, 102, 200, 201, 202, 203, 204, 205, 206, 207, 300, 301, 302, 303, 304, 305, 306, 307, 400, 401, 402,
        403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417, 418, 422, 423, 424, 425, 426, 449,
        450, 500, 501, 502, 503, 504, 505, 506, 507, 509, 510,
    ];

    public $jsonapiVersion = 'v1.0.0';

    /**
     * Render an exception into a response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        // dd($exception);
        $data = $this->getExceptionData($exception);
        if ($data !== false) {
            return $this->makeResponse($exception, $data[1], $data[0], $data[2]);
        }

        return $this->getResponse($exception);
    }

    /**
     * Get exception response data. Return false for default handler
     *
     * @param \Exception $exception
     *
     * @return array|false
     */
    protected function getExceptionData(Exception $exception)
    {
        $status = 500;
        $content = null;
        $headers = [];
        if ($exception instanceof TokenMismatchException) {
            $status = 406;
            $content = [
                'title' => trans('laravel-soft-jer::messages.token_mismatch.title'),
                'detail' => trans('laravel-soft-jer::messages.token_mismatch.detail'),
            ];
        } elseif ($exception instanceof ValidationException) {
            $status = 400;
            $messages = $exception->validator->messages();
            $content = [
                'code' => '2',
                'title' => $exception->getMessage(),
                'detail' => empty($messages) ? $exception->getMessage() : $messages->first(),
                'source' => $messages->toArray(),
            ];
        } elseif ($exception instanceof AuthorizationException) {
            $status = 401;
        } else {
            return false;
        }

        return [$status, $content, $headers];
    }

    /**
     * Get response of an exception.
     *
     * @param \Exception $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getResponse(Exception $exception)
    {
        $status = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : (method_exists($exception, 'getCode') ? $exception->getCode() : 500);
        $message = $exception->getMessage();
        $content = [];
        if (!empty($message) && is_string($message)) {
            $content = [
                'title' => $message,
                'detail' => $message,
            ];
        }

        return $this->makeResponse($exception, $content, $status);
    }

    /**
     * Make response.
     *
     * @param \Exception $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function makeResponse(Exception $exception, $content, $status, $headers = [])
    {
        if (!in_array($status, static::$HTTP_STATUS_CODES)) {
            $status = 500;
        }
        if ($status == 204) {
            $content = null;
        } else {
            $temp = [
                'status' => strval($status),
                'title' => trans("laravel-soft-jer::messages.$status.title"),
                'detail' => trans("laravel-soft-jer::messages.$status.detail"),
            ];
            $content = empty($content) ? $temp : array_merge($temp, $content);
            if ($status >= 200 && $status < 300) {
                $content = array_merge($this->getJsonapi(), ['data' => $content]);
            } else {
                $content = array_merge($this->getJsonapi(), ['errors' => $content]);
            }
        }
        $response = new JsonResponse($content, $status, $headers);
        $response->exception = $exception;

        return $response;
    }

    /**
     * Get jsonapi part.
     *
     * @return array
     */
    protected function getJsonapi()
    {
        return [
            'jsonapi' => [
                'version' => $this->jsonapiVersion,
            ],
        ];
    }
}
