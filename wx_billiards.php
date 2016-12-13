<?php
// 谁是卧底游戏
function Billiards($key, $userid)
{
    try {
        $mysql = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        $mysql->exec('SET CHARACTER SET ' . DB_CHARSET);
        $mysql->exec('SET NAMES ' . DB_CHARSET);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    
    if ($key == '多人台球') {
        $text = "请输入要玩桌球的人数(3~5)\n其他用户请输入房间号\n回复【换】重新开始游戏\n回复【退出】即可退出多人台球游戏";
    } elseif ($key == '退出') {
        $text = "退出成功";
    } elseif ($key >= 3 && $key <= 5) {
        $eachs = (15 - (15 % $key)) / $key;
        $data = getBiDatas($userid);
        if ($data) {
            $allcount = $key;
            $sql = "update bi_rooms set allcount= '$allcount', nowcount='0',numbers='123456789ABCDEF',eachs='$eachs' where userid= '$userid'";
            execSql($sql);
            $datas = getBiDatas($userid);
            $roomid = $datas['roomid'];
            $text = "您是房主\n游戏房间号为" . $roomid . "\n游戏人数为：" . $allcount . "\n每人分得：" . $eachs . "\n回复[换]，直接重新开始游戏，\n";
        } else {
            $allcount = $key;
            $sql = "insert into bi_rooms(userid,allcount,nowcount,numbers,eachs) values('$userid', '$allcount', '0','123456789ABCDEF',$eachs)";
            execSql($sql);
            $datas = getBiDatas($userid);
            $roomid = $datas['roomid'];
            $text = "您是房主\n游戏房间号为" . $roomid . "\n游戏人数为：" . $allcount . "\n每人分得：" . $eachs . "\n回复[换]，直接重新开始游戏，\n";
        }
    } elseif ($key >= 1000 && $key <= 9999) {
        $sql = "select * from bi_rooms where roomid = '$key'";
        $data = $mysql->query($sql)->fetch();
        if ($data) {
            if ($data['nowcount'] < $data['allcount']) {
                $nowcount = (int) $data['nowcount'];
                $numbers = $data['numbers'];
                $eachs = $data['eachs'];
                $nowcount ++;
                $text = "";
                for ($i = 0; $i < $eachs; $i ++) {
                    $temp = getNumbers($numbers);
                    $text .= hexdec($temp) . "\n";
                    $numbers = preg_replace("/['$temp']+/i", '', $numbers);
                }
                $sql = "update bi_rooms set nowcount = '$nowcount',numbers= '$numbers'  where roomid = '$key'";
                execSql($sql);
            } else {
                $text = "房间人数已满";
            }
        } else {
            $text = "您输入的房间号无效";
        }
    } elseif ($key == '换') {
        $sql = "update bi_rooms set nowcount='0',numbers='123456789ABCDEF' where userid= '$userid'";
        execSql($sql);
        $datas = getBiDatas($userid);
        $text = "更换成功\n游戏房间号为" . $datas['roomid'] . "\n游戏人数为：" . $datas['allcount'] . "\n每人分得：" . $datas['eachs'] . "\n回复[换]，直接重新开始游戏，\n";
    } else {
        $text = '好吧，你说的我听不懂了';
    }
    return $text;
}

/*
 * function execSql($sql){
 * try {
 * $mysql = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
 * $mysql->exec('SET CHARACTER SET '.DB_CHARSET);
 * $mysql->exec('SET NAMES '.DB_CHARSET);
 * $mysql->exec($sql);
 * } catch (PDOException $e) {
 * print "Error!: " . $e->getMessage() . "<br/>";
 * die();
 * }
 * return true;
 * }
 *
 */
function getBiDatas($userid)
{
    try {
        $mysql = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        $mysql->exec('SET CHARACTER SET ' . DB_CHARSET);
        $mysql->exec('SET NAMES ' . DB_CHARSET);
        
        $sql = "select * from bi_rooms where userid = '$userid'";
        $datas = $mysql->query($sql)->fetch();
    } catch (PDOException $e) {
        $datas = false;
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    return $datas;
}

function getNumbers($numbers)
{
    $randNum = rand(0, strlen($numbers) - 1);
    return substr($numbers, $randNum, 1);
}
?>
