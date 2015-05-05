
;
(function ($) {
    $(document).ready(function ($) {

        //Initialize variables
        var time = new Time();       
        var restarauntId = 0;
        window.stored_tables = [];
        window.reserved_tables = [];

        //set default states for fieldgroups in make reservation form
        var stages = {'stage-1': false, 'stage-2': false, 'stage-3': false};

        //Set a reservationDate Instance with Table Interaction as Default
        var dataInteraction = new reservationData('tb');

        //Backup in case ther is no support for time and date elements
        if (!detectInputElementSupport('time')) {
            $('input[type="time"]').mask("99:99", {placeholder: "hh:mm:ss"});
            $('input[type="date"]').mask("9999-99-99", {placeholder: "yyyy-mm-dd"});
        }
        //$('.phone_number').mask("999-9999-999", {placeholder: "999-9999-999"});


        //Change Relevant Behavior         
        var regexes = [
            new RegExp("([0]?[1-9]|[1|2][0-9]|[3][0|1])[./-]([0]?[1-9]|[1][0-2])[./-]([0-9]{4}|[0-9]{2})"),
            new RegExp("^[a-zA-Z][a-zA-Z0-9\.\-_]+@[a-zA-Z]{3,8}\.[a-zA-Z]{2,6}"),
            new RegExp("[1-9]+")];


        if ($('#make-reservation-form').data('restaurant-id')) {
            restarauntId = $('#make-reservation-form').data('restaurant-id');
            loadTablesInMemory();
        }

        //Create Accordion with Restaurants information
        $('.restaurants-info .list').accordion({
            collapsible: true,
            icons: null,
            animate: 285
        });



        //jQuery Validation Methods
        $.validator.addMethod('time', function (value, element) { //Method for time elements validation
            var time = new Time();
            return this.optional(element) || time.validate.isTime(value);
        }, "Please enter a valid time value");

        $.validator.addMethod('fullname', function (value, element) { //Method for time elements validation
            var regex = new RegExp('^[a-zA-Z\u00A1-\uFFFF]+ [a-zA-Z\u00A1-\uFFFF]+');
            return this.optional(element) || regex.test(value);
        }, "Please enter a valid name");

        $.validator.addMethod('phoneNumber', function (value, element) { //Method for time elements validation
            var regex = new RegExp('^[0-9]{3}\-[0-9]{4}\-[0-9]{3}'); //999-9999-999
            return this.optional(element) || regex.test(value);
        }, "Please enter a valid phone number 999-9999-999");

        $.validator.addMethod('notZero', function (value, element) { //Method for time elements validation
            var regex = new RegExp('^[1-9][0-9]*');
            return this.optional(element) || regex.test(value);
        }, "Please enter a number bigger than zero");

        $.validator.addMethod('maxTime', function (value, element) { //Method for time elements validation
            var time = new Time();
            var $startTime = $('#start_time').val();

            var diff = time.subtractTime($startTime, value);

            return this.optional(element) || diff > 0;
        }, "Please enter bigger time value");


        //jQuery Validate Classes Rules
        $.validator.addClassRules("time", {
            required: true,
            time: true
        });

        $.validator.addClassRules("end_time", {
            required: true,
            time: true,
            maxTime: true
        });

        $.validator.addClassRules("fullname", {
            required: true,
            fullname: true
        });

        $.validator.addClassRules("tel", {
            required: true,
            phoneNumber: true
        });

        $.validator.addClassRules("number", {
            required: true,
            digits: true,
            notZero: true
        });

        $.validator.addClassRules("tel", {
            required: true,
            digits: true,
            notZero: true
        });


        //Validate Form
        var validator = $("#make-reservation-form").validate({
            submitHandler: createReservation,
            rules: {
                email: "required"
            }
        });


        //Set Event Listener for make reservation form parent element
        $('.kechup_rr_make_reservation').bind('click change', function (evt) {
            var $element = $(evt.target);

            var time = new Time();
            var form = document.getElementById('make-reservation-form');

            switch (true) {
                case $element.hasClass('restaurant_name') :
                    restarauntId = parseInt($element.val());
                    loadTablesInMemory();
                    break;
                case $element.hasClass('table') :
                    selectTable($element);
                    break;
                case $element.hasClass('person_num'):
                    setTable($element.val());
                    break;
                case $element.hasClass('cancel-reservation'):
                    cancelReservation();
                    break;
                case $element.hasClass('start_time'):
                    setDefaultDuration($element);
                    break;
            }


            function setDefaultDuration($elem) {
                setTimeout(function () {
                    var startTime = $elem.val();
                    $('.end_time').val(time.addTime(startTime, '02:00'));
                }, 700);
            }

        });


        $('.kechup_rr_edit_reservation').bind('click change', function (evt) {
            var $element = $(evt.target);

            var time = new Time();

            switch (true) {
                case $element.hasClass('cancel-reservation'):
                    cancelReservation();
                    break;
            }


            function cancelReservation() {
                dataInteraction.switchInteraction('bk');
                var $key = $('#booking-access-key').val();

                dataInteraction.cancelBooking({'ACCESS_KEY': $key }, function (data) {
                   console.log(data);
                    if (data >= 0) {
                        $('#reservation-message').html('The reservation canceled');
                    }
                });
            }

        });

        //APPLICATION SCOPED FUNCTIONS, these functions are available in applications scope
        function createReservation() {
            dataInteraction.switchInteraction('bk');


            var form = document.getElementById('make-reservation-form');

            //Must be in the right order to work
            //RESTAURANT_ID, TABLE_ID, START_TIME, END_TIME, DATE, NAME, EMAIL, PERSON_NUM, PHONE_NUMBER, STATUS
            var formFields = {
                'RESTAURANT_ID': $('.restaurant_name').val(),
                'TABLE_ID': $('.table-id').val(),
                'START_TIME': $('.start_time').val(),
                'END_TIME': $('.end_time').val(),
                'DATE': $('.reservation-date').val(),
                'NAME': $('.fullname').val(),
                'EMAIL': $('.email').val(),
                'PERSON_NUM': $('.person_num').val(),
                'PHONE_NUMBER': $('.phone_number').val(),
                'STATUS': 'pending',
            };

            var record = objectToArray(formFields);

            dataInteraction.add(record, function (data) {
                console.log(data);
                if (data >= 1) {
                    $('#reservation-message').html('The reservation is booked, you will get an email for confirmation!');
                    resetForm(form);
                }
            });

        }

        function resetForm(form) {
            var index = 0;
            for (; index < form.length; index = index + 1) {
                if (form[index].nodeName.toString().toLowerCase() === 'input' && form[index].type.toString().toLowerCase() !== 'submit') {
                    form[index].value = "";
                }
            }
        }

        function detectInputElementSupport(elemtype) {
            var inpElem = document.createElement('input');
            inpElem.type = elemtype;

            return inpElem.type !== "text";
        }

        function objectToArray(obj) {
            var temp = [];
            var index = 0;
            for (index in obj) {
                temp.push(obj[index]);
            }
            return temp;
        } 

        function loadTablesInMemory() {
            dataInteraction.switchInteraction('tb');

            dataInteraction.getAll(restarauntId, function (data) {
                var result = JSON.parse(data);


                //Setup Loop variables
                var index = 0;
                var current = false;               
                stored_tables = [];

                for (index in result) {
                    current = result[index];
                    window.stored_tables.push([current.ID, current.MIN_CAPACITY, current.CAPACITY, JSON.parse(current.META)]);
                }
            });
        }

        function setAvailableId(personNum) {            
            var id = filterCapacity(personNum, window.reserved_tables);
            var $makeReservationButton = $('#make-reservation-button');
            
            
            $makeReservationButton.attr('disabled', 'disabled');
            if(id > 0){                
                $('#table-id').val(id);
                $makeReservationButton.removeAttr('disabled');
            }
        }
        
        function setTable(personNum) {            
            var formFields = {
                'RESTAURANT_ID': $('.restaurant_name').val(),
                'START_TIME': $('.start_time').val(),
                'END_TIME': $('.end_time').val(),
                'DATE': $('.reservation-date').val(),
                'STATUS': 'confirmed'
            };            
            calculateTables(formFields, personNum);
        }

        /**
         * 
         * @param {integer} personsNum
         * @param {array} usedIds excluded ids
         * @returns {integer} id
         */
        function filterCapacity(personsNum, usedIds) {
            var id = 0;
            var inStore = (stored_tables) ? stored_tables : false;
            
            if (inStore) {
                var index = 0;
                for (; index < inStore.length; index = index + 1) {

                    var tid = parseInt(inStore[index][0]);
                    var tableCapacity = parseInt(inStore[index][2]);

                    if (tableCapacity >= personsNum && usedIds.indexOf(tid.toString()) === -1) {
                        id = tid;
                    }
                }
            }
            return id;
        }
        
        function calculateTables(fields,personNum) {

            dataInteraction.switchInteraction('bk');

            var index = false;
            for (index in fields) {
                if (fields[index] === "") {
                    delete fields[index];
                }
            }

            dataInteraction.getQueriedBooking(fields, function (data) {
                //Get JSON
                var result = JSON.parse(data);                
                window.reserved_tables = [];                

                var index = 0;
                for (; index < result.length; index = index + 1) {
                    var current = result[index];
                    window.reserved_tables.push(current.TABLE_ID);
                }
                
                setAvailableId(personNum);                
            });           
        }

    });
}(jQuery));


