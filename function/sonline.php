<?php
require_once 'class/sonline.class.php';
require_once 'class/output.class.php';
require_once 'functions.php';
$isLocal = true; // 是否开启本地数据源搜索(Anime1, Bimibimi, 枫林网, Qinmei)

if ($_POST["kt"]) {
    $keyTitle = RemoveXSS($_POST["kt"]);
} elseif ($_GET["kt"]) {
    $keyTitle = RemoveXSS($_GET["kt"]);
} else {
    die('error');
}

$sonline = new allSearchOnline();
$output = new allOutput();

// 添加搜索源_在线抓取源
$urls = array();
array_push($urls, 'https://api.bilibili.com/x/web-interface/search/all?jsonp=jsonp&keyword=' . urlencode($keyTitle)); //0
array_push($urls, 'http://www.baidu.com/s?wd=site%3Awww.dilidili.name%20' . urlencode($keyTitle) . '&pn=0'); //1
array_push($urls, 'http://www.fengchedm.com/common/search.aspx?key=' . urlencode($keyTitle)); //2
array_push($urls, 'http://search.pptv.com/result?search_query=' . urlencode($keyTitle) . '&result_type=3'); //3
array_push($urls, 'http://www.baidu.com/s?wd=site%3Awww.le.com%20' . urlencode($keyTitle) . '&pn=0'); //4
array_push($urls, 'http://www.baidu.com/s?wd=site%3Awww.iqiyi.com%20' . urlencode($keyTitle) . '&pn=0'); //5
array_push($urls, 'http://www.baidu.com/s?wd=site%3Awww.youku.com%20' . urlencode($keyTitle) . '&pn=0'); //6
array_push($urls, 'http://www.baidu.com/s?wd=site%3Av.qq.com%20' . urlencode($keyTitle) . '&pn=0'); //7
array_push($urls, 'https://qinmei.video/api/v1/animate?title=' . urlencode($keyTitle)); //8
array_push($urls, 'http://www.nicotv.me/video/search/' . urlencode($keyTitle) . '.html'); //9

// 抓取数据并整理数据
$oriData = $sonline->__getSDdata($urls);

$frst = array();
$frst['bilibili'] = $oriData[0];
$frst['dilidili'] = $oriData[1];
$frst['fcdm'] = $oriData[2];
$frst['pptv'] = $oriData[3];
$frst['letv'] = $oriData[4];
$frst['iqiyi'] = $oriData[5];
$frst['youku'] = $oriData[6];
$frst['tencenttv'] = $oriData[7];
$frst['qinmei'] = $oriData[8];
$frst['nicotv'] = $oriData[9];

// 结构化数据整理_在线抓取源+本地源
$data = $sonline->__doS($frst, $keyTitle, $isLocal);

if ($_POST["kt"]) {
    // 输出结果
    $output->__doOutputSOnline($data['bilibili'], '哔哩哔哩', 'www.bilibili.com', $keyTitle, 'bilibili.ico');
    if ($isLocal) {
        $output->__doOutputSOnline($data['anime1'], 'Anime1', 'anime1.me', $keyTitle, 'anime1.ico');
        $output->__doOutputSOnline($data['bimibimi'], 'Bimibimi', 'www.bimibimi.tv', $keyTitle, 'bimibimi.ico');
    }
    $output->__doOutputSOnline($data['nicotv'], '妮可动漫', 'www.nicotv.me', $keyTitle, 'nicotv.ico');
    $output->__doOutputSOnline($data['qinmei'], 'Qinmei', 'qinmei.video', $keyTitle, 'qinmei.png');
    $output->__doOutputSOnline($data['dilidili'], '嘀哩嘀哩', 'www.dilidili.name', $keyTitle, 'dilidili.ico');
    if ($isLocal) {
        $output->__doOutputSOnline($data['8maple'], '枫林网', '8maple.ru', $keyTitle, '8maple.ico');
    }
    $output->__doOutputSOnline($data['iqiyi'], '爱奇艺', 'www.iqiyi.com', $keyTitle, 'iqiyi.png');
    $output->__doOutputSOnline($data['tencenttv'], '腾讯视频', 'v.qq.com', $keyTitle);
    $output->__doOutputSOnline($data['fcdm'], '风车动漫', 'www.fengchedm.com', $keyTitle, 'none.png');
    $output->__doOutputSOnline($data['youku'], '优酷', 'www.youku.com', $keyTitle, 'youku.png');
    $output->__doOutputSOnline($data['pptv'], 'PPTV', 'www.pptv.com', $keyTitle, 'pptv.ico');
    $output->__doOutputSOnline($data['letv'], '乐视', 'www.le.com', $keyTitle, 'letv.ico');
} elseif ($_GET["kt"]) {
    // API Json 输出
    $data = delairAnimeHeader($data);
    $output->__doOutputOri($data);
} else {
    echo 'error';
}
