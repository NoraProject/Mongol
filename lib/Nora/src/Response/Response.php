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
 * レスポンスクラス
 */
class Response {
    use Storage\SimpleStorageTrait;

    private $_contentType = 'html';

    public function setContentType( $type ) {
        $this->_contentType = $type;
    }

    public function getRenderer( ) {
        return ResponseRenderer::create( $this->_contentType );
    }

    public function toString( ) {
        $renderer = $this->getRenderer();
        $renderer->sendHeader();
        echo $renderer->render( $this );
    }


}
