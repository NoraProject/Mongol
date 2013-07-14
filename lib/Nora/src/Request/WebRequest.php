<?php
/**
 * のらプロジェクトファイル
 *
 * @author     ハジメ <mail@hazime.org>
 * @copyright  opyright (c) 2013, nora project all rights reserved.
 * @license    http://www.hazime.org/license/licence.txt
 * @version    $id$
 */
namespace Nora\Request;


use Nora\Storage;

/**
 * Webリクエストクラス
 */
class WebRequest {
    use Storage\SimpleStorageTrait;

    private $_requestMethod,$_requestProtocol,$_requestURI;

    /**
     * Webリクエストを起動
     */
    public function __construct( ) {
        $this->_initWebRequest( );
    }

    /**
     * Webリクエストを初期化
     */
    private function _initWebRequest( ) {
        $this->setRequestMethod($_SERVER['REQUEST_METHOD']);
        $this->setRequestProtocol($_SERVER['SERVER_PROTOCOL']);
        $this->setRequestURI(str_replace($_SERVER['SCRIPT_NAME'],'',$_SERVER['REQUEST_URI']));
    }

    /**
     * リクエストURIを登録する
     */
    public function setRequestURI( $value ) {
        $this->_requestURI = $value;
    }

    /**
     * リクエストプロトコルを登録する
     */
    public function setRequestProtocol( $value ) {
        $this->_requestProtocol = $value;
    }

    /**
     * リクエストメソッドを登録する
     */
    public function setRequestMethod( $value ) {
        $this->_requestMethod = $value;
    }

    /**
     * リクエストURIを参照する
     */
    public function getRequestURI( ) {
        return $this->_requestURI;
    }

    public function getVarInt( $name, $default = null ) {
        $val = $this->getVar($name,$default);
        if( $val == null ) return null;
        return intval($val);
    }
    public function getVarEnum( $name ) {
        $val = $this->getVar($name);
        $args = array_slice(func_get_args(),1);
        if( in_array($val, $args) ){
            return $val;
        }else{
            return false;
        }
    }


}
