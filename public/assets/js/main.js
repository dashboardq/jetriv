(function() {

    function clickBookmark(e) {
        console.log('clickBookmark');
        if(e.target.classList.contains('bookmark')) {
            document.querySelector('.bookmark_details').classList.add('show');
        }
    }

    function clickSaveBookmark(e) {
        console.log('clickSaveBookmark');
        if(e.target.matches('.bookmark_details input')) {
            document.querySelector('.bookmark_details').classList.remove('show');
        }
    }

    function init() {
        document.addEventListener('click', clickBookmark);
        document.addEventListener('click', clickSaveBookmark);
    }

    init();

})();
