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
<!--ProtoType-->
<script src="https://unpkg.com/prop-types@15.6/prop-types.js"></script>

<div id="root">

</div>

<script type="text/babel">

    class PetrolList1 extends React.Component {
        state  = {
            newMileage: "",
            finishDayPetrol: ""
        };
        render() {
            const {newMileage,finishDayPetrol} = this.state;
            return(
                <div className="wrapper-item">
                    <div className="petrol-list">
                        Старый пробег: <input className="input-petrol-list old-mileage" placeholder="<?= $balance['mileage'] ?>"/>
                        км. <br />
                        Новый пробег <span data-value="BT45" className="new-mileage">{newMileage}</span> км. <br />
                        Остаток в начале дня: <input className="input-petrol-list start-day-petrol"
                                                     placeholder="<?= $balance['balance'] ?>"/> л. <br />
                        Остаток в конце дня: <span className="finish-day-petrol"> {finishDayPetrol} </span> л. <br />
                        Дата последней заправки <span className="last-date-petrol"><?= $balance['last_date'] ?></span><br />
                        Всего бензина <span className="sum-petrol" data-value="BT38"><?= $balance['balance'] ?></span> л.<br />
                        Осталось километров <span className="rest-km"><?= round($balance['balance'] / (11 / 100)) ?></span> км.<br />
                    </div>
                </div>
            )
        }
    }

    class SelectRoutes1 extends React.Component {
        state = {
            petrol: ""
        };

        handlerPetrolChange = (e) => {
            e.preventDefault();
            this.setState(() => {
                // Важно: используем `state` вместо `this.state` при обновлении.
                return { petrol: e.currentTarget.value }
            });
            this.props.updateDate(this.state.petrol);
        }
        // petrolChange (e) {
        //
        //     this.setState((e) => {
        //         // Важно: используем `state` вместо `this.state` при обновлении.
        //         return { petrol: e.currentTarget.value }
        //     });
        //     // this.setState({ petrol: e.currentTarget.value });
        //
        //     // console.log(this.state.petrol)
        // };

        render() {
            const {petrol} = this.state;
            return(
                <div className="wrapper-item">
                    <div className="select-routes">
                        <div><span className="title">Первый путевой лист</span></div>
                        <br />
                            <input
                                type="number"
                                className="input-select-routes number-list"
                            /> Номер путевого листа <br /><br />
                            <input
                                type="number"
                                className="input-select-routes input-petrol"
                                onChange={this.handlerPetrolChange.bind(this)}
                                value={petrol}
                            /> Бензина залил <br /><br />
                            <input type="date" className="input-select-routes input-petrol-date" /> Дата заправки<br />
                            <br />
                            <input type="date" className="input-select-routes new-date-list" /> Дата путевого листа<br /><br />
                            <div className="checkbox-list">
                                <div className="checkbox-list-items">
                                    <select>
                                        <option data-id="0">не выбрано</option>
                                        <?php foreach ($routes AS $route): ?>
                                        <option data-id="<?= $route[0] ?>" data-km="<?= $route[3] ?>"><?= $route[1] ?>
                                            - <?= $route[2] ?> (<?= $route[3] ?>км)
                                        </option>
                                        <?php endforeach; ?>
                                    </select> <input type="checkbox" /> в обратную сторону
                                </div>
                            </div>
                                <button className="add-route">Добавить маршрут</button>
                                <button className="save">сохранить</button>
                                <button className="get-doc">получить документ</button>
                    </div>
                </div>
            )
        }
    }

    class App  extends React.Component{
        state = {
            petrol: ''
        };
        updateDate = (value) => {
            this.setState({petrol: value});

        };
        render() {
            return (
                <React.Fragment>
                    <h1>Сервис посчитаем бензин</h1>
                    <div className="wrapper">
                        <div className="wrapper-items block-1">
                            <PetrolList1/>
                            <SelectRoutes1 updateDate={this.updateDate}/>
                        </div>
                    </div>
                </React.Fragment>
            )
        }
    };

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
</div>
</body>
</html>




