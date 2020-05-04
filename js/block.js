(function () {

    let Block = function (data) {
        this.init();
    };

    Block.prototype = {
        Block: null,

        init: function () {

        },
    }

    if (!window.Block) window.Block = Block;
})();

