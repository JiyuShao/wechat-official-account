<?php
/**
 * wechat php test
 */
include_once ("wx_variables.php");
include_once ("wx_turingAPI.php");
include_once ("wx_underCover.php");
include_once ("wx_checkUser.php");
include_once ("wx_billiards.php");
include_once ("wx_pixivAPI.php");

$wechatObj = new wechatCallbackapiTest();
if ($_GET["echostr"]) {
    $wechatObj->valid();
} else {
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{

    public function valid()
    {
        $echoStr = $_GET["echostr"];
        
        // valid signature , option
        if ($this->checkSignature()) {
            echo $echoStr;
            exit();
        }
    }

    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (! defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        
        $token = TOKEN;
        $tmpArr = array(
            $token,
            $timestamp,
            $nonce
        );
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
    
    // response message
    public function responseMsg()
    {
        // get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        
        // extract post data
        if (! empty($postStr)) {
            /*
             * libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
             * the best way is to check the validity of xml by yourself
             */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $type = trim($postObj->MsgType);
            $event = $postObj->Event;
            $eventKey = $postObj->EventKey;
            $keyword = trim($postObj->Content);
            $time = time();
            
            switch ($type) {
                case "text":
                    $resultStr = $this->receiveText($fromUsername, $toUsername, $time, $keyword);
                    break;
                case "event":
                    $resultStr = $this->receiveEvent($fromUsername, $toUsername, $time, $event, $eventKey);
                    break;
                default:
                    $resultStr = "";
                    break;
            }
            echo $resultStr;
        } else {
            echo "";
            exit();
        }
    }

    private function receiveEvent($fromUsername, $toUsername, $time, $event, $eventKey)
    {
        switch ($event) {
            case "subscribe":
                $resultStr = $this->formatTuringXML($fromUsername, $toUsername, $time, "关注本宝宝了么?");
                break;
            case "unsubscribe":
                break;
            case "CLICK":
                switch ($eventKey) {
                    case "谁是卧底":
                        setCurrentGame($fromUsername, "UnderCover", 1);
                        $underCouverResult = UnderCover($eventKey, $fromUsername);
                        $resultStr = $this->formatTuringXML($fromUsername, $toUsername, $time, $underCouverResult);
                        break;
                    case "多人台球":
                        setCurrentGame($fromUsername, "Billiards", 1);
                        $billiardsResult = Billiards($eventKey, $fromUsername);
                        $resultStr = $this->formatTuringXML($fromUsername, $toUsername, $time, $billiardsResult);
                        break;
                    case "PixivRank_daily":
                        $pixivAPI = new PixivAPI();
                        $pixivAPI->login(PIXIV_USERNAME, PIXIV_PASSWORD);
                        $pixivResult = json_decode($pixivAPI->getRanking('daily'), true);
                        $resultStr = $this->formatPixivXML($fromUsername, $toUsername, $time, $pixivResult['response'][0]['works']);
                        break;
                    case "PixivRank_daily_r18":
                        $pixivAPI = new PixivAPI();
                        $pixivAPI->login(PIXIV_USERNAME, PIXIV_PASSWORD);
                        $pixivResult = json_decode($pixivAPI->getRanking('daily_r18'), true);
                        $resultStr = $this->formatPixivXML($fromUsername, $toUsername, $time, $pixivResult['response'][0]['works']);
                        break;
                    default:
                        $resultStr = $this->formatTuringXML($fromUsername, $toUsername, $time, "么么哒(づ￣ 3￣)づ,快来聊吧");
                        break;
                }
                break;
            default:
                break;
        }
        
        return $resultStr;
    }

    private function receiveText($fromUsername, $toUsername, $time, $keyword)
    {
        // Reply based on word
        if ($keyword == "订阅") {
            $resultStr = $this->formatTuringXML($fromUsername, $toUsername, $time, "关注本宝宝了么?");
            
            // for UnderCover game
        } elseif ($keyword == "谁是卧底") {
            setCurrentGame($fromUsername, "UnderCover", 1);
            $underCouverResult = UnderCover($keyword, $fromUsername);
            $resultStr = $this->formatTuringXML($fromUsername, $toUsername, $time, $underCouverResult);
        } elseif (getCurrentGame($fromUsername)['status'] == 1 && getCurrentGame($fromUsername)['currentgame'] == "UnderCover" && ($keyword == "退出" || $keyword == "规则" || $keyword == "惩罚" || ($keyword >= 1 && $keyword <= 99) || ($keyword >= 1000 && $keyword <= 9999) || $keyword == "换" || substr($keyword, 0, 3) == "改")) {
            if ($keyword == "退出") {
                setCurrentGame($fromUsername, "UnderCover", 0);
            }
            $underCouverResult = UnderCover($keyword, $fromUsername);
            $resultStr = $this->formatTuringXML($fromUsername, $toUsername, $time, $underCouverResult);
            
            // for billiard game
        } elseif ($keyword == "多人台球") {
            setCurrentGame($fromUsername, "Billiards", 1);
            $billiardsResult = Billiards($keyword, $fromUsername);
            $resultStr = $this->formatTuringXML($fromUsername, $toUsername, $time, $billiardsResult);
        } elseif (getCurrentGame($fromUsername)['status'] == 1 && getCurrentGame($fromUsername)['currentgame'] == "Billiards" && ($keyword == "退出" || ($keyword >= 3 && $keyword <= 5) || ($keyword >= 1000 && $keyword <= 9999) || $keyword == "换")) {
            if ($keyword == "退出") {
                setCurrentGame($fromUsername, "Billiards", 0);
            }
            $billiardsResult = Billiards($keyword, $fromUsername);
            $resultStr = $this->formatTuringXML($fromUsername, $toUsername, $time, $billiardsResult);
        } else {
            $turingResult = tuling($keyword);
            $resultStr = $this->formatTuringXML($fromUsername, $toUsername, $time, $turingResult);
        }
        
        return $resultStr;
    }
    
    // format Turing result to wechat XML
    private function formatTuringXML($fromUsername, $toUsername, $time, $turingResult)
    {
        $length = count($turingResult);
        if ($length == 1) {
            $textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[text]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						</xml>";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $turingResult);
            return $resultStr;
        } else {
            $pictureTpl = "<xml>
    						<ToUserName><![CDATA[%s]]></ToUserName>
    						<FromUserName><![CDATA[%s]]></FromUserName>
    						<CreateTime>%s</CreateTime>
    						<MsgType><![CDATA[news]]></MsgType>
    						<ArticleCount>%s</ArticleCount>
    						<Articles>
    						%s
    						</Articles>
    						</xml> ";
            
            $itemXML = "";
            foreach ($turingResult as $key => $value) {
                $itemXML .= "<item>
                <Title><![CDATA[" . $value['Title'] . "]]></Title>
                <Description><![CDATA[" . $value['Description'] . "]]></Description>
                <PicUrl><![CDATA[" . $value['PicUrl'] . "]]></PicUrl>
                <Url><![CDATA[" . $value['Url'] . "]]></Url>
                </item>";
            }
            $resultStr = sprintf($pictureTpl, $fromUsername, $toUsername, $time, $length, $itemXML);
            return $resultStr;
        }
    }
    
    // format Pixiv result to wechat XML
    private function formatPixivXML($fromUsername, $toUsername, $time, $pixivResult)
    {
        $length = count($pixivResult);
        $pictureTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[news]]></MsgType>
					<ArticleCount>%s</ArticleCount>
					<Articles>
					%s
					</Articles>
					</xml> ";
        
        $itemXML = "";
        for ($i = 0; $i < count($pixivResult); $i ++) {
            $title = $pixivResult[$i]['work']['title'].'        [id='.$pixivResult[$i]['work']['id'].']';
            $description = 'Rank:' . $pixivResult[$i]['rank'] . ' Previous Rank:' . $pixivResult[$i]['previous_rank'] . ' Picture ID:' . $pixivResult[$i]['work']['id'];
            $pictureUrl = $pixivResult[$i]['work']['image_urls']['px_480mw'];
            $url = $pictureUrl;
            $itemXML .= "<item>
                <Title><![CDATA[" . $title . "]]></Title>
                <Description><![CDATA[" . $description . "]]></Description>
                <PicUrl><![CDATA[" . $pictureUrl . "]]></PicUrl>
                <Url><![CDATA[" . $url . "]]></Url>
                </item>";
        }
        $resultStr = sprintf($pictureTpl, $fromUsername, $toUsername, $time, $length, $itemXML);
        return $resultStr;
    }
}
?>
