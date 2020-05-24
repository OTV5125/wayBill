<?php
/**
 * Created by PhpStorm.
 * User: mihailnilov
 * Date: 28.04.2020
 * Time: 14:55
 */

require_once 'vendor/autoload.php';


use Service\MysqlSelect;

$mysql = new MysqlSelect();
$routes = $mysql->getRoutes();
$balance = $mysql->getBalance();


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>


<!-- Подключение React только для разработки -->
<script src="https://unpkg.com/react@16/umd/react.development.js"></script>
<script src="https://unpkg.com/react-dom@16/umd/react-dom.development.js"></script>
<!-- Babel -->
<script src="https://unpkg.com/babel-standalone@6.15.0/babel.min.js"></script>

<div id="root">

</div>

<script type="text/babel">
    // верхний блок (с результатами)
    class PetrolList1 extends React.Component {
        constructor (props) {
            super(props);
            this.state = {
                addPetrol: "",
                addKM: 0,
                oldMileage: <?= $balance['mileage'] ?>,
                newMileage: <?= $balance['mileage'] ?>,
                startDayPetrol: <?= $balance['balance'] ?>,
                finishDayPetrol: <?= $balance['balance'] ?>,
                petrolDate: "<?= $balance['last_date'] ?>",
                allPetrol: <?= $balance['balance'] ?>,
                restKM: "<?= round($balance['balance'] / (11 / 100)) ?>"
            };
        }

        static getDerivedStateFromProps(props, state){
            if((props.addPetrol) && (props.addPetrol !== state.addPetrol) && (props.addPetrol>=0)){
                let addPetrol = props.addPetrol*1 - state.addPetrol;
                let newAllPetrol = Math.floor((state.allPetrol + addPetrol)*100)/100;
                let newFinishDayPetrol = Math.floor((state.finishDayPetrol+addPetrol)*100)/100;
                let newRestKM = Math.floor((newFinishDayPetrol*100)/11);
                return {
                    allPetrol: newAllPetrol,
                    restKM: newRestKM,
                    addPetrol: props.addPetrol,
                    finishDayPetrol: newFinishDayPetrol
                }
            } else if ((props.petrolDate) && (props.petrolDate !== state.petrolDate)) {
                return {
                    petrolDate: props.petrolDate
                }
            } else if (((props.addKM) || (props.addKM === 0)) && (props.addKM !== state.addKM)) {
                let newRestKM = Math.floor((state.allPetrol*100)/11) - props.addKM*1;
                if (newRestKM < 0) {
                    alert("Превышено число километров")
                }
                let newPetrolBalance = Math.floor(((newRestKM*11)/100)*100)/100;
                return {
                    newMileage: state.oldMileage + props.addKM,
                    restKM: newRestKM,
                    addKM: props.addKM,
                    finishDayPetrol: newPetrolBalance
                }
            }
            return null;
        }
        render() {
            const {newMileage,finishDayPetrol, startDayPetrol, petrolDate, allPetrol, restKM, oldMileage} = this.state;
            return(
                <div className="wrapper-item">
                    <div className="petrol-list">
                        Старый пробег: <input className="input-petrol-list" placeholder={oldMileage}/>
                        км. <br />
                        Новый пробег <span>{newMileage}</span> км. <br />
                        Остаток в начале дня: <input className="input-petrol-list"
                                                     placeholder={startDayPetrol}/> л. <br />
                        Остаток в конце дня: <span> {finishDayPetrol} </span> л. <br />
                        Дата последней заправки <span> {petrolDate} </span><br />
                        Всего бензина <span> {allPetrol} </span> л.<br />
                        Осталось километров <span> {restKM} </span> км.<br />
                    </div>
                </div>
            )
        }
    }
    // Нижний блок с полями ввода
    class SelectRoutes1 extends React.Component {
        constructor(props) {
            super(props);
            this.addSelect = this.addSelect.bind(this);
            // this.handlerSelect = this.handlerSelect.bind(this);
            this.state = {
                counterSelects: 1,
                selects: [
                    {
                        id: 1,
                        dataKM: 0
                    }
                ]
            }
        }

        addSelect() {
            this.setState({
                selects: this.state.selects.concat([
               {id: ++this.state.counterSelects,
               dataKM: ""}
               ])
            });
        }

        handlerSelect = (e) => {
            let data = e.target.value*1;
            let id = e.target.id*1;
            let selects = JSON.parse(JSON.stringify(this.state.selects));
            for (let obj of selects) {
                if(obj.id === id) {
                    obj.dataKM = data;
                }
            }
            this.setState({
                selects: selects,
                addKM: this.state.addKM + data
            });
            let addKM = 0;
            let name = "select-block";
            for (let obj of selects) {
                addKM = addKM + obj.dataKM;
            }
            this.props.updateData(addKM,name);

        };


        handlerInputChange = (e) => {
            let data = e.currentTarget.value;
            let name = e.target.name;
            this.props.updateData(data,name);
        };

        render() {
            return(
                <div className="wrapper-item">
                    <div className="select-routes">
                        <div><span className="title">Первый путевой лист</span></div>
                        <br />
                            <input
                                type="number"
                                className="input-select-routes"
                                name="number-list"
                                onChange={this.handlerInputChange}
                            /> Номер путевого листа <br /><br />
                            <input
                                type="number"
                                className="input-select-routes"
                                name="input-petrol"
                                onChange={this.handlerInputChange}
                            /> Бензина залил <br /><br />
                            <input
                                type="date"
                                className="input-select-routes"
                                name="input-petrol-date"
                                onChange={this.handlerInputChange}
                            /> Дата заправки<br /> <br />
                            <input type="date"
                                   className="input-select-routes"
                                   name="new-date-list"
                                   onChange={this.handlerInputChange}
                            /> Дата путевого листа<br /><br />
                            <div className="checkbox-list">
                                { // здесь будет отрисовано необходимое кол-во компонентов
                                    this.state.selects.map((item) => (
                                        <SelectBlock1 name="select-block" key={item.id} id={item.id} handlerSelect={this.handlerSelect}/>
                                    ))
                                }
                            </div>
                                <button onClick={this.addSelect}>Добавить маршрут</button>
                                <button className="save">сохранить</button>
                                <button className="get-doc">получить документ</button>
                    </div>
                </div>
            )
        }
    }
    // Блок селектов
    function SelectBlock1(props) {
            return (
            <div className="checkbox-list-items">
                <select onChange={props.handlerSelect} id={props.id}>
                    <option id="0" value="0">Не выбрано</option>
                    <?php foreach ($routes AS $route): ?>
                    <option id="<?= $route[0] ?>" value="<?= $route[3] ?>"><?= $route[1] ?>
                        - <?= $route[2] ?> (<?= $route[3] ?>км)
                    </option>
                    <?php endforeach; ?>
                </select> <input type="checkbox" /> в обратную сторону
            </div>
            )

    }

    class App  extends React.Component{
        state = {
            addKM: '',
            numberList: '',
            addPetrol: '',
            petrolDate: '',
            newDateList: ''
        };
        updateData = (value,name) => {
            if ((name === "number-list") && (value>=0)) {
                this.setState({
                    numberList: value
                });
            }
            if (name ==="input-petrol") {
                this.setState({
                    addPetrol: value
                });
            }
            if(name === "input-petrol-date") {
                let originDate = value.split('-');
                let date = originDate.reverse().join('-');
                this.setState({
                    petrolDate: date
                });
            }
            if(name === "new-date-list") {
                let firstDate = new Date(value);
                let secondDate = new Date(this.state.petrolDate)
                if ( firstDate < secondDate ) {
                    this.setState({
                        newDateList: value
                    });
                } else  {
                    alert("Дата путевого листа должна быть больше даты последней заправки");
                }
            }
            if (name === "select-block") {
                this.setState({
                    addKM: value
                });
            }
        };
        render() {
            const {addPetrol, petrolDate,addKM} = this.state;
            return (
                <React.Fragment>
                    <h1>Сервис посчитаем бензин</h1>
                    <div className="wrapper">
                        <div className="wrapper-items block-1">
                            <PetrolList1
                                addPetrol = {addPetrol}
                                petrolDate = {petrolDate}
                                addKM = {addKM}
                            />
                            <SelectRoutes1 updateData={this.updateData}/>
                        </div>
                    </div>
                </React.Fragment>
            )
        }
    }

    ReactDOM.render(
        <App/>,
        document.getElementById('root')
    );


</script>
<!--<script src="http://code.jquery.com/jquery-3.5.0.js"></script>-->
<!--<script src="js/ajax.js"></script>-->
<!--<script src="js/Math.js"></script>-->
<!--<script src="js/routing.js"></script>-->
<!--<script src="js/block.js"></script>-->

<!--<script>-->
<!--    window.onload = function () {-->
<!--        new Routing('.block-1', --><?//=json_encode($balance)?><!--, --><?//=json_encode($routes)?><!--);-->
<!--    }-->
<!--</script>-->
<?//=var_dump($balance)?>








<!--    <div class="wrapper-items block-2">-->
<!--        <div class="wrapper-item">-->
<!--            <div class="petrol-list">-->
<!--                Старый пробег: <input disabled class="input-petrol-list old-mileage" placeholder="--><?//= $balance['mileage'] ?><!--">-->
<!--                км. <br>-->
<!--                Новый пробег <span data-value="BT45" class="new-mileage"></span>км. <br>-->
<!--                Остаток в начале дня: <input disabled class="input-petrol-list start-day-petrol"-->
<!--                                             placeholder="--><?//= $balance['balance'] ?><!--"> л. <br>-->
<!--                Остаток в конце дня: <span class="finish-day-petrol"> </span> л. <br>-->
<!--                Дата последней заправки <span class="last-date-petrol">--><?//= $balance['last_date'] ?><!--</span><br>-->
<!--                Всего бензина <span class="sum-petrol" data-value="BT38">--><?//= $balance['balance'] ?><!--</span> л.<br>-->
<!--                Осталось километров <span class="rest-km">--><?//= round($balance['balance'] / (11 / 100)) ?><!--</span> км.<br>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!---->
<!--        <div class="wrapper-item">-->
<!--            <div class="select-routes">-->
<!--                <div><span class="title">Первый путевой лист</span></div>-->
<!--                <br>-->
<!--                <input type="number" disabled class="input-select-routes number-list" value="1"> Номер путевого листа <br><br>-->
<!--                <input type="number" class="input-select-routes input-petrol"> Бензина залил <br><br>-->
<!--                <input type="date" class="input-select-routes input-petrol-date"> Дата заправки<br>-->
<!--                <br>-->
<!--                <input type="date" class="input-select-routes new-date-list"> Дата путевого листа<br><br>-->
<!--                <div class="checkbox-list">-->
<!--                    <div class="checkbox-list-items">-->
<!--                        <select>-->
<!--                            <option data-id="0">не выбрано</option>-->
<!--                            --><?php //foreach ($routes AS $route): ?>
<!--                                <option data-id="--><?//= $route[0] ?><!--" data-km="--><?//= $route[3] ?><!--">--><?//= $route[1] ?>
<!--                                    - --><?//= $route[2] ?><!-- (--><?//= $route[3] ?><!--км)-->
<!--                                </option>-->
<!--                            --><?php //endforeach; ?>
<!--                        </select> <input type="checkbox"> в обратную сторону-->
<!--                    </div>-->
<!--                </div>-->
<!--                <button class="add-route">Добавить маршрут</button>-->
<!--                <button class="get-doc">получить документ</button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
</body>
</html>




