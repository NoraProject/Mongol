<?php
/**
 * @author     ハジメ <mail@hazime.org>
 * @copyright  opyright (c) 2013, nora project all rights reserved.
 * @license    http://www.hazime.org/license/licence.txt
 * @version    $id$
 */

use Nora\Controller\Controller;

class AppCsvController extends Controller{

    public function getMonthlyAction( $req, $res ){
        $dataDir = YAMAHIRO_SYSTEM_DIR;
        header('Content-Type: text/csv; charset="cp932"');
        header('Content-Disposition: attachment; filename=yamahiro_monthly.csv');
        echo mb_convert_encoding(file_get_contents($dataDir."/KOUBAI/DATA/APP/MONTHLY_CSV.csv"),'cp932');
        die();
    }

    public function getDailyAction( $req, $res ){
        $dataDir = YAMAHIRO_SYSTEM_DIR;
        header('Content-Type: text/csv; charset="cp932"');
        header('Content-Disposition: attachment; filename=yamahiro_daily.csv');
        echo mb_convert_encoding(file_get_contents($dataDir."/KOUBAI/DATA/APP/DAILY_CSV.csv"),'cp932');
        die();
    }
}


