<?php
function setCurrentGame($userid, $currentgame, $status)
{
    try {
        $mysql = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        $mysql->exec('SET CHARACTER SET ' . DB_CHARSET);
        $mysql->exec('SET NAMES ' . DB_CHARSET);
        $data = getCurrentGame($userid);
        if ($data) {
            $sql = "update user_games set userid= '$userid',currentgame= '$currentgame',status= '$status' where userid = '$userid'";
        } else {
            $sql = "insert into user_games(userid,currentgame,status) values('$userid', '$currentgame' , '$status')";
        }
        $mysql->exec($sql);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    return true;
}

function getCurrentGame($userid)
{
    try {
        $mysql = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        $mysql->exec('SET CHARACTER SET ' . DB_CHARSET);
        $mysql->exec('SET NAMES ' . DB_CHARSET);
        
        $sql = "select * from user_games where userid = '$userid'";
        $datas = $mysql->query($sql)->fetch();
    } catch (PDOException $e) {
        $datas = false;
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    return $datas;
}
?>
