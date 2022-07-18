<?php
namespace application\controllers;
use application\libs\Application;

class UserController extends Controller {
    public function signin() {
        switch(getMethod()) {
            case _GET:
                return "user/signin.php";
            case _POST:
                $email = $_POST['email'];
                $pw = $_POST['pw'];
                $param = [
                    "email" => $email
                ];
                $dbUser = $this->model->selUser($param);
                if(!$dbUser || !password_verify($pw, $dbUser->pw)) {
                    return "redirect:signin?email={$email}&err";
                }
                $dbUser->pw = null;
                $dbUser->regdt = null;
                // 메모리 차지하지 않게
                $this->flash(_LOGINUSER, $dbUser);
                return "redirect:/feed/index";
        }
    }

    public function signup() {
        // if(getMethod() === _GET) {
        //     return "user/signup.php";
        // } else if(getMethod() === _POST) {
        //     return "redirect:signin";
        // }

        switch(getMethod()) {
            case _GET:
                return "user/signup.php";
            case _POST:
                $param = [
                    "email" => $_POST['email'],
                    "pw" => $_POST['pw'],
                    "nm" => $_POST['nm']
                ];
                $param["pw"] = password_hash($param["pw"], PASSWORD_BCRYPT);
                $this->model->insUser($param);
                return "redirect:signin";
        }
    }

    public function logout() {
        $this->flash(_LOGINUSER);
        return "redirect:/user/signin";
    }

    public function feedwin() {
        $iuser = isset($_GET["iuser"]) ? intval($_GET["iuser"]) : 0;
        $param = [ "feeduser" => $iuser, "loginuser" => getIuser() ];
        $this->addAttribute(_DATA, $this->model->selUserProfile($param));
        $this->addAttribute(_JS, ["user/feedwin", "cursor", "https://unpkg.com/swiper@8/swiper-bundle.min.js"]);
        $this->addAttribute(_CSS, ["feed/index", "user/feedwin", "https://unpkg.com/swiper@8/swiper-bundle.min.css"]);
        $this->addAttribute(_MAIN, $this->getView("user/feedwin.php"));
        return "template/t1.php";
    }

    public function feed() {
        if(getMethod() === _GET) {
            $iuser = isset($_GET["iuser"]) ? intval($_GET["iuser"]) : 0;
            $page = 1;
            if(isset($_GET["page"])) {
                $page = intval($_GET["page"]);
            }
            $startIdx = ($page - 1) * _FEED_ITEM_CNT;
            $param = [
                "startIdx" => $startIdx,
                "feeduser" => $iuser,
                "loginuser" => getIuser()
            ];
            $list = $this->model->selFeedList($param);
            
            foreach($list as $item) {
                $param2 = ["ifeed" => $item->ifeed ];
                $item->imgList = Application::getModel("feed")->selFeedImgList($param2);
                $item->cmt = Application::getModel("feedcmt")->selFeedCmt($param2);

            }
            return $list;
        }
    }

    public function follow() {
        
        $param = [
            "fromiuser" => getIuser()
        ];

        switch(getMethod()) {
            case _POST:
                $json = getJson();
                $param["toiuser"] = $json["toiuser"];               
                return [_RESULT => $this->model->insFollow($param)];
            case _DELETE:
                $param["toiuser"] = $_GET["toiuser"];               
                return [_RESULT => $this->model->delFollow($param)];
        }
    }

    public function profile() {
        switch(getMethod()) {
            case _DELETE:
                $loginUser = getLoginUser();
                if($loginUser) {
                    $path = "static/img/profile/{$loginUser->iuser}/{$loginUser->mainimg}";
                    if(file_exists($path) && unlink($path)) {
                        $param = ["iuser" => $loginUser->iuser, "delMainImg" => 1];
                        if($this->model->updUser($param)) {
                            $loginUser->mainimg = null;
                            return [_RESULT => 1]; 
                        }
                    }
                }
                return [_RESULT => 0];
            case _POST:
                if(!is_array($_FILES) || !isset($_FILES["imgs"])){
                    return [_RESULT => 0];
                }

                $loginUser = getLoginUser();
                if($loginUser) {
                    if($loginUser->mainimg){
                        $path = _IMG_PATH . "/profile/{$loginUser->iuser}/{$loginUser->mainimg}";
                        unlink($path);
                    }

                    $saveDir = _IMG_PATH . "/profile/" . $loginUser->iuser;
                    if(!is_dir($saveDir)) {
                        mkdir($saveDir, 0777, true);
                    }
                    $tempName = $_FILES['imgs']['tmp_name'][0];
                    $rndFileName = getRndFileNm($_FILES['imgs']['name'][0]);
                    move_uploaded_file($tempName, $saveDir."/".$rndFileName);
                    $param = [
                        "iuser" => $loginUser->iuser,
                        "mainimg" => $rndFileName
                    ];
                    if($this->model->updUser($param)) {
                        $loginUser->mainimg = $rndFileName;
                    }
                }
                return ["src" => $rndFileName]; 
        }
    }
}