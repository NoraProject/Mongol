<?php
/**
 * のらプロジェクトファイル
 *
 * @author     ハジメ <mail@hazime.org>
 * @copyright  opyright (c) 2013, nora project all rights reserved.
 * @license    http://www.hazime.org/license/licence.txt
 * @version    $id$
 */
namespace Nora;

/**
 * ノラクラス
 */
class Nora {

    static private $_instance = false;

    static public function getInstance() {
        if( static::$_instance ) return static::$_instance;
        return static::$_instance = new static;
    }

    private function __construct( ) {

    }

}
