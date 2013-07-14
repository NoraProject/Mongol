<?php
/**
 * のらプロジェクトファイル
 *
 * @author     ハジメ <mail@hazime.org>
 * @copyright  opyright (c) 2013, nora project all rights reserved.
 * @license    http://www.hazime.org/license/licence.txt
 * @version    $id$
 */
namespace Nora\App;

use Nora\Request\WebRequest;
use Nora\Response\Response;

/**
 * アプリケーションクラス
 */
class App {

    private $_appDir;

    public static function createApp( $appDir ) {
        return new static($appDir);
    }

    /**
     * アプリケーションディレクトリを指定して起動
     */
    private function __construct( $appDir ) {
        $this->_initApp( $appDir );
    }

    /**
     * アプリケーションを初期化
     */
    private function _initApp( $appDir ) {
        $this->_appDir = realpath($appDir);
    }

    private function _pathSanitize($value){
        $value = str_replace('/','',$value);
        $value = str_replace('.','',$value);
        return $value;
    }


    /**
     * アプリケーションへリクエストを実行
     */
    public function request( WebRequest $request ) {
        $module = $request->getVar('module','default');
        $controller = $request->getVar('controller','default');
        $action = $request->getVar('action','default');
        $response = new Response();
        $this->dispatch( $module, $controller, $action, $request, $response );
        return $response;
    }

    /**
     * ディスパッチする
     */
    public function dispatch( $module, $controller, $action, $request, $response = null ) {

        // コントローラを探す
        $file = sprintf( 
            $this->_appDir.'/module/%s/controller/%s.php',
            $this->_pathSanitize($module),
            ucfirst($this->_pathSanitize($controller))
        );

        if( "" == realpath($file) ) {
            throw new \Exception('存在しないURLです['.$file.']');
        }

        require_once $file;

        $class = sprintf(
            '%s%sController',
            ucfirst($module),
            ucfirst($controller)
        );

        if( !class_exists($class) ) {
            throw new \Exception('存在しないURLです['.$file.']');
        }

        $controller = new $class( $this );
        $controller->dispatch( $action, $request, $response );

        return $response;
    }
}
