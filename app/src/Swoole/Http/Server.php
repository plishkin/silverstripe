<?php

namespace App\Swoole\Http;

use SilverStripe\Control\HTTPApplication;
use SilverStripe\Control\HTTPRequestBuilder;
use SilverStripe\Core\CoreKernel;
use SilverStripe\Core\Environment;
use Swoole\Http\Request as SwooleRequest;
use Swoole\Http\Response as SwooleResponse;

/**
 * Class Server
 * @package App\Swoole
 */
class Server
{

    /**
     * @return HTTPApplication
     */

    public static function handleRequest(SwooleRequest $swooleRequest, SwooleResponse $swooleResponse): void
    {
        $kernel = new CoreKernel(BASE_PATH);
        $app = new HTTPApplication($kernel);
        $variables = self::getSSGlobalVarsFromRequest($swooleRequest);
        Environment::setVariables($variables); // Currently necessary for SSViewer, etc. to work

        // Health-check prior to creating environment
        $request = HTTPRequestBuilder::createFromVariables($variables, @file_get_contents('php://input'));
        $response = $app->handle($request);

        $swooleResponse->end('Ok');
//        $swooleResponse->end($response->getBody());
    }

    public static function getSSGlobalVarsFromRequest(SwooleRequest $request)
    {
        return [
            '_SERVER' => array_change_key_case($request->server, CASE_UPPER),
            '_HEADER' => $request->header,
            '_GET' => $request->get,
            '_POST' => $request->post,
            '_FILES' => $request->files,
            '_COOKIE' => $request->cookie,
            '_SESSION' => [],
        ];
    }

}
