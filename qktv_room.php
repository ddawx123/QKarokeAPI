<?php
//$roomId = "39d9db84232b328d314a71";
header("Content-Type: text/html; charset=UTF-8");
echo "<center><strong>全民K歌歌房实时监控系统_v1.0</strong></center>";
getKTVRoomInfo(trim(@$_GET['roomId']));

/**
 * 全民K歌歌房状态获取函数（基于JSON数据解析方案）
 * @param string $rid 传入需要查询的房间ID
 * @return string 返回查询结果模型
 * @copyright 2017 DingStudio.Tech All Rights Reserved
 */
function getKTVRoomInfo($rid = null) {
    if ($rid == null) {
        die("<center><strong>非法操作提醒：</strong>抱歉，您没有输入有效房间号！请检查后重试。</center><hr><center>小丁工作室 版权所有</center>");
    }
    $roomId = $rid;
    $api = "https://cgi.kg.qq.com/fcgi-bin/fcg_ktv_room_info?jsonpCallback=callback_0&inCharset=utf-8&outCharset=utf-8&format=json&roomid=".$roomId."&method=room_info&queryTime=".date('YmdHis',time());
    $response = file_get_contents($api);
    if ($response == '') {
        die("<center><strong>服务器内部错误提醒：</strong>抱歉，程序无法请求远程服务器！请稍候重试。技术错误信息报告：系统无法连接腾讯服务器，可能是系统与腾讯服务器的连接被阻断。错误代码：502</center><hr><center>小丁工作室 版权所有</center>");
    }
    $result = json_decode($response);
    if ($result->data->basic->stKtvRoomInfo->iRoomStatus == 0) {
        echo "<hr>温馨提示：抱歉，系统检测到该房间当前尚未开启！请通知房主开启再试啦~<br>";
    }
    echo "<hr>";
    if ($result->data->basic->stKtvRoomInfo->iShowStartTime == 0) {
        echo "该房间ID对应的歌房可能不存在，请检查后重试！<br>";
    }
    else {
        echo "歌房标题(Room Title)：".$result->data->basic->stKtvRoomInfo->strName."<br>";
        echo "歌房公告(Room Notification)：".$result->data->basic->stKtvRoomInfo->strNotification."<br>";
        echo "歌房唯一ID(Room ID): ".$roomId."<br>";
        echo "歌房所有者昵称(Room Owner): ".$result->data->basic->stKtvRoomInfo->stAnchorInfo->nick."<br>";
        echo "歌房即时在线人数(Member Number): ".$result->data->basic->stKtvRoomInfo->iMemberNum."<br>";
        echo "歌房开启时间(Room Start Time): ".date('Y年m月d日H时i分s秒',$result->data->basic->stKtvRoomInfo->iShowStartTime)."<br>";
        if ($result->data->basic->stKtvRoomInfo->iRoomStatus == 0) {
            echo "歌房释放时间(Room End Time): ".date('Y年m月d日 H时i分s秒',$result->data->basic->stKtvRoomInfo->iShowEndTime)."<br>";
        }
        if ($result->data->basic->stKtvRoomInfo->iRoomStatus != 0) {
            echo "歌房外链URL(Room Url): ".$result->data->basic->stKtvRoomShareInfo->strShareUrl."<br>";
        }
    }
}
echo "<hr><center>小丁工作室 版权所有</center>";
