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
// 定数を定義する
if(!defined('NORA_SCRIPT')) define('NORA_SCRIPT', __DIR__);
if(!defined('NORA_HOME')) define('NORA_HOME', realpath(NORA_SCRIPT.'/..'));
if(!defined('NORA_SRC')) define('NORA_SRC', realpath(NORA_HOME.'/src'));
if(!defined('NORA_VENDOR')) define('NORA_VENDOR', realpath(NORA_HOME.'/vendor'));

// パスを定義
ini_set('include_path', NORA_SRC.PATH_SEPARATOR.NORA_VENDOR.PATH_SEPARATOR.ini_get('include_path'));

// AutoClassLoaderの起動
require_once NORA_SRC.'/ClassLoader/Loader.php';
require_once NORA_SCRIPT.'/functions.php';

// ネームスペースNoraをソース置き場から検索する様に登録する
Nora\ClassLoader\Loader::getLoader( )->addPrefix('Nora', NORA_SRC);


// ログレベルを定義
// メモ
// fatal 
// 想定外の状況で、アプリケーションの継続が不可能である場合。それこそ syslog に書き込みたい位のシビアなケースがこれ。ちなみに syslogd に fatal レベルを流すと、デフォルトではベルがなったりする程である。一般的なアプリでは指定しない方が無難かな？ 
// error 
// 何かエラーが生じ、サーバだったら停止してるかリクエスト処理に大きな問題がある状態を示す。かなり緊急に対応を要する問題がある、とわかる程度。 
// warn 
// 警告。何か問題があったが、一応不完全ながらリクエストは完結している状態を示す。 
// info 
// 情報。実運用の最低ログレベル。サーバの起動とか、リクエストの受け付け・完了をこのレベルにしておくのが良かろう。 
// debug 
// デバッグログ。デバッグ用のトレースログを含み、ログファイルのサイズが爆発することなぞ気にせずに「問題解決のためにログを取りまくりたい！」時に指定するものである。当然、実運用でこのログレベルのまま運用すると、後でログファイルを見る時にうんざりする...そんなくらいのレベル。 
// trace 今までデバッグレベルが大まか過ぎたのを解消するため、一番詳細なログレベルとして追加された。ホントに詳細な動作チェックをするケースで使うべきレベルである。

define('NORA_LOG_FATAL', 1);
define('NORA_LOG_ERROR', 2);
define('NORA_LOG_WARN', 4);
define('NORA_LOG_INFO', 8);
define('NORA_LOG_DEBUG', 16);
define('NORA_LOG_TRACE', 32);
define('NORA_LOG_ALL', 63);

# PHPの設定
mb_language("Japanese");
mb_internal_encoding("UTF-8");
