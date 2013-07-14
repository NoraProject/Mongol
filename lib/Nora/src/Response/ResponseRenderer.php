<?php
/**
 * のらプロジェクトファイル
 *
 * @author     ハジメ <mail@hazime.org>
 * @copyright  opyright (c) 2013, nora project all rights reserved.
 * @license    http://www.hazime.org/license/licence.txt
 * @version    $id$
 */
namespace Nora\Response;


use Nora\Storage;

/**
 * レスポンレンダラクラス
 */
abstract class ResponseRenderer {

    static public function create( $type ) {
        $class = __NAMESPACE__.'\\ResponseRenderer'.ucfirst($type);
        return new $class();
    }

    public function render( $response ) {
        var_dump($response);
    }

}
