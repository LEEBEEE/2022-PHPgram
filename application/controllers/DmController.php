<?php
namespace application\controllers;
use application\libs\Application;

class DmController extends Controller {
    public function index() {        
        $this->addAttribute(_JS, ["dm/index"]);
        $this->addAttribute(_CSS, ["dm/index"]);
        $this->addAttribute(_MAIN, $this->getView("dm/index.php"));
        return "template/t1.php";
    }

    public function dmlist() {
        switch(getMethod()) {
            case _GET:
                $param = [ "iuser" => getIuser() ];
                $dmList = $this->model->selDmList($param);
                foreach($dmList as $item) {
                    $param["idm"] = $item->idm;
                    $item->opponent = $this->model->selDmOpponent($param);
                }
                return $dmList;
        }
    }

    public function reg() {
        switch(getMethod()) {
            case _POST: //dm 생성
                $json = getJson();     
                $idm = $this->model->insDm();

                $param = [ "idm" => $idm, "iuser" => $json["toiuser"] ];                
                $this->model->insDmuser($param);
                
                $param["iuser"] = getIuser();
                $this->model->insDmuser($param);
                
                return [ "idm" => $idm, "opponent" => $this->model->selDmOpponent($param) ];
        }
    }

    public function msglist() {
        switch(getMethod()) {
            case _GET:
                $page = intval($_GET["page"]);
                $limit = intval($_GET["limit"]);
                $startIdx = ($page - 1) * $limit;
                $param = [
                    "idm" => $_GET["idm"],
                    "startIdx" => $startIdx,
                    "limit" => $limit            
                ];
                $list = $this->model->selDmMsgList($param); 
                return $list ? $list : [];
        }
    }
}