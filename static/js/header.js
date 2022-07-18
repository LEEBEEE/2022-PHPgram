(function() {    
    const dmIcon = document.querySelector('#dmIcon');
    const totalDmUnreadCntParent = document.querySelector('#totalDmUnreadCntParent');
    const totalDmUnreadCnt = document.querySelector('#totalDmUnreadCnt');
    dmIcon.addEventListener('click', e => {        
        totalDmUnreadCnt.innerText = 0;
        totalDmUnreadCntParent.classList.add("d-none");
        location.href = '/dm/index';
    });
})();