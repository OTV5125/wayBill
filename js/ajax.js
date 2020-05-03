(function () {

    var Ajax = function () {

    };

    Ajax.prototype = {


        init: function () {

        },


        postAjax: function (url, data, callback) {
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                dataType: 'html',
                success: function (response) {
                    callback(response);
                }
            });
        },
    }

    if (!window.Ajax) window.Ajax = Ajax;
})();



