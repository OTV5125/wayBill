(function () {

    let Routing = function (classNode, balance, routes) {
        this.ajax = new Ajax();
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
            $('.block-1 .get-doc').on('click', this.buttonGetDoc.bind(this, '.block-1'));
            $('.block-2 .get-doc').on('click', this.buttonGetDoc2.bind(this, '.block-2'));
        },

        buttonSave: function (classNode) {
            let block =  $(classNode);
            this.block2 = new Block('.block-2', this.block.getBalance());
            block.css('pointer-events', 'none');
            block.find('.add-route').remove();
            block.find('.save').remove();
            block.find('.btnGetDoc1').remove();
            block.find('.petrol-list').css("background-color", "rgba(97, 56, 224, 0.5)");
            block.find('.select-routes').css("background-color", "rgba(97, 56, 224, 0.5)");
            $('.block-2').fadeIn(500);

        },

        buttonGetDoc: function(){
            let block1 = this.block.getData(true);
            this.ajax.postAjax('getDoc.php', {block1: block1}, function (result) {
                window.open('doc.xlsx');
                console.log(result)
                console.log(JSON.parse(result));
            })
        },

        buttonGetDoc2: function(){
            let block1 = this.block.getData(true);
            let block2 = this.block2.getData(false);
            this.ajax.postAjax('getDoc.php', {block1: block1, block2: block2}, function (result) {
                // window.open('doc.xlsx');
                console.log(result)
                console.log(JSON.parse(result));
            })
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

