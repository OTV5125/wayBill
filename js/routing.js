(function () {

    let Routing = function (classNode, balance, routes) {
        this.init(classNode);
        new Block(classNode, balance, routes);
    };

    Routing.prototype = {
        Routing: null,

        init: function (classNode) {
            $('.save').on('click', this.buttonSave.bind(this, classNode));
        },

        buttonSave: function (classNode) {
            $(classNode).css('pointer-events', 'none');
            $(classNode).children('.add-route').remove();
            let data = {};

        }

    }

    if (!window.Routing) window.Routing = Routing;
})();

