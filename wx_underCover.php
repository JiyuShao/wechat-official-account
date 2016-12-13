<?php
// 谁是卧底游戏
function UnderCover($key, $username)
{
    try {
        $mysql = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        $mysql->exec('SET CHARACTER SET ' . DB_CHARSET);
        $mysql->exec('SET NAMES ' . DB_CHARSET);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    $userid = $username;
    if ($key == '谁是卧底') {
        $text = "如果您是法官请输入游戏人数(4~13之间，不包括法官哦)\n其他用户请输入房间号\n回复【规则】了解游戏规则\n回复【惩罚】查看惩罚游戏\n回复【退出】即可退出谁是卧底游戏";
    } elseif ($key == '退出') {
        $text = "退出成功";
    } elseif ($key >= 4 && $key <= 6) {
        $data = getDatas($userid);
        if ($data) {
            $allcount = $key;
            $undercoverid1 = rand(1, $key);
            $words = getWords();
            $word1 = $words['word1'];
            $word2 = $words['word2'];
            $sql = "update uc_rooms set allcount= '$allcount', nowcount='0',undercoverid1= '$undercoverid1',word1= '$word1',word2= '$word2' where userid= '$username'";
            execSql($sql);
            $datas = getDatas($userid);
            $roomid = $datas['roomid'];
            $text = "您是法官\n游戏房间号为" . $roomid . "\n平民词：" . $word1 . "\n卧底词：" . $word2 . "\n卧底是：" . $undercoverid1 . "号\n游戏人数为：" . $allcount . "\n回复[换]，换一组词，\n(一局结束后，不必重建房，回复[换]直接换词)\n如果需要自定义词语，请输入”改“＋卧底词和平民词,如：改状元，冠军";
        } else {
            $userid = $username;
            $allcount = $key;
            $undercoverid1 = rand(1, $key);
            $words = getWords();
            $word1 = $words['word1'];
            $word2 = $words['word2'];
            $sql = "insert into uc_rooms(userid,allcount,nowcount,undercoverid1,word1,word2) values('$username', '$allcount', '0','$undercoverid1' ,'$word1' ,'$word2')";
            execSql($sql);
            $datas = getDatas($userid);
            $roomid = $datas['roomid'];
            $text = "您是法官\n游戏房间号为" . $roomid . "\n平民词：" . $word1 . "\n卧底词：" . $word2 . "\n卧底是：" . $undercoverid1 . "号\n游戏人数为：" . $allcount . "\n回复[换]，换一组词，\n(一局结束后，不必重建房，回复[换]直接换词)\n如果需要自定义词语，请输入”改“＋卧底词和平民词,如：改状元，冠军";
        }
    } elseif ($key >= 7 && $key <= 10) {
        $data = getDatas($userid);
        if ($data) {
            $allcount = $key;
            $undercoverid1 = rand(1, $key);
            $undercoverid2 = randexcp1($undercoverid1, $allcount);
            $words = getWords();
            $word1 = $words['word1'];
            $word2 = $words['word2'];
            $sql = "update uc_rooms set allcount= '$allcount', nowcount= '0',undercoverid1= '$undercoverid1',undercoverid2= '$undercoverid2',word1= '$word1',word2 = '$word2' where userid = '$username'";
            execSql($sql);
            $datas = getDatas($userid);
            $roomid = $datas['roomid'];
            $text = "您是法官\n游戏房间号为" . $roomid . "\n平民词：" . $word1 . "\n卧底词：" . $word2 . "\n卧底1是：" . $undercoverid1 . "号\n卧底2是：" . $undercoverid2 . "号\n游戏人数为：" . $allcount . "\n回复[换]，换一组词，\n(一局结束后，不必重建房，回复[换]直接换词)\n如果需要自定义词语，请输入”改“＋卧底词和平民词,如：改状元，冠军";
        } else {
            $userid = $username;
            $allcount = $key;
            $undercoverid1 = rand(1, $key);
            $undercoverid2 = randexcp1($undercoverid1, $allcount);
            $words = getWords();
            $word1 = $words['word1'];
            $word2 = $words['word2'];
            $sql = "insert into uc_rooms(userid,allcount,nowcount,undercoverid1,undercoverid2,word1,word2) values('$username', '$allcount', '0','$undercoverid1' ,'$undercoverid2' ,'$word1' ,'$word2')";
            execSql($sql);
            $datas = getDatas($userid);
            $roomid = $datas['roomid'];
            $text = "您是法官\n游戏房间号为" . $roomid . "\n平民词：" . $word1 . "\n卧底词：" . $word2 . "\n卧底1是：" . $undercoverid1 . "号\n卧底2是：" . $undercoverid2 . "号\n游戏人数为：" . $allcount . "\n回复[换]，换一组词，\n(一局结束后，不必重建房，回复[换]直接换词)\n如果需要自定义词语，请输入”改“＋卧底词和平民词,如：改状元，冠军";
        }
    } elseif ($key >= 11 && $key <= 13) {
        $data = getDatas($userid);
        if ($data) {
            $allcount = $key;
            $undercoverid1 = rand(1, $key);
            $undercoverid2 = randexcp1($undercoverid1, $allcount);
            $whiteboardid = randexcp2($undercoverid1, $undercoverid2, $allcount);
            $words = getWords();
            $word1 = $words['word1'];
            $word2 = $words['word2'];
            $sql = "update uc_rooms set allcount= '$allcount',nowcount = '0', undercoverid1= '$undercoverid1',undercoverid2= '$undercoverid2',whiteboardid='$whiteboardid',word1= '$word1',word2= '$word2' where userid = '$username'";
            execSql($sql);
            $datas = getDatas($userid);
            $roomid = $datas['roomid'];
            $text = "您是法官\n游戏房间号为" . $roomid . "\n平民词：" . $word1 . "\n卧底词：" . $word2 . "\n卧底1是：" . $undercoverid1 . "号\n卧底2是：" . $undercoverid2 . "号\n白板号是" . $whiteboardid . "号\n游戏人数为：" . $allcount . "\n回复[换]，换一组词，\n(一局结束后，不必重建房，回复[换]直接换词)\n如果需要自定义词语，请输入”改“＋卧底词和平民词,如：改状元，冠军";
        } else {
            $userid = $username;
            $allcount = $key;
            $undercoverid1 = rand(1, $key);
            $undercoverid2 = randexcp1($undercoverid1, $allcount);
            $whiteboardid = randexcp2($undercoverid1, $undercoverid2, $allcount);
            $words = getWords();
            $word1 = $words['word1'];
            $word2 = $words['word2'];
            $sql = "insert into uc_rooms(userid,allcount,nowcount,undercoverid1,undercoverid2,whiteboardid,word1,word2) values('$username', '$allcount', '0','$undercoverid1' ,'$undercoverid2' ,'$whiteboardid' ,'$word1' ,'$word2')";
            execSql($sql);
            $datas = getDatas($userid);
            $roomid = $datas['roomid'];
            $text = "您是法官\n游戏房间号为" . $roomid . "\n平民词：" . $word1 . "\n卧底词：" . $word2 . "\n卧底1是：" . $undercoverid1 . "号\n卧底2是：" . $undercoverid2 . "号\n白板号是" . $whiteboardid . "号\n游戏人数为：" . $allcount . "\n回复[换]，换一组词，\n(一局结束后，不必重建房，回复[换]直接换词)\n如果需要自定义词语，请输入”改“＋卧底词和平民词,如：改状元，冠军";
        }
    } elseif ($key >= 1000 && $key <= 9999) {
        $sql = "select * from uc_rooms where roomid = '$key'";
        $data = $mysql->query($sql)->fetch();
        if ($data) {
            if ($data['nowcount'] < $data['allcount']) {
                $nowcount = (int) $data['nowcount'];
                $nowcount ++;
                $sql = "update uc_rooms set nowcount = '$nowcount'  where roomid = '$key'";
                execSql($sql);
                if ($nowcount == (int) $data['undercoverid1']) {
                    $text = "您是" . $nowcount . "号，您的词语是" . $data['word2'];
                } elseif ($nowcount == (int) $data['undercoverid2']) {
                    $text = "您是" . $nowcount . "号，您的词语是" . $data['word2'];
                } elseif ($nowcount == (int) $data['whiteboardid']) {
                    $text = "您是" . $nowcount . "号，您是白板";
                } else {
                    $text = "您是" . $nowcount . "号，您的词语是" . $data['word1'];
                }
            } else {
                $text = "房间人数已满";
            }
        } else {
            $text = "您输入的房间号无效";
        }
    } elseif ($key == "换") {
        $datas = getDatas($userid);
        if ($datas == false) {
            return "输入无效,请确认您已经创建房间";
        }
        $allcount = (int) $datas['allcount'];
        $undercoverid1 = rand(1, $allcount);
        $words = getWords();
        $word1 = $words['word1'];
        $word2 = $words['word2'];
        $roomid = $datas['roomid'];
        if ($allcount < 7) {
            $sql = "update uc_rooms set nowcount = '0', undercoverid1= '$undercoverid1',word1= '$word1',word2 = '$word2' where userid = '$username'";
            execSql($sql);
            $text = "换词成功\n游戏房间号为" . $roomid . "\n平民词：" . $word1 . "\n卧底词：" . $word2 . "\n卧底是：" . $undercoverid1 . "号,游戏人数为：" . $allcount . "\n请参与人员重新发送房间号";
        } elseif ($allcount < 11) {
            $undercoverid2 = randexcp1($undercoverid1, $allcount);
            $sql = "update uc_rooms set nowcount = '0', undercoverid1= '$undercoverid1',undercoverid2= '$undercoverid2',word1= '$word1',word2 = '$word2' where userid = '$username'";
            execSql($sql);
            $text = "换词成功\n游戏房间号为" . $roomid . "\n平民词：" . $word1 . "\n卧底词：" . $word2 . "\n卧底1是" . $undercoverid1 . "号,卧底2是" . $undercoverid2 . "号,游戏人数为：" . $allcount . "\n请参与人员重新发送房间号";
        } elseif ($allcount < 14) {
            $undercoverid2 = randexcp1($undercoverid1, $allcount);
            $whiteboardid = randexcp2($undercoverid1, $undercoverid2, $allcount);
            $sql = "update uc_rooms set nowcount = '0', undercoverid1= '$undercoverid1',undercoverid2= '$undercoverid2',whiteboardid='$whiteboardid',word1= '$word1',word2= '$word2' where userid = '$username'";
            execSql($sql);
            $text = "换词成功\n游戏房间号为" . $roomid . "\n平民词：" . $word1 . "\n卧底词：" . $word2 . "\n卧底1是" . $undercoverid1 . "号,卧底2是" . $undercoverid2 . "号,白板号是" . $whiteboardid . "号,游戏人数为：" . $allcount . "\n请参与人员重新发送房间号";
        }
    } elseif (substr($key, 0, 3) == "改") {
        $key = substr($key, 3);
        $words = explode(",", $key);
        if (count($words) != 2) {
            $words = explode("，", $key);
        }
        if (count($words) != 2) {
            $text = "请按照正确格式输入卧底词和平民词,如：改状元，冠军";
            return $text;
        }
        $word1 = $words[0];
        $word2 = $words[1];
        $datas = getDatas($userid);
        if ($datas == false) {
            return "输入无效,请确认您已经创建房间";
        }
        $allcount = (int) $datas['allcount'];
        $roomid = $datas['roomid'];
        $undercoverid1 = rand(1, $allcount);
        if ($allcount < 7) {
            $sql = "update uc_rooms set nowcount = '0', undercoverid1= '$undercoverid1',word1= '$word1',word2 = '$word2' where userid = '$username'";
            execSql($sql);
            $text = "改词成功\n游戏房间号为" . $roomid . "\n平民词：" . $word1 . "\n卧底词：" . $word2 . "\n卧底是：" . $undercoverid1 . "号,游戏人数为：" . $allcount . "\n请参与人员重新发送房间号";
        } elseif ($allcount < 11) {
            $undercoverid2 = randexcp1($undercoverid1, $allcount);
            $sql = "update uc_rooms set nowcount = '0', undercoverid1= '$undercoverid1',undercoverid2= '$undercoverid2',word1= '$word1',word2 = '$word2' where userid = '$username'";
            execSql($sql);
            $text = "改词成功\n游戏房间号为" . $roomid . "\n平民词：" . $word1 . "\n卧底词：" . $word2 . "\n卧底1是" . $undercoverid1 . "号,卧底2是" . $undercoverid2 . "号,游戏人数为：" . $allcount . "\n请参与人员重新发送房间号";
        } elseif ($allcount < 14) {
            $undercoverid2 = randexcp1($undercoverid1, $allcount);
            $whiteboardid = randexcp2($undercoverid1, $undercoverid2, $allcount);
            $sql = "update uc_rooms set nowcount = '0', undercoverid1= '$undercoverid1',undercoverid2= '$undercoverid2',whiteboardid='$whiteboardid',word1= '$word1',word2 = '$word2' where userid = '$username'";
            execSql($sql);
            $text = "改词成功\n游戏房间号为" . $roomid . "\n平民词：" . $word1 . "\n卧底词：" . $word2 . "\n卧底1是" . $undercoverid1 . "号,卧底2是" . $undercoverid2 . "号,白板号是" . $whiteboardid . "号,游戏人数为：" . $allcount . "\n请参与人员重新发送房间号";
        }
    } elseif ($key == "规则") {
        $text = "4-6人游戏1卧底\n7-10人游戏2卧底\n11-13人游戏2卧底1白板\n1.每人每轮用一句话描述自己拿到的词语，既不能让卧底察觉，也要给同伴以暗示\n" . "2.每轮描述完毕，所有在场的人投票选出怀疑谁是卧底，得票最多的人出局。若没有人的得票超过半数（50%），则没有人出局。若卧底出局，则游戏结束。若卧底未出局，游戏继续\n" . "3.反复多个流程,若卧底撑到最后一轮（场上剩3人时），则卧底获胜，反之，则大部队胜利";
    } elseif ($key == "惩罚") {
        $text = getPunish();
    } else {
        $text = '好吧，你说的我听不懂了';
    }
    return $text;
}

function execSql($sql)
{
    try {
        $mysql = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        $mysql->exec('SET CHARACTER SET ' . DB_CHARSET);
        $mysql->exec('SET NAMES ' . DB_CHARSET);
        $mysql->exec($sql);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    return true;
}

function getWords()
{
    try {
        $mysql = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        $mysql->exec('SET CHARACTER SET ' . DB_CHARSET);
        $mysql->exec('SET NAMES ' . DB_CHARSET);
        $sql = "select count(*) from uc_words";
        $all = $mysql->query($sql)->fetchColumn(0);
        $id = rand(1, $all);
        $sql = "select * from uc_words where id = $id";
        $words = $mysql->query($sql)->fetch();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    return $words;
}

function getDatas($userid)
{
    try {
        $mysql = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        $mysql->exec('SET CHARACTER SET ' . DB_CHARSET);
        $mysql->exec('SET NAMES ' . DB_CHARSET);
        
        $sql = "select * from uc_rooms where userid = '$userid'";
        $datas = $mysql->query($sql)->fetch();
    } catch (PDOException $e) {
        $datas = false;
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    return $datas;
}

function randexcp1($excp, $key)
{
    $randresult = rand(1, $key);
    if ($randresult == $excp) {
        return randexcp1($excp, $key);
    } else {
        return $randresult;
    }
}

function randexcp2($excp1, $excp2, $key)
{
    $randresult = rand(1, $key);
    if ($randresult == $excp1 || $randresult == $excp2) {
        return randexcp2($excp1, $excp2, $key);
    } else {
        return $randresult;
    }
}

function getPunish()
{
    $content = "请输的同学摇骰子选择:\n\n";
    try {
        $mysql = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        $mysql->exec('SET CHARACTER SET ' . DB_CHARSET);
        $mysql->exec('SET NAMES ' . DB_CHARSET);
        $sql = "select count(*) from uc_punish";
        $maxid = $mysql->query($sql)->fetchColumn(0);
        $a = array();
        $i = 1;
        while ($i <= 6) {
            $id = fmod(rand(1, 100000), $maxid) + 1;
            if (array_search($id, $a) == false) {
                $a[] = $id;
                $sql = "select item from uc_punish where id = $id";
                $item = $mysql->query($sql)->fetchColumn(0);
                $content .= $i . ". " . $item . "\n\n";
                $i = $i + 1;
            }
        }
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    return $content;
}
?>
