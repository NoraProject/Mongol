<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * のらプロジェクトファイル
 *
 * @author     ハジメ <mail@hazime.org>
 * @copyright  opyright (c) 2013, nora project all rights reserved.
 * @license    http://www.hazime.org/license/licence.txt
 * @version    $id$
 */
namespace Nora\ClassLoader;

/**
 * クラスローダー
 *
 * 自動的にクラスを読み込む
 */
class Loader {

    const NAMESPACE_SEPARATOR = '\\';

    static private $_instance = false;

    private $_prefixes = array();

    /**
     * シングルトンメソッド
     */
    static public function getLoader( ) {
        if( static::$_instance ) return static::$_instance;
        return static::$_instance = new static;
    }

    /**
     * 起動時にレジストする
     */
    private function __construct( ) {
        $this->register();
    }

    /**
     * ローダを登録する
     */
    public function register( ) {
        spl_autoload_register( array($this,'loadClass'), false, true );
    }

    /**
     * ローダを解除する
     */
    public function unregister( ) {
        spl_autoload_unregister( array($this,'loadClass') );
    }


    /**
     * プレフィックスを追加する
     *
     * @param string クラス名のプレフィックス
     * @param mixed ディレクトリ名かその配列
     */
    public function addPrefix( $prefix, $dir = false) {
        if( !isset($this->_prefixes[$prefix]) ) $this->_prefixes[$prefix] = array();
        $this->_prefixes[$prefix] += (array) $dir;
        return $this;
    }

    /**
     * クラス定義ファイルを探す
     */
    public function findClassFile( $class_name ) {
        //NAMESPACE_SEPARATORから始まる場合はストリップする
        if( $class_name[0] == self::NAMESPACE_SEPARATOR) 
            return $this->findClassFile( substr($class_name,1) );

        // プレフィックスから候補ディレクトリを取得する
        foreach( $this->_prefixes as $prefix => $dirs ) {
            if( 0 === strpos( $class_name, $prefix ) ) {
                //printf('プレフィックスが一致 %s %s'.PHP_EOL, $class_name, $prefix);
                $no_prefixed_class_name = substr( $class_name, strlen($prefix) );

                if (false !== $pos = strrpos($no_prefixed_class_name, self::NAMESPACE_SEPARATOR)) {
                    $class_file_path = str_replace(
                        self::NAMESPACE_SEPARATOR,
                        DIRECTORY_SEPARATOR,
                        substr($no_prefixed_class_name, 0, $pos)
                    ).DIRECTORY_SEPARATOR;
                    $class_base_name = substr($no_prefixed_class_name, $pos + 1);
                } else {
                    $class_file_path = '';
                    $class_base_name = $class_name;
                }
                $class_file_path .= str_replace('_', DIRECTORY_SEPARATOR, $class_base_name) . '.php';
                //printf('class_file_path %s'.PHP_EOL, $class_file_path);

                foreach( $dirs as $dir ) {
                    $final_class_file_path = $dir.'/'.$class_file_path;
                    //printf('読み込み対象ファイル %s'.PHP_EOL, $final_class_file_path);
                    if(file_exists( $final_class_file_path )) {
                        return $final_class_file_path;
                    }
                }

            }
        }
        return false;
    }

    /**
     * ローダを実行する
     */
    public function loadClass( $class_name ) {
        $class_file = $this->findClassFile( $class_name );
        if( $class_file ) require_once $class_file;
    }
}
