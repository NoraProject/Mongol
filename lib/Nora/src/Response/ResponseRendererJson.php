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
class ResponseRendererJson extends ResponseRenderer {

    public function sendHeader(  ) {
        header('content-type: application/json; charset=utf8');
    }

    public function render( $response ) {
        $datas = $response->toArray();
        return json_encode($datas);
    }
}
