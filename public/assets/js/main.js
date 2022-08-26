(function() {

    function closeTool(e) {
        var $el = e.target.closest('._active');
        var actives = [];
        var i;

        if($el) {
            $el.classList.remove('_active');
            for(i = 0; i < $el.classList.length; i++) {
                if($el.classList[i].endsWith('_active')) {
                    $el.classList.remove($el.classList[i]);
                }
            }
        }
    }

    function clickTool(e) {
        var $article = e.target.closest('article');
        var cls = _ao.action(e) + '_active';

        // If the tool is already open, just close it.
        if($article.classList.contains(cls)) {
            // Eventually use chainable methods
            //_ao.visible('.edit button', $article).click();
            _ao.click(_ao.visible('.edit button', $article));
        } else {
            // Otherwise close the other tools then open this tool.
            _ao.click(_ao.visible('.edit button', $article));

            $article.classList.add(cls);
            $article.classList.add('_active');
        }
    }

    // Need to reset the input contents to the original contents.
    function resetTool(e) {
        console.log('resetTool');
    }

    function submitEdit(e) {
        console.log(e);
        console.log('submitEdit');
    }

    function init() {
        ao.listen('click', 'article .tools button', clickTool);
        ao.listen('click', 'article .edit ._cancel', closeTool, resetTool);
        
        ao.listen('success', 'article .edit', _ao._toggleSuffixClosest('_active'), _ao._closest('._part'));
    }

    init();

})();
