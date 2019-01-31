<?php
require_once("include/databaseFunctions.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
    <script src="./include/jquery-clockpicker.min.js"></script>
    <script>

        $(function () {
            $('#from, #to').autocomplete({
                source: function (request, response) {
                    $.get('https://transport.opendata.ch/v1/locations', {
                        query: request.term,
                        type: 'station'
                    }, function (data) {
                        response($.map(data.stations, function (station) {
                            return {
                                label: station.name,
                                station: station
                            }
                        }));
                    }, 'json');
                },
                select: function (event, ui) {
                    console.log(ui.item.station.id);
                }
            });
        });

        $(function () {
            $("#datepicker").datepicker({
                dateFormat: "dd.mm.yy"
            });
        });
    </script>
</head>
<title>travelr - Search</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style type="text/css" media="all">
    @import "css/style.css";
    @import "css/style2.css";
    @import "css/stylejquery.css";
    @import "css/clockpicker.css";
</style>
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
<body>
<?php
require_once("header.php");
if (@testDatabaseConnection()) {
    ?>
    <div class="content center padding-16">
        <form action="timetable.php" method="GET">
            <div class="container white padding-16">
                <h2 style="margin: 0">Search</h2>
                <div class="row-padding" style="margin:0 -16px;">
                    <div class="ui-widget half">
                        <label for="from"> <b class="left label">From</b></label>
                        <input name="from" class="input border" type="text" placeholder="Departure from" id="from"
                               value="<?php echo $_GET['from'] ?>" required
                               style="font-size: 15px"/>
                    </div>
                    <div class="ui-widget half">
                        <label for="to"> <b class="left label">To</b></label>
                        <input name="to" class="input border" type="text" placeholder="Arriving at" id="to"
                               value="<?php echo $_GET['to'] ?>" required
                               style="font-size: 15px"/>
                    </div>
                    <div class="half">
                        <label for="datepicker"><b class="left label">Date</b></label>
                        <input autocomplete="off" name="date" type="text" id="datepicker" class="border">
                    </div>
                    <div class="half">
                        <div class="almost-half">
                            <label for="timepicker"><b class="left label">Time</b></label>
                            <input autocomplete="off" name="time" type="text" class="form-control border" id="timepicker"
                                   value=""
                                   placeholder="Now">
                        </div>
                        <div class="more-than-half" id="fromto">
                            <label for="fromto" style="padding-right: 1%"><b>From</b></label>
                            <label class="switch" style="position: relative; top: -5px;">
                                <input name="fromto" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                            <label for="fromto" style="padding-left: 1%"><b>To</b></label>
                        </div>
                    </div>
                </div>
                <p>
                    <input class="button theme" type="submit" value="Submit">
                </p>
            </div>
        </form>
    </div>
    <script>
        let input = $('#timepicker').clockpicker({
            placement: 'top',
            align: 'left',
            autoclose: true,
            'default': 'now'
        });

        Date.prototype.toDateInputValue = (function () {
            let local = new Date(this);
            local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
            return local.toLocaleDateString();
        });

        document.getElementById('datepicker').value = new Date().toDateInputValue();
    </script>
    <?php
} else {
    require_once("databaseError.php");
}
require_once("footer.php")
?>
</body>
</html>
  
