<?php
namespace application\controllers;
use application\libs\Application;


class FeedController extends Controller {
    public function index() {
        $this->addAttribute(_JS, ["feed/index", "cursor", "https://unpkg.com/swiper@8/swiper-bundle.min.js"]);
        $this->addAttribute(_CSS, ["feed/index", "https://unpkg.com/swiper@8/swiper-bundle.min.css"]);
        $this->addAttribute(_MAIN, $this->getView("feed/index.php"));
        return "template/t1.php";
    }

    public function rest() {
        switch(getMethod()) {
            case _POST:
                if(!is_array($_FILES) || !isset($_FILES["imgs"])){
                    return [_RESULT => 0];
                }
                $location = $_POST['location'];
                $ctnt = $_POST['ctnt'];
                $iuser = getIuser();
                $param = [
                    "location" => $location,
                    "ctnt" => $ctnt,
                    "iuser" => $iuser
                ];
                $ifeed = $this->model->insFeed($param);
                foreach($_FILES["imgs"]["name"] as $key => $orgFileName) {
                    $saveDir = _IMG_PATH . "/feed/" . $ifeed;
                    if(!is_dir($saveDir)) {
                        mkdir($saveDir, 0777, true);
                    }
                    $tempName = $_FILES['imgs']['tmp_name'][$key];
                    $rndFileName = getRndFileNm($orgFileName);
                    move_uploaded_file($tempName, $saveDir."/".$rndFileName);
                    $param = [
                        "ifeed" => $ifeed,
                        "img" => $rndFileName
                    ];
                    $this->model->insFeedImgs($param);
                }
                $param2 = ["ifeed" => $ifeed];
                $data = $this->model->selFeedAfterReg($param2);
                $data->imgList = $this->model->selFeedImgList($param2);
                return $data; // 객체든 배열이든 리턴해주면 JSON형태로 응답

            case _GET:
                $page = 1;
                if(isset($_GET["page"])) {
                    $page = intval($_GET["page"]);
                }
                $startIdx = ($page - 1) * _FEED_ITEM_CNT;
                $param = [
                    "startIdx" => $startIdx,
                    "iuser" => getIuser()
                ];
                $list = $this->model->selFeedList($param);

                foreach($list as $item) {
                    $param2 = ["ifeed" => $item->ifeed];
                    $item->imgList = $this->model->selFeedImgList($param2);
                    $item->cmt = Application::getModel("feedcmt")->selFeedCmt($param2);
                }
                
                return $list;
        }
    }
    public function fav() {
        $urlPaths = isGetOne();
        if(!$urlPaths){
            exit();
        }
        $param = [
            "ifeed" => intval($urlPaths),
            "iuser" => getIuser()
        ];
        switch(getMethod()) {
            case _POST:
                return [_RESULT => $this->model->insFav($param)];
            case _DELETE:
                return [_RESULT => $this->model->delFav($param)];            
        }

        // $urlPaths = getUrlPaths();
        // if(!isset($urlPaths[2])){
        //     exit();
        // }
        
        // $param = [
        //     "ifeed" => intval($urlPaths[2]),
        //     "iuser" => getIuser()
        // ];
        // switch(getMethod()) {
        //     case _POST:            
        //         return [_RESULT => $this->model->insFav($param)];
        //     case _DELETE:
        //         return [_RESULT => $this->model->delFav($param)];            
        // }
    }
}