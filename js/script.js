(function () {

    var Info = function () {
        this.ajax = new Ajax();
        this.init();
        this.sumKm = Number($('.block-1 .sumKm').text());
        this.inputOilVal = Number($('.block-1 .sumBalance').text());
        this.inputOilLastDate = $('.block-1 .lastOilDate').text();
        this.oldMileage = Number($('.block-1 .oldMileage').text());
        this.error = {newOil: 0, newDateList: 0, newKm:0}
    };

    Info.prototype = {
        info: null,
        classes: {
            block1: {
                sumBalance: ".block-1 .sumBalance",
                oldBalance: ".block-1 .oldBalance",
                inpOil: ".block-1 .input-oil",
                sumKm: ".block-1 .sumKm",
                inpOilDate: ".block-1 .input-oil-date",
                lastOilDate: ".block-1 .lastOilDate",
                newDate: ".block-1 .new-date",
                selectRoutes: ".block-1 .selectRoutes",
                btnGetDoc1: ".block-1 .btnGetDoc1",
                oldMileage: ".block-1 .oldMileage",
                newMileage: ".block-1 .newMileage",
            }
        },

        init: function () {
            this.inputOil();
            this.listBlock1();
            this.btnGetDoc1();
        },

        //BLOCK 1
        // selected: function () {
        //     $('.selectRoutes select').change(function(){
        //         let sum = 0;
        //         let select = $( ".selectRoutes select option:selected" );
        //         let km;
        //         for(let i = 0; select.length > i; i++){
        //             if(select[i].dataset.id > 0){
        //                 km = Number(select[i].dataset.km);
        //                 sum += km;
        //                 console.log(select[i].dataset.id);
        //                 console.log($(select[i]).parent().parent().find("input[type='checkbox']")[0].checked);
        //             }
        //
        //         }
        //         $('.sumKm').text(this.startKm - sum);
        //         console.log(sum)
        //     }.bind(this));
        // }

        inputOil: function () {
            $(this.classes.block1.inpOil).on('input keyup', function(e) {
                let newOil = Number($(e.target).val());
                if(newOil > 0){
                    $(this.classes.block1.sumBalance).text(newOil + this.inputOilVal);
                    $(this.classes.block1.sumKm).text(Math.round10(newOil/(11/100) + this.sumKm, 0));
                    this.newKm = Math.round10(newOil/(11/100) + this.sumKm, 0);
                }else{
                    $(this.classes.block1.sumBalance).text(this.inputOilVal);
                    $(this.classes.block1.sumKm).text(this.sumKm);
                }
            }.bind(this));
            $(this.classes.block1.inpOilDate).on('change', function(e){
                let oldDate = this.inputOilLastDate;
                oldDate = oldDate.split("-").reverse().join("-");
                let newDate = $(e.target).val();
                if(oldDate > newDate){
                    this.error.newOil = 1;
                    alert('Дата новой заправки меньше даты последней заправки');
                }else{
                    this.error.newOil = 0;
                    $(this.classes.block1.lastOilDate).text(newDate.split("-").reverse().join("-"))
                }
            }.bind(this));
        },

        listBlock1: function () {
            $(this.classes.block1.newDate).on('change', function(e){
                let oldDate = $(this.classes.block1.lastOilDate).text();
                oldDate = oldDate.split("-").reverse().join("-");
                if(oldDate > $(e.target).val()){
                    this.error.newDateList = 1;
                    alert('Дата путевого листа должна быть больше даты последней заправки');
                }else{
                    this.error.newDateList = 0;
                }
            }.bind(this));
            $('.block-1 .selectRoutes select').change(function(){
                let newKm;
                if(this.newKm === undefined){
                    newKm = this.sumKm;
                }else{
                    newKm = this.newKm;
                }
                let sum = 0;
                let select = $( ".block-1 .selectRoutes select option:selected" );
                let km;
                for(let i = 0; select.length > i; i++){
                    if(select[i].dataset.id > 0){
                        km = Number(select[i].dataset.km);
                        sum += km;
                            // console.log(select[i].dataset.id);
                            // console.log($(select[i]).parent().parent().find("input[type='checkbox']")[0].checked);
                    }
                }
                let sumKm = newKm - sum;
                this.normative = sum*0.11;
                $(this.classes.block1.newMileage).text(this.oldMileage + sum);
                $(this.classes.block1.sumKm).text(sumKm);
                if(sumKm < 0){
                    this.error.newKm = 1;
                    alert('Превышено число километров');
                }else{
                    this.error.newKm = 0;
                }
            }.bind(this));
        },

        btnGetDoc1: function () {
            $(this.classes.block1.btnGetDoc1).on('click', function () {
                if(this.error.newKm !== 0 || this.error.newDateList !== 0 || this.error.newOil !== 0){
                    alert('ошибка в заполнении формы');
                }else{
                    let arr = [];
                    let select = $( ".block-1 .selectRoutes select option:selected" );
                    for(let i = 0; select.length > i; i++){
                        if(select[i].dataset.id > 0){
                            arr.push([select[i].dataset.id, $(select[i]).parent().parent().find("input[type='checkbox']")[0].checked]);
                        }
                    }

                    var date = $(this.classes.block1.newDate).val()
                    date = date.split('-') // => массив ["a","b","c"]
                    let obj = {
                        BX4: 2,//number document
                        AD5: date[2], //day
                        AI5: date[1], //mount
                        AU5: date[0], //year
                        BU19: $(this.classes.block1.oldMileage).text(), //Показание спидометра старые
                        BT45: $(this.classes.block1.newMileage).text(), //Показание спидометра новые
                        BT34: $(this.classes.block1.inpOil).val(), //Выдано бензина
                        BT37: $(this.classes.block1.oldBalance).text(), //Осталось при выезде
                        BT38: Math.round10(Number($(this.classes.block1.sumKm).text())*0.11, -1), //Осталось при выезде
                        BT39: this.normative, //Осталось при выезде
                        BT40: this.normative, //Осталось при выезде
                    };
                    // console.log('Показание спидометра старые '+ obj.BU19);
                    // console.log('Показание спидометра новые '+ obj.BT45);
                    // console.log('Дата выдачи бензина '+ $(this.classes.block1.inpOilDate).val());
                    // console.log('Дата путевого листа '+ obj.date);
                    // console.log('Выдано бензина '+ obj.BT34);
                    // console.log('Осталось при выезде '+ obj.BT37);
                    // console.log('Осталось при возвращении '+ obj.BT38);
                    // console.log('Расход по норме '+ obj.BT39);


                    this.ajax.postAjax('getDoc.php', {data: obj, routes: arr, dateInputOil: $(this.classes.block1.inpOilDate).val()}, function (result) {
                        window.open('doc.xlsx');
                        console.log(result)
                    })

                }
            }.bind(this));
        }


        //BLOCK 1
    }

    if (!window.Info) window.Info = Info;
})();