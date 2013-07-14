<?php
/**
 * のらプロジェクトファイル
 *
 * @author     ハジメ <mail@hazime.org>
 * @copyright  opyright (c) 2013, nora project all rights reserved.
 * @license    http://www.hazime.org/license/licence.txt
 * @version    $id$
 */
namespace Nora\Router;
use Nora\Request\WebRequest;

/**
 * URIルーター
 */
class UriRouter {

    /**
     * URIルーターを起動
     */
    public function __construct( ) {
        $this->_initUriRouter( );
    }

    /**
     * URIルーターを初期化
     */
    private function _initUriRouter( ) {
    }

    /**
     * ルートを作成する
     */
    public function addRoute($pattern, $defaults = array(), $priority = 100) {

        // patternを変換する
        $paramNames = array();
        $patternF =  preg_quote( strtok($pattern, '%%'), '/' );
        while( false !== $token = strtok( '%%' ) ) {
            $paramNames[] = $token;
            $patternF .=  $token != 'params' ? '([^\/]+)' : '(.*)';
            $patternF .=  preg_quote(strtok('%%'), '/');
        }
        
        $this->_routes[$priority][$pattern] = array(
            'format'=>$patternF,'params'=>$paramNames,'defaults'=>$defaults
        );
    }
         
    /**
     * ルートを作成する
     */
    public function route( WebRequest $request ) {
        $uri = $request->getRequestURI();
        foreach($this->_routes as $priority=>$v) {
            foreach( $v as $pattern=>$route ) {
                if( preg_match('/'.$route['format'].'/', $uri, $m) ) {
                    $params = array();
                    $result = array_combine(
                        $route['params'],
                        array_slice($m,1)
                    );

                    if( isset($result['params']) && !empty($result['params'])) {
                        $key = strtok( $result['params'], '/' );
                        while( $value = strtok('/') ) {
                            $params[$key] = $value;
                            $key = strtok('/');
                        }
                        unset($result['params']);
                    }
                    $result = array_merge( $params, $route['defaults'], $result );

                    $request->setVars( $result );

                    return true;
                }
            }
        }
    }
}
