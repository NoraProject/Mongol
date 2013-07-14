<?php
/**
 * のらプロジェクトファイル
 *
 * @author     ハジメ <mail@hazime.org>
 * @copyright  opyright (c) 2013, nora project all rights reserved.
 * @license    http://www.hazime.org/license/licence.txt
 * @version    $id$
 */

use Nora\Controller\Controller;

class ApiIndexController extends Controller{


    public function __construct( ) {

    }

    public function getAllYuryouAction( $request, $response){
        $dataDir = YAMAHIRO_SYSTEM_DIR;

        $datas = array(array('店舗','販売油量'));
        foreach( file($dataDir."/KOUBAI/DATA/APP/LAST_MONTH_ALL_YURYOU") as $line ){
            $data = explode(' ', trim($line)); // 201306 0001 911000 52968 150.87 7.99392e+06 35.3048 0 25.1097 28.7185 10.867 0 0 0 0
            $datas[] = array($data[1],$data[3]/1000);
        }

        $response->setVars(array('status'=>0,'datas'=>$datas));
        $response->setContentType('json');
    }

    private function _retriveLatest( $ss_code, $item_code ) {
        $dateFormat = "y/m/d";
        $dataDir = YAMAHIRO_SYSTEM_DIR;

        $status = file_get_contents($dataDir."/DATA/APP/IMPORT_STATUS");
        $status = explode(' ', trim($status)); // 0:データ 1:システム
        $last = file_get_contents($dataDir.sprintf("/KOUBAI/DATA/APP/STATUS/%04d/%06d",$ss_code,$item_code));
        $last = explode(' ', trim($last)); // 0:売値 1:原価

        return array(
            'update_time'=>date($dateFormat,strtotime($status[0])),
            'system_update_time'=>date($dateFormat,strtotime($status[1])),
            'urine'=>$last[0],
            'genka'=>$last[1]
        );
    }

    private function _retriveKubun( $ss_code, $item_code ) {
        $dateFormat = "y/m";
        $dataDir = YAMAHIRO_MOVING_AVERAGE_MONTH_DIR;
        $label = explode(' ',
            "年月 掛客 自社券 クレジット 現金フリー 現金固定 計上自家使用 非計上自家使用 自社代行 他社代行"
        );


        $data = sprintf(
            $dataDir.'/%04d/%06d',
            $ss_code,
            $item_code
        );

        $fp = fopen( $data, "r" );
        $datas = array($label);
        while( $line = fgets($fp,1024)) {
            $parts =  explode(' ', trim($line));
            $parts[0] = date($dateFormat, strtotime(str_pad($parts[0],8,0,STR_PAD_RIGHT)));
            $datas[] = array(
                $parts[0],
                floatval($parts[6]),
                floatval($parts[7]),
                floatval($parts[8]),
                floatval($parts[9]),
                floatval($parts[10]),
                floatval($parts[11]),
                floatval($parts[12]),
                floatval($parts[13]),
                floatval($parts[14]),
            );
        }
        fclose($fp);
        return $datas;

    }
    private function _retriveMovingAverage( $ss_code, $item_code, $mode ) {
$table = array(
'0001'=>'大久保SS',
'0003'=>'プラザ松庵SS',
'0022'=>'国立富士見台SS',
'0023'=>'砂川中央SS',
'0041'=>'セルフ新座SS',
'0002'=>'セルフ高田馬場SS',
'0011'=>'セルフ大森アベニューSS',
'0007'=>'セルフ武蔵野SS',
'0027'=>'セルフ下連雀SS',
'0024'=>'セルフ調布ヶ丘SS',
'0030'=>'セルフ日野南平SS',
'0025'=>'セルフSolnet昭島SS',
'0028'=>'セルフ八王子北野SS',
'0032'=>'セルフ相原SS',
'0029'=>'セルフ桜美林学園前SS',
'0034'=>'セルフ小金井橋SS',
'0031'=>'セルフ小平SS',
'0036'=>'セルフ福生ナンバーワンSS',
'0006'=>'ラポート野火止SS',
'0037'=>'セルフ所沢花園SS',
'0013'=>'セルフ蕨SS',
'0010'=>'セルフ万世橋SS',
'0012'=>'セルフ永代橋SS',
'0033'=>'セルフ一之江SS',
'0039'=>'セルフ市川橋SS',
'0040'=>'セルフ水元公園SS',
'0044'=>'セルフ西府SS',
'0043'=>'セルフ目白台SS',
'0045'=>'セルフ三鷹新川SS',
'0046'=>'セルフ所沢下富SS',
'0047'=>'セルフ日本橋SS',
'0048'=>'セルフ高島屋前SS',
'0049'=>'セルフ代々木SS',
'0050'=>'セルフ赤坂SS'
);
        if( 'day' == $mode ) {
            $dateFormat = "y/m/d";
            $dataDir = YAMAHIRO_MOVING_AVERAGE_DAY_DIR;
            $label = array('年月日',$table[sprintf('%04d',$ss_code)].':販売数量',$table[sprintf('%04d',$ss_code)].':単価');
        }else{
            $dateFormat = "y/m";
            $dataDir = YAMAHIRO_MOVING_AVERAGE_MONTH_DIR;
            //$label = array('年月日','ハイオク・レギュラー(販売数量)','単価');
            $label = array('年月日',$table[sprintf('%04d',$ss_code)].':販売数量',$table[sprintf('%04d',$ss_code)].':単価');
        }

        $data = sprintf(
            $dataDir.'/%04d/%06d',
            $ss_code,
            $item_code
        );

        //$label = array('date','amount','price','mva_amount','mva_price');
        $fp = fopen( $data, "r" );
        $table_datas = $datas = array($label);
        while( $line = fgets($fp,1024)) {
            $parts =  explode(' ', trim($line));
            $parts[0] = date($dateFormat, 1+strtotime(str_pad($parts[0],8,1,STR_PAD_RIGHT)));
            $datas[] = array(
                $parts[0],
                floatval($parts[17]) / 1000,
                floatval($parts[16])
            );
            $table_datas[] = array(
                $parts[0],
                floatval($parts[3]) / 1000,
                floatval($parts[4])
            );
        }
        fclose($fp);
        return array('mv'=>$datas,'table'=>$table_datas);
    }

    /**
     * 移動平均取得(日店商品別)
     */
    public function getMovingAverageAction( $request, $response ) {

        $ss_code = $request->getVarInt('ss_code');
        $item_code = $request->getVarInt('item_code');

        $datas = array();
        $datas['day'] = $this->_retriveMovingAverage( $ss_code, $item_code, 'day' );
        $datas['month'] = $this->_retriveMovingAverage( $ss_code, $item_code, 'month' );
        $datas['kubun'] = $this->_retriveKubun( $ss_code, $item_code);
        $datas['latest'] = $this->_retriveLatest( $ss_code, $item_code);


        $response->setVars(array('status'=>0,'datas'=>$datas));
        $response->setContentType('json');
    }

    /**
     * 移動平均取得(日店商品別)
     */
    public function getMultiAction( $request, $response ) {

        $ss_codes = explode(',',$request->getVar('ss_code'));
        $item_code = $request->getVarInt('item_code');
        $mode = $request->getVarInt('mode');
        if(!$mode) $mode = 1;

        // 全データ取得
        $month_datas = $day_datas = $day_index = $month_index = array();
        foreach( $ss_codes as $ss_code ) {
            $day_datas[$ss_code] = array();
            $data = $this->_retriveMovingAverage( $ss_code, $item_code, 'day', $mode );
            foreach( $data['mv'] as $recode ) {
                $day_datas[$ss_code][($recode[0])] = $recode;
                $day_index[($recode[0])] = $recode[0];
            }
            $data = $this->_retriveMovingAverage( $ss_code, $item_code, 'month', $mode );
            foreach( $data['mv'] as $recode ) {
                $month_datas[$ss_code][($recode[0])] = $recode;
                $month_index[($recode[0])] = $recode[0];
            }
        }

        foreach( $day_index as $index ){
            $datas['day']['mv'][$index] = array();
            foreach( $ss_codes as $ss_code ){
                if(isset($day_datas[$ss_code][$index])){
                    $datas['day']['mv'][$index] = array_merge(
                        $datas['day']['mv'][$index],
                        array_slice($day_datas[$ss_code][$index],1)
                    );
                }else{
                    $datas['day']['mv'][$index][] = 0;
                    $datas['day']['mv'][$index][] = 0;
                }
            }
        }
        foreach( $month_index as $index ){
            $datas['month']['mv'][$index] = array();
            foreach( $ss_codes as $ss_code ){
                if(isset($month_datas[$ss_code][$index])){
                    $datas['month']['mv'][$index] = array_merge(
                        $datas['month']['mv'][$index],
                        array_slice($month_datas[$ss_code][$index],1)
                    );
                }else{
                    $datas['month']['mv'][$index][] = 0;
                    $datas['month']['mv'][$index][] = 0;
                }
            }
        }

        foreach($datas['day']['mv'] as $k=>$v) {
            array_unshift($v,$k);
            $returns['day']['mv'][] = $v;
        }
        foreach($datas['month']['mv'] as $k=>$v) {
            array_unshift($v,$k);
            $returns['month']['mv'][] = $v;
        }

        foreach($datas['day']['mv'] as $k=>$v ){
            $recode = array($k);
                for($i=0;$i<count($v);$i+=2){
                    $recode[] = $v[$i];
                }
                $returns['yuryou'][] = $recode;

            $recode = array($k);
                for($i=1;$i<count($v);$i+=2){
                    $recode[] = $v[$i];
                }
                $returns['tanka'][] = $recode;
            }
        if( $mode == 2 ){
        }

        //$response->setVars(array('status'=>0,'datas'=>$datas));
        $response->setVars(array('status'=>0,'datas'=>$returns));
        $response->setContentType('json');
    }

}
