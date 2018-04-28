<?php
/**
 * Created by PhpStorm.
 * User: Zchu
 * Date: 2018/4/28
 * Time: 8:52
 */

namespace App\Controller;
use Psr\Http\Message\ResponseInterface;

class HelloController
{
    private $response;
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function __invoke()
    {
        $response = $this->response->withHeader('Content-Type', 'text/html');
        $response->getBody()
            ->write("<html><head></head><body>Hello, world!</body></html>");

        return $response;
    }
}