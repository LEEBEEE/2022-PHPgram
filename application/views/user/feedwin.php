<div id="lData" data-toiuser="<?=$this->data->iuser?>"></div>
<div class="d-flex flex-column align-items-center">
    <div class="size_box_100"></div>
    <div class="w100p_mw614">
        <div class="d-flex flex-row">            
            <div class="d-flex flex-column justify-content-center me-3">
                <div class="circleimg h150 w150 <?=$this->data->iuser === getIuser() ? 'pointer' : ''?>" data-bs-toggle="modal" data-bs-target="#profileEdit" id="profileEditBtn">
                    <img class="profileimg" src='/static/img/profile/<?=$this->data->iuser?>/<?=$this->data->mainimg?>' onerror='this.error=null;this.src="/static/img/profile/bp.png"'>
                </div>
            </div>
            <div class="flex-grow-1 d-flex flex-column justify-content-evenly">
                <div><?=explode("@", $this->data->email)[0]?>
                <!-- 버튼~ -->
                <?php
                        if($this->data->iuser === getIuser()) {
                            echo '<button type="button" id="" data-bs-target="#btnModProfile" data-bs-toggle="modal" class="btn btn-outline-secondary">프로필 수정</button>';
                        } else {                            
                            $data_follow = 0;
                            $cls = "btn-primary";
                            $txt = "팔로우";

                            if($this->data->meyou === 1) {
                                $data_follow = 1;
                                $cls = "btn-outline-secondary";
                                $txt = "팔로우 취소";
                            } else if($this->data->youme === 1 && $this->data->meyou === 0) {
                                $txt = "맞팔로우 하기";
                            }
                            echo "<button type='button' id='btnFollow' data-youme='{$this->data->youme}' data-follow='{$data_follow}' class='btn {$cls}'>{$txt}</button>";
                        }
                    ?>
                </div>

                <div class="d-flex flex-row">
                    <!-- innerText로 빼다 쓰면 dataset에 안 박아도 됨 -->
                    <div class="flex-grow-1">게시물<span id="feedCnt" data-feedcnt="<?=$this->data->feedCnt?>"><?=$this->data->feedCnt?></span></div>
                    <div class="flex-grow-1"> 팔로워<span id="follower" data-follower="<?=$this->data->follower?>"><?=$this->data->follower?></span></div>
                    <div class="flex-grow-1"> 팔로우<span><?=$this->data->follow?></span></div>
                </div>
                <div class="bold"><?=$this->data->nm?></div>
                <div><?=$this->data->cmt?></div>
            </div>
        </div>
        <div id="item_container"></div>
    </div>
    <div class="loading d-none"><img src="/static/img/loading.gif"></div>
</div>


<!-- modal -->
<?php if($this->data->iuser === getIuser()) { ?>
<div class="modal fade" id="profileEdit" tabindex="-1" aria-labelledby="profileEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content text-center" id="profileEditContent">
            <div class="p-4 modal-header justify-content-center">
                <h5 class="modal-title text-center bold">프로필 사진 바꾸기</h5>
            </div>
            <div class="p-0 modal-body" id="id-modal-body">
            <div class="text-primary _modal-item" data-bs-target="#changeProfileImg" data-bs-toggle="modal" id="changeImg"><span>사진 업로드</span></div>
            <!-- <div class="text-danger _modal-item bt-1" id="delPP"><span id="btnDelCurrentProfilePic">현재 사진 삭제</span></div> -->
            </div>
            <div class="modal-footer justify-content-center pointer text-mute" data-bs-dismiss="modal" id="modal-close" aria-label="Close">
                <span>취소</span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changeProfileImg" aria-hidden="true" aria-labelledby="changeProfileImg" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title bold" id="changeProfileImgLabel">사진 업로드</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="circleimg h300 w300 pointer">
                    <img id="currentProfileImg" class="profileimg" 
                        src='/static/img/profile/<?=$this->data->iuser?>/<?=$this->data->mainimg?>' 
                        onerror='this.error=null;this.src="/static/img/profile/bp.png"'>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="changeBtn">변경하기</button>
            </div>
        </div>
        
        <form class="d-none">
            <input type="file" accept="image/*" name="imgs">
        </form>
    </div>
</div>




    <!-- 뻘짓 -->
    <div class="modal fade" id="btnModProfile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="profileModalContent">

            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">프로필</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
            </div>
    <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="col-form-label">이름</label>
                    <input type="text" class="form-control" id="name" name="nm" value="<?= $this->data->nm ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="col-form-label">아이디</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?= $this->data->email ?>">
                </div>
                <div class="mb-3">
                    <label for="intro" class="col-form-label">소개</label>
                    <textarea class="form-control" id="intro" name="intro"><?= $this->data->cmt ?></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>        
    </div> 
<?php } ?>

