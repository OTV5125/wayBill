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
    class PetrolList extends React.Component {
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
            // Добавление маршрутов
            if((props.addPetrol) && (props.addPetrol !== state.addPetrol) && (props.addPetrol>=0)){
                // Берем разницу между существующим заправленным бензином (от стейта) и новым
                let addPetrol = props.addPetrol*1 - state.addPetrol;
                // Такая конструкция нужна для округления до 2 знаков после запятой
                let newAllPetrol = Math.floor((state.allPetrol + addPetrol)*100)/100;
                let newFinishDayPetrol = Math.floor((state.finishDayPetrol+addPetrol)*100)/100;
                // Вычисляем остаток километров на основании бензина в конце дня
                let newRestKM = Math.floor((newFinishDayPetrol*100)/11);
                return {
                    allPetrol: newAllPetrol,
                    restKM: newRestKM,
                    addPetrol: props.addPetrol,
                    finishDayPetrol: newFinishDayPetrol
                }
                // Изменям дату заправки
            } else if ((props.petrolDate) && (props.petrolDate !== state.petrolDate)) {
                return {
                    petrolDate: props.petrolDate
                }
                // Изменяем километраж
            } else if (((props.addKM) || (props.addKM === 0)) && (props.addKM !== state.addKM)) {
                // Вычисляем остаток километров на основании всего бензина
                let newRestKM = Math.floor((state.allPetrol*100)/11) - props.addKM*1;
                if (newRestKM < 0) {
                    alert("Превышено число километров")
                }
                // На основании нового остатка вычисляем новый баланс бензина
                let newPetrolBalance = Math.floor(((newRestKM*11)/100)*100)/100;
                return {
                    // Вычисляем новый пробег
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
    class SelectRoutes extends React.Component {
        constructor(props) {
            super(props);
            this.addSelect = this.addSelect.bind(this);
            this.state = {
                // Данный счетчик нужен для формирования id селектов
                counterSelects: 1,
                selects: [
                    {
                        id: 1,
                        dataKM: 0,
                        returnRace: false
                    }
                ]
            }
        }

        addSelect() {
            this.setState({
                selects: this.state.selects.concat([
               {id: ++this.state.counterSelects,
               dataKM: "",
               returnRace: false}
               ])
            });
        }

        handlerSelect = (e) => {
            let data = e.target.value*1;
            let id = e.target.id*1;
            // Так как ссылку на стейт нельзя использовать, выбрана такая конструкция
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

        handlerCheckboxChange = (e) => {
            let id = e.target.id*1;
            // Так как ссылку на стейт нельзя использовать, выбрана такая конструкция
            console.log(id);
            let selects = JSON.parse(JSON.stringify(this.state.selects));
            for (let obj of selects) {
                if(obj.id === id) {
                    obj.returnRace = !obj.returnRace;
                }
            }
            this.setState({
                selects: selects
            });
            let addKM = 0;
            let name = "select-block";
            for (let obj of selects) {
                if(obj.returnRace) {
                    addKM = addKM + obj.dataKM*2;
                } else {
                    addKM = addKM + obj.dataKM
                }
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
                                        <SelectBlock name="select-block"
                                                      key={item.id}
                                                      id={item.id}
                                                      handlerSelect={this.handlerSelect}
                                                      handlerCheckboxChange = {this.handlerCheckboxChange}
                                        />
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
    function SelectBlock(props) {
            return (
            <div className="checkbox-list-items">
                <select onChange={props.handlerSelect} id={props.id}>
                    <option id="0" value="0">Не выбрано</option>
                    <?php foreach ($routes AS $route): ?>
                    <option id="<?= $route[0] ?>" value="<?= $route[3] ?>"><?= $route[1] ?>
                        - <?= $route[2] ?> (<?= $route[3] ?>км)
                    </option>
                    <?php endforeach; ?>
                </select> <input onChange={props.handlerCheckboxChange} id={props.id} type="checkbox" /> в обратную сторону
            </div>
            )

    }

    class PetrolBlock  extends React.Component{
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
                let firstDate = new Date(value);
                let secondDate = new Date(this.state.newDateList.split('-').reverse().join("-"));
                if (!this.state.newDateList.trim()) {
                    this.setState({
                        petrolDate: value.split('-').reverse().join("-")
                    });
                } else if ( firstDate < secondDate) {
                    this.setState({
                        petrolDate: value.split('-').reverse().join("-")
                    });
                } else  {
                    alert("Дата путевого листа должна быть больше даты последней заправки");
                }
            }
            if(name === "new-date-list") {
                // Нужно будет упростить
                let firstDate = new Date(value);
                let secondDate = new Date(this.state.petrolDate.split('-').reverse().join("-"));
                if (!this.state.petrolDate.trim()) {
                    this.setState({
                        newDateList: value.split('-').reverse().join("-")
                    });
                } else if ( firstDate > secondDate ) {
                    this.setState({
                        newDateList: value.split('-').reverse().join("-")
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
                <div className="wrapper-items">
                    <PetrolList
                        addPetrol = {addPetrol}
                        petrolDate = {petrolDate}
                        addKM = {addKM}
                    />
                    <SelectRoutes updateData={this.updateData}/>
                </div>
            )
        }
    }

    class App extends React.Component {
        render() {
            return (
                <React.Fragment>
                    <h1>Сервис посчитаем бензин</h1>
                    <div className="wrapper">
                        <PetrolBlock/>
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

</body>
</html>




