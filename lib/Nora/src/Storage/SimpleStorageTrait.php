<?php
/**
 * のらプロジェクトファイル
 *
 * @author     ハジメ <mail@hazime.org>
 * @copyright  opyright (c) 2013, nora project all rights reserved.
 * @license    http://www.hazime.org/license/licence.txt
 * @version    $id$
 */
namespace Nora\Storage;

trait SimpleStorageTrait {

    private $_vars = array();

    /**
     * データの出し入れ
     * ====================
     */

    /**
     * データの一括セット
     */
    public function setVars( $vars ) {
        $this->_vars = array_merge($this->_vars, $vars);
    }

    /**
     * データの取得
     */
    public function getVar( $name, $default = null ) {
        if( $this->hasVar($name) ) return $this->_vars[$name];
        return $default;
    }

    public function hasVar($name){
        return isset($this->_vars[$name]);
    }

    public function toArray() {
        return $this->_vars;
    }


}
