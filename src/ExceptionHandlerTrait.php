<?php

namespace Laravel\JER;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

defined('API_VERSION') or define('API_VERSION', 'v1.0.0');

trait ExceptionHandlerTrait
{
    protected static $HTTP_STATUS_CODES = [
        100, 101, 102, 200, 201, 202, 203, 204, 205, 206, 207, 300, 301, 302, 303, 304, 305, 306, 307, 400, 401, 402,
        403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417, 418, 422, 423, 424, 425, 426, 449,
        450, 500, 501, 502, 503, 504, 505, 506, 507, 509, 510,
    ];

    /**
     * Render an exception into a response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        $data = $this->getExceptionData($e);
        if ($data !== false) {
            return $this->makeResponse($e, $data[1], $data[0], $data[2]);
        }

        return $this->getResponse($e);
    }

    /**
     * Get exception response data.
     *
     * @param \Exception $e
     *
     * @return array|false
     */
    protected function getExceptionData(Exception $e)
    {
        $status = 500;
        $content = null;
        $headers = [];
        if ($e instanceof TokenMismatchException) {
            $status = 406;
            $content = [
                'title' => trans('jer::token_mismatch.title'),
                'detail' => trans('jer::token_mismatch.detail'),
            ];
        } elseif ($e instanceof ValidationException) {
            $status = 400;
            $messages = $e->validator->messages();
            $content = [
                'code' => '2',
                'title' => $e->getMessage(),
                'detail' => empty($messages) ? $e->getMessage() : $messages->first(),
                'source' => $messages->toArray(),
            ];
        } elseif ($e instanceof AuthorizationException) {
            $status = 401;
        } else {
            return false;
        }

        return [$status, $content, $headers];
    }

    /**
     * Get response of an exception.
     *
     * @param \Exception $e
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getResponse(Exception $e)
    {
        $status = method_exists($e, 'getCode') ? $e->getCode() : (method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        $message = $e->getMessage();
        $content = [];
        if (!empty($message) && is_string($message)) {
            $content = [
                'title' => $message,
                'detail' => $message,
            ];
        }

        return $this->makeResponse($e, $content, $status);
    }

    /**
     * Make response.
     *
     * @param \Exception $e
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function makeResponse(Exception $e, $content, $status, $headers = [])
    {
        if (!in_array($status, static::$HTTP_STATUS_CODES)) {
            $status = 500;
        }
        if ($status == 204) {
            $content = null;
        } else {
            $temp = [
                'status' => strval($status),
                'title' => trans("jer::messages.$status.title"),
                'detail' => trans("jer::messages.$status.detail"),
            ];
            $content = empty($content) ? $temp : array_merge($temp, $content);
            if ($status >= 200 && $status < 300) {
                $content = array_merge(static::getJsonapi(), ['data' => $content]);
            } else {
                $content = array_merge(static::getJsonapi(), ['errors' => $content]);
            }
        }
        $response = new JsonResponse($content, $status, $headers);
        $response->exception = $e;

        return $response;
    }

    /**
     * Get jsonapi part.
     *
     * @return array
     */
    protected static function getJsonapi()
    {
        return [
            'jsonapi' => [
                'version' => API_VERSION,
            ],
        ];
    }
}
