(function () {

    let Block = function (classNode, balance, routes) {
        this.error = {newOil: 0, newDateList: 0, newKm:0}
        this.balance = balance;
        this.routes = routes;
        this.oldMileage = $(classNode+' .old-mileage');
        this.newMileage = $(classNode+' .new-mileage');
        this.startDayPetrol = $(classNode+' .start-day-petrol');
        this.finishDayPetrol = $(classNode+' .finish-day-petrol');
        this.lastDatePetrol = $(classNode+' .last-date-petrol');
        this.sumPetrol = $(classNode+' .sum-petrol');
        this.restKm = $(classNode+' .rest-km');
        this.numberList = $(classNode+' .number-list');
        this.inputPetrol = $(classNode+' .input-petrol');
        this.inputPetrolDate = $(classNode+' .input-petrol-date');
        this.newDateList = $(classNode+' .new-date-list');
        this.selectRoutes = $(classNode+' .select-routes');
        this.init();
    };

    Block.prototype = {
        Block: null,

        init: function () {
            this.addDataBlock();
            this.inputPetrolFunc();
            this.inputNewDateFunc();
            this.selectRoute();
        },

        addDataBlock: function () {
            this.oldMileage.val(this.balance.mileage);
            this.startDayPetrol.val(this.balance.balance);
            this.lastDatePetrol.text(this.balance.last_date);
            this.restKm.text(Math.round10(this.balance.balance/(11/100), -2));
            this.sumKm = Math.round10(this.balance.balance/(11/100), -2);
            this.numberList.val(this.balance.number_list);
        },

        inputPetrolFunc: function () {
            this.inputPetrol.on('input keyup', function(e) { //считает бензин и меняет отчет километров в запасе
                this.balance.inputPetrol = Number($(e.target).val());
                this.recountPetrol();
            }.bind(this));

            this.startDayPetrol.on('input keyup', function(e) {
                this.balance.balance = Number($(e.target).val());
                this.recountPetrol();
            }.bind(this));

            this.inputPetrolDate.on('change', function(e){ //сравнивает что бы дата заправки была не меньше последней даты заправки
                let oldDate = this.lastDatePetrol.text();
                oldDate = oldDate.split("-").reverse().join("-");
                let newDate = $(e.target).val();
                if(oldDate > newDate){
                    this.error.newOil = 1;
                    alert('Дата новой заправки меньше даты последней заправки');
                }else{
                    this.error.newOil = 0;
                    this.newDatePetrol = newDate.split("-").reverse().join("-");
                    this.lastDatePetrol.text(this.newDatePetrol);
                }
            }.bind(this));
        },

        recountPetrol: function f() {

            if(this.balance.inputPetrol > 0){
                this.sumPetrol.text(this.balance.balance + this.balance.inputPetrol);
                this.sumKm = Math.round10((this.balance.balance + this.balance.inputPetrol)/(11/100), -2);
                this.restKm.text(this.sumKm);
            }else{
                this.sumPetrol.text(this.balance.balance);
                this.sumKm = Math.round10(this.balance.balance/(11/100), -2);
                this.restKm.text(this.sumKm);
            }
            this.recountKm();
        },

        inputNewDateFunc: function(){
            this.newDateList.on('change', function(e){
                let oldDate = this.lastDatePetrol.text();
                oldDate = oldDate.split("-").reverse().join("-");
                if(oldDate > $(e.target).val()){
                    this.error.newDateList = 1;
                    alert('Дата путевого листа должна быть больше даты последней заправки');
                }else{
                    this.error.newDateList = 0;
                }
            }.bind(this));
        },


        selectRoute: function () {
            this.selectRoutes.find('select').off('change', function(){
                this.recountKm();
            }.bind(this));
            this.selectRoutes.find('select').on('change', function(){
                this.recountKm();
            }.bind(this));
        },

        recountKm: function () {
            let sum = 0;
            let select = this.selectRoutes.find('select option:selected');
            let km;
            for(let i = 0; select.length > i; i++) {
                if (select[i].dataset.id > 0) {
                    km = Number(select[i].dataset.km);
                    sum += km;
                }
            }
            let sumKm = this.sumKm - sum;
            this.newMileage.text(Number(this.oldMileage.val()) + sum);
            this.restKm.text(Math.round10(sumKm, -2));
            this.finishDayPetrol.text(Math.round10(Number(sumKm) * 0.11, -2));
            if(sumKm < 0){
                this.error.newKm = 1;
                alert('Превышено число километров');
            }else{
                this.error.newKm = 0;
            }
        },

        getBalance: function () {
            return {id: 1, balance: this.finishDayPetrol.text(), last_date: this.lastDatePetrol.text(), mileage: this.newMileage.text(), number_list: Number(this.numberList.val()) + 1}
        }
    }

    if (!window.Block) window.Block = Block;
})();

