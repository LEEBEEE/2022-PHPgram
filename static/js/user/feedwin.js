if(feedObj) {
    const url = new URL(location.href);
    feedObj.iuser = parseInt(url.searchParams.get('iuser'));
    feedObj.getFeedUrl = '/user/feed';
    feedObj.getFeedList();
}

/*
function getFeedList() {
    if(!feedObj) { return; }
    feedObj.showLoading();            
    const param = {
        page: feedObj.currentPage++,
        iuser: parseInt(url.searchParams.get('iuser'))
    }
    fetch('/user/feed' + encodeQueryString(param))
    .then(res => res.json())
    .then(list => {                
        feedObj.makeFeedList(list);                
    })
    .catch(e => {
        console.error(e);
        feedObj.hideLoading();
    });
}
getFeedList();
*/

(function() {
    const lData = document.querySelector('#lData');
    const btnFollow = document.querySelector('#btnFollow');
    const follower = document.querySelector('#follower');
    let followerCnt = parseInt(follower.innerText);

    // follow 처리

    if(btnFollow) {
        btnFollow.addEventListener('click', function() {
            const param = {
                toiuser: parseInt(lData.dataset.toiuser)
            };
            const follow = btnFollow.dataset.follow;
            const followUrl = '/user/follow';

            switch(follow) {
                case '1':
                    fetch(followUrl + encodeQueryString(param), {method:'DELETE'})
                    .then(res => res.json())
                    .then(res => {
                        if(res.result) {
                            btnFollow.dataset.follow = '0';
                            btnFollow.classList.remove('btn-outline-secondary');
                            btnFollow.classList.add('btn-primary');
                            follower.innerText = followerCnt - 1;
                            // followerCnt -= 1;
                            // follower.innerText = followerCnt;
                            if(btnFollow.dataset.youme === '1') {
                                btnFollow.innerText = '맞팔로우 하기';
                            } else {
                                btnFollow.innerText = '팔로우';
                            }
                        }
                    });
                    break;
                case '0':
                    fetch(followUrl, {
                        method: 'POST',
                        body: JSON.stringify(param)
                    })
                    .then(res => res.json())
                    .then(res => {
                        if(res.result) {
                            follower.innerText = followerCnt + 1;
                            btnFollow.dataset.follow = '1';
                            btnFollow.classList.remove('btn-primary');
                            btnFollow.classList.add('btn-outline-secondary');
                            btnFollow.innerText = '팔로우 취소';
                        }
                    })
                    break;
            }
        })
    }

    // profile image change

    const btnProfileModalClose = document.querySelector('#modal-close');
    const gData = document.querySelector('#gData');
    let mainimg = gData.dataset.mainimg;

     const divDel = document.createElement('div');
    divDel.className = "text-danger _modal-item bt-1";
    divDel.innerHTML = "<span id='btnDelCurrentProfilePic'>현재 사진 삭제</span>";

    document.querySelector('#changeImg').after(divDel);
    
    if(mainimg === "") {
        divDel.classList.add('d-none');
    }


    const btnDelCurrentProfilePic = document.querySelector('#btnDelCurrentProfilePic');
    if(btnDelCurrentProfilePic) {
        btnDelCurrentProfilePic.addEventListener('click', e => {
            fetch('/user/profile', { method: 'DELETE' })
            .then(res => res.json())
            .then(res => {
                if(res.result) {
                    
                    const lastCmt = document.querySelectorAll('.lastCmt');
                    if(lastCmt){
                        const gUser = gData.dataset.loginiuser;
                        const profilePic = document.querySelector('#profilePic')
                        const cmtUser = profilePic.dataset.iuser;
                    
                        if(gUser === cmtUser) {
                            const cmtProfileList = profilePic.querySelectorAll('img');
                            cmtProfileList.forEach(item => item.classList.add('profileimg'));
                        }
                    }

                    const profileImgList = document.querySelectorAll('.profileimg');
                    profileImgList.forEach(item => {
                        item.src = '/static/img/profile/bp.png';
                    });
                }
                btnProfileModalClose.click();
                divDel.classList.add('d-none');
            })
        });
    }

    const changeImg = document.querySelector('#changeImg');
    if(changeImg) {
        const modal = document.querySelector('#changeProfileImg');
        const frmElem = modal.querySelector('form');
        const btnClose = modal.querySelector('.btn-close');
        const imgElem = modal.querySelector('#currentProfileImg');

        frmElem.imgs.addEventListener('change', function(e) {
    
            if(e.target.files.length > 0) {
    
                const imgSource = e.target.files[0];

                const reader = new FileReader();
                reader.readAsDataURL(imgSource);
                reader.onload = function() {
                    imgElem.src = reader.result;
                };

    
                const changeBtn = modal.querySelector('#changeBtn');
                changeBtn.addEventListener('click', function() {
                    const file = frmElem.imgs.files[0];
    
                    const fData = new FormData();
                    fData.append('imgs[]', file);
                        
                    fetch('/user/profile', {
                        method: 'post',
                        body: fData
                    })
                    .then(res => res.json())
                    .then(res => {
                        // console.log(res);
                        if(res.src) {                                
                            btnClose.click();

                            const lastCmt = document.querySelectorAll('.lastCmt');
                            if(lastCmt){
                                const gUser = gData.dataset.loginiuser;
                                const profilePic = document.querySelector('#profilePic')
                                const cmtUser = profilePic.dataset.iuser;
                            
                                if(gUser === cmtUser) {
                                    const cmtProfileList = profilePic.querySelectorAll('img');
                                    cmtProfileList.forEach(item => item.classList.add('profileimg'));
                                }
                            }

                            const profileImgList = document.querySelectorAll('.profileimg');
                            profileImgList.forEach(item => {
                                item.src = `/static/img/profile/${lData.dataset.toiuser}/${res.src}`;
                            });
                            
                           divDel.classList.remove('d-none');
                        }
                    });
                });
            }
        });
        
        // btnClose.addEventListener('click', function() {
        //     imgElem.src = `/static/img/profile/${gData.dataset.loginiuser}/${gData.dataset.mainimg}`;
        // })

        imgElem.addEventListener('click', function() {
            frmElem.imgs.click();
        });
    }
})();