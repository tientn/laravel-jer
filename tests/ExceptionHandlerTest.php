<?php

namespace LaravelSoft\JER;

use Exception;
use Illuminate\Http\JsonResponse;

class ExceptionHandlerTest extends AbstractTestCase
{
    public function testBasicRender()
    {
        $handler = $this->app->make(ExceptionHandler::class);
        $response = $handler->render($this->app->request, $exception = new Exception('Foo'));
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame($exception, $response->exception);
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertJsonStringEqualsJsonString($response->getContent(), '{"jsonapi":{"version":"v1.0.0"},"errors":{"status":"500","title":"Foo","detail":"Foo"}}');

        $response = $handler->render($this->app->request, $exception = new Exception('Bar', 403));
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(403, $response->getStatusCode());
        $this->assertSame($exception, $response->exception);
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertJsonStringEqualsJsonString($response->getContent(), '{"jsonapi":{"version":"v1.0.0"},"errors":{"status":"403","title":"Bar","detail":"Bar"}}');
    }
}
