

var Time = (function () {

    //Constructor
    function Time() {

        return {
            "calculatePeriod": calculatePeriod,
            "combineTimeAndDate": combineTimeAndDate,
            "getCurrentTime": getCurrentTime,
            "getCurrentDate": getCurrentDate,
            "getTimeZone": detectTimeZone,
            "parseDateTimeString": parseDateTimeString,
            "subtractTime": subtractTime,
            "addTime": addTime,
            "validate": localValidation
        };
    }

    //More Code
    function calculatePeriod(datetime_str, datetime_end) {

        if (localValidation.isTime(datetime_str) && localValidation.isTime(datetime_end)) {

            var datetime_str = parseDateTimeString(datetime_str);
            var datetime_end = parseDateTimeString(datetime_end);

            return (datetime_end - datetime_str) / 1000;
        }
        return false;
    }

    function combineTimeAndDate(time_str, date_str) {

        if (localValidation.isTime(time_str) && localValidation.isDate(date_str)) {
            var time_a = parseDateTimeString(time_str);
            var date_a = parseDateTimeString(date_str);

            console.log(time_a);
            console.log(date_a);
            //year, month, day, hours, minutes,
            return +new Date(date_a[2], date_a[1] - 1, date_a[0], time_a[0], time_a[1]);
        }
        return false;
    }
    function addTime(time, plustime){
        var sum = parseTimeToSeconds(time) + parseTimeToSeconds(plustime);
        return parseSecondsToTimeString(sum);
    }

    function subtractTime(startTime, endTime) {
        return parseTimeToSeconds(endTime) - parseTimeToSeconds(startTime);
    }

    function parseSecondsToTimeString(seconds) {
        var time = [];
        time.push(Math.floor(seconds / 3600));
        time.push(Math.floor(seconds % 3600 / 60));
        time.push(Math.floor(seconds % 60));

        return time.map(function(a){
            return a < 10? '0' + a : a;
        }).join(':');        
    }

    //it converts a time string into seconds
    function parseTimeToSeconds(timestring) {
        var time = 0;
        timestring.split(':').map(function (current, index) {
            var factors = [3600, 60, 1];
            time += parseInt(current) * factors[index];
            return current;
        });
        return time
    }

    function detectTimeZone() {
        var date = new Date();

        var currentHours = (date.getHours() === 0) ? 24 : date.getHours();
        var currentUTCHours = (date.getUTCHours() === 0) ? 24 : date.getUTCHours();

        return currentHours - currentUTCHours;
    }

    function getCurrentTime(specifySeconds) {
        var d = getCurrentDateAsObject();

        var timeArray = (specifySeconds) ? [d.h, d.m, d.s] : [d.h, d.m];

        return timeArray.map(function (n) {
            return (n > 10) ? n : '0' + n;
        }).join(':');
    }

    function getCurrentDate() {
        var d = getCurrentDateAsObject();

        return [d.y, d.M, d.d].map(function (n) {
            return (n > 10) ? n : '0' + n;
        }).join('-');
    }

    function parseDateTimeString(timeString) {
        var datetime_a = [];

        if (localValidation.isTime(timeString)) {
            datetime_a = parse(timeString, ":");
            datetime_a[2] = (datetime_a[2]) ? datetime_a[2] : 00;

            return datetime_a;
        }

        if (localValidation.isDate(timeString)) {
            datetime_a = parse(timeString, "/");

            return datetime_a;
        }

        function parse(str, sep) {
            var ta = str.split(sep);

            return ta.map(function (a) {
                return parseInt(a);
            });
        }
    }

    function getCurrentDateAsObject() {
        var d = new Date();

        //Set vars
        return {
            "y": d.getFullYear(),
            "M": d.getMonth() + 1,
            "d": d.getDate(),
            "h": d.getHours(),
            "m": d.getMinutes(),
            "s": d.getSeconds()
        };
    }

    var localValidation = (function () {
        var timeRegex = "([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?";
        var dateRegex = "([0]?[1-9]|[1|2][0-9]|[3][0|1])[./-]([0]?[1-9]|[1][0-2])[./-]([0-9]{4}|[0-9]{2})";

        //General Validation function
        var validate = function (regex, value) {
            var pattern = new RegExp(regex);
            return pattern.test(value);
        };

        //Specific Validations
        function validateTime(value) {
            return validate(timeRegex, value);
        }


        function validateDate(value) {
            return validate(dateRegex, value);
        }


        //Return observable methods
        return {
            "isTime": validateTime,
            "isDate": validateDate
        };

    }());

    //Return the Constructor
    return Time;
}());

