<?php
/**
 * のらプロジェクトファイル
 *
 * @author     ハジメ <mail@hazime.org>
 * @copyright  opyright (c) 2013, nora project all rights reserved.
 * @license    http://www.hazime.org/license/licence.txt
 * @version    $id$
 */
namespace Nora\Controller;

use Nora\Request\WebRequest AS Request;
use Nora\Response\Response;

class Controller {

    public function dispatch( $action, $request, $response ) {

        $actionName = $action.'Action';

        if( !method_exists($this,$actionName) ){
            throw new \Exception( '存在しないアクションです'."[$actionName]" );
        }

        $status = call_user_func(
            array( $this, $actionName ),
            $request,
            $response
        );

        return $response;
    }
}

