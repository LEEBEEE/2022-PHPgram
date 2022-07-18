<?php
namespace application\controllers;

class FeedCmtController extends Controller {
    public function index() {
        switch(getMethod()) {
            case _POST:
                $json = getJson();
                if(preg_replace('/\s+/', '', $json["cmt"])) {
                    $json["iuser"] = getIuser();
                    return [_RESULT => $this->model->insFeedCmt($json)];
                }
            case _GET:
                $ifeed = isset($_GET["ifeed"]) ? intval($_GET["ifeed"]) : 0;
                $param = ["ifeed" => $ifeed];
                return $this->model->selFeedCmtList($param);
        }
    }

}