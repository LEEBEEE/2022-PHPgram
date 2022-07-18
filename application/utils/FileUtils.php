<?php

    function getExt($fileName) {
        // return end(explode(".", $fileName)); // end(변수만)
        // return mb_substr($fileName, mb_strrpos($fileName, "."));
        return pathinfo($fileName, PATHINFO_EXTENSION);
    }
    
    function gen_uuid_v4() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x'
        , mt_rand(0, 0xffff)
        , mt_rand(0, 0xffff)
        , mt_rand(0, 0xffff)
        , mt_rand(0, 0xffff) | 0x4000
        , mt_rand(0, 0xffff) | 0x8000
        , mt_rand(0, 0xffff)
        , mt_rand(0, 0xffff)
        , mt_rand(0, 0xffff)
    );
}

function getRndFileNm($fileName) {
    return gen_uuid_v4() .".". getExt($fileName);
}