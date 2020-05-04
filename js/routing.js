(function () {

    let Routing = function (classNode, balance, routes) {
        this.init(classNode);
        this.block = new Block(classNode, balance, routes);
    };

    Routing.prototype = {
        Routing: null,

        init: function (classNode) {
            $('.save').on('click', this.buttonSave.bind(this, classNode));
            // $('.add-route').off('click', this.buttonAddRoute);
            $('.block-1 .add-route').on('click', this.buttonAddRoute.bind(this, classNode));
            $('.block-2 .add-route').on('click', this.buttonAddRoute2.bind(this, '.block-2'));
        },

        buttonSave: function (classNode) {
            console.log(this.block.getBalance())
            let block =  $(classNode);
            this.block = new Block('.block-2', this.block.getBalance());
            block.css('pointer-events', 'none');
            block.find('.add-route').remove();
            block.find('.save').remove();
            block.find('.btnGetDoc1').remove();
            block.find('.petrol-list').css("background-color", "rgba(97, 56, 224, 0.5)");
            block.find('.select-routes').css("background-color", "rgba(97, 56, 224, 0.5)");

        },

        buttonAddRoute: function (classNode) {
            let block =  $(classNode).find('.checkbox-list-items').first();
            block.clone().appendTo(classNode+' .checkbox-list');
            this.block.selectRoute();
        },

        buttonAddRoute2: function (classNode) {
            let block =  $(classNode).find('.checkbox-list-items').first();
            block.clone().appendTo(classNode+' .checkbox-list');
            this.block.selectRoute();
        }


    }

    if (!window.Routing) window.Routing = Routing;
})();

