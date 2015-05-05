
;
(function ($) {
    $(document).ready(function ($) {

        window.tablesInLayout = [];
        window.selected = [];

        //Backup in case ther is no support for time and date elements
        if (!detectInputElementSupport('time')) {
            $('input[type="time"]').mask("99:99:99", {placeholder: "hh:mm:ss"});
            $('input[type="date"]').mask("9999-99-99", {placeholder: "yyyy-mm-dd"});
        }       


        //Init Time Object
        var time = new Time();

        //Create A restaurant object to call the data methods
        var restarauntId = $('#current-restaurant').data('id');


        //TABLES
        $('#available_tables').bind('click change', function (evt) {
            var $Telement = $(evt.target);
            var table = new reservationData('tb');


            //Stored IDs for tables used in Restaurant Tables Layout
            var tablesInLayout = window.tablesInLayout || [];
            var selected = window.selected || [];

            if (!stored_tables) {
                stored_tables = [];
            }

            switch (true) {
                case $Telement.hasClass('add-multiple-tables') :
                    createTables($Telement);
                    break;
                case $Telement.hasClass('update-table') :
                    updateTable($Telement);
                    break;
                case $Telement.hasClass('delete-table') :
                    deleteTable($Telement);
                    break;                           
            }


            function createTables($button) {
                evt.preventDefault();

                var tableNumber = $('#table-num').val();
                var capacity = $('#table-cap').val();
                var minCapacity = $('#table-min-cap').val();


                var recordData = [];
                var index = 0;
                for (; index < tableNumber; index = index + 1) {
                    recordData.push([restarauntId, minCapacity, capacity]);
                }

                //RESTAURANT_ID, MIN_CAPACITY, CAPACITY, META
                table.addMultipleTables(recordData, function (data) {
                    if (data) {
                        refreshTables();
                    }
                });

                return false;
            }

            function updateTable($button) {
                evt.preventDefault();

                var $Table = $button.parent('.table-record');
                var tableId = $Table.data('table-id');

                var minCapacity = $Table.children('.table-min-cap').val();
                var capacity = $Table.children('.table-cap').val();
                var name = $Table.children('.table-name').val();


                table.edit([capacity, minCapacity, name, tableId], function (data) {
                    if (data) {
                        refreshTables();
                    }
                });

                return false;
            }

            function deleteTable($button) {
                evt.preventDefault();

                var $Table = $button.parent('.table-record');
                var tableId = $Table.data('table-id');

                table.remove(tableId, function (data) {
                    if (data) {
                        refreshTables();
                    }
                });

                return false;
            }
                        

            //This function refresh Edit Tables Area
            function refreshTables() {
                table.getAll(restarauntId, function (data) {
                    //Setup the base variables
                    var Template = document.getElementById('table-record-template').innerHTML;
                    var result = JSON.parse(data);

                    //Setup Loop variables
                    var index = 0;
                    var current = false;
                    var inner = '';
                    stored_tables = [];

                    for (index in result) {
                        current = result[index];

                        //Replace All values
                        inner += Template.replace('{{table-id}}', current.ID)
                                .replace('{{table-id}}', current.ID)
                                .replace('{{table-id}}', current.ID)
                                .replace('{{name}}', current.NAME)
                                .replace('{{name}}', current.NAME)
                                .replace('{{min-allowed-cap}}', kechup_rr_admin_vars.minimum_persons_per_table)
                                .replace('{{min-allowed-cap}}', kechup_rr_admin_vars.minimum_persons_per_table)
                                .replace('{{table-min-cap}}', current.MIN_CAPACITY)
                                .replace('{{table-cap}}', current.CAPACITY);

                        stored_tables.push([current.ID, current.MIN_CAPACITY, current.CAPACITY, JSON.parse(current.META)]);
                    }                   

                    //Assign new content                    
                    (document.getElementById('edit-tables-for-' + restarauntId)).getElementsByClassName('inner-container')[0].innerHTML = inner;                    
                });
            }                 

            String.prototype.removePrefix = function (prefix) {
                return parseInt(this.replace(prefix, ''));
            };
        });

        $('#bookings-page').bind('click change', function (evt) {
            var $element = $(evt.target);

            //Listener vars
            var currentRestaurantId = '';

            //Initialize Reservation Data Object
            var reservation = new reservationData('bk');
            var form = document.getElementById('create_booking');
            var saveBookingButton = document.getElementsByClassName('save-booking')[0];

            //Must be in the right order to work
            //RESTAURANT_ID, TABLE_ID, START_TIME, END_TIME, DATE, NAME, EMAIL, PERSON_NUM, PHONE_NUMBER, STATUS
            var formFields = {
                'RESTAURANT_ID': form[1].value,
                'TABLE_ID': form[5].value,
                'START_TIME': form[7].value,
                'END_TIME': form[9].value,
                'DATE': form[11].value,
                'NAME': form[15].value,
                'EMAIL': form[19].value,
                'PERSON_NUM': form[13].value,
                'PHONE_NUMBER': form[17].value,
                'STATUS': form[3].value
            };

            switch (true) {
                case $element.is('select') || $element.is('input') || $element.hasClass('box'):
                    setTimeout(function () {
                        refreshBookings();
                    }, 500);

                case $element.hasClass('restaurant_name') :
                    reloadTablesSelection($element);
                    break;
                case $element.hasClass('save-booking') :
                    addBooking();
                    break;
                case $element.hasClass('status-button') :
                    changeBookingStatus($element);
                    break;
            }

            function addBooking() {
                evt.preventDefault();

                var index = false;
                for (index in formFields) {
                    if (formFields[index] === "") {
                        delete formFields[index];
                    }
                }

                //Object.keys(formFields).length gives the length of the object, the attribute length isn't present in not indexed objects
                if (Object.keys(formFields).length === 10 && saveBookingButton.getAttribute('disabled') !== 'disabled') {
                    var record = [formFields.RESTAURANT_ID, formFields.TABLE_ID, formFields.START_TIME, formFields.END_TIME, formFields.DATE, formFields.NAME, formFields.EMAIL, formFields.PERSON_NUM, formFields.PHONE_NUMBER, formFields.STATUS];
                    
                    reservation.add(record, function (data) {
                        if (data) {
                            refreshBookings();
                        }
                    });

                }
                return false;
            }

            function changeBookingStatus($button) {
                evt.preventDefault();

                var $parent = $button.closest('.booking');
                var id = parseInt($parent.attr('id').replace('booking-', ''));

                reservation.edit({'ID': id, 'STATUS': $button.data('status')}, function (data) {
                    if (data) {
                        refreshBookings();
                    }
                });

                return false;
            }

            function reloadTablesSelection($button) {
                if ($button.hasClass('restaurant_name'))
                    setTimeout(function () {
                        if ($button.val() !== currentRestaurantId) {
                            reservation.switchInteraction('tb');

                            reservation.getAll($button.val(), function (data) {
                                var result = JSON.parse(data);
                                var Template = '<option value="{{id}}">{{text}}</option>';

                                //Setup Loop variables
                                var index = 0;
                                var inner = '<option value="">' + kechup_rr_admin_vars.translations.all + '</option>';

                                for (index in result) {
                                    var current = result[index];

                                    //Replace All values
                                    inner += Template.replace('{{id}}', current.ID)
                                            .replace('{{text}}', 'ID: ' + current.ID + ' Cap: ' + current.CAPACITY);
                                }

                                $('#table-id').html(inner);
                            });
                        }
                    }, 500);
            }

            function refreshBookings() {
                
                var index = false;
                for (index in formFields) {
                    if (formFields[index] === "") {
                        delete formFields[index];
                    }
                }

                reservation.getQueriedBooking(formFields, function (data) {
                    var template = document.getElementById('booking-record-template').innerHTML;
                    var result = JSON.parse(data);

                    var index = 0;
                    var inner = '';

                    for (; index < result.length; index = index + 1) {
                        var current = result[index];

                        var currentRestaurant = getRestaurantById(StoredRestaurants, current.RESTAURANT_ID);


                        inner += template.replace('{{id}}', current.ID)
                                .replace('{{restaurant-id}}', current.RESTAURANT_ID)
                                .replace('{{name}}', current.NAME)
                                .replace('{{restaurant-name}}', currentRestaurant.title)
                                .replace('{{persons-number}}', current.PERSON_NUM)
                                .replace('{{start-time}}', current.START_TIME)
                                .replace('{{end-time}}', current.END_TIME)
                                .replace('{{date}}', current.DATE)
                                .replace('{{email}}', current.EMAIL)
                                .replace('{{phone-number}}', current.PHONE_NUMBER)
                                .replace('{{table-id}}', current.TABLE_ID)
                                .replace(current.STATUS, current.STATUS + ' highlighted');
                    }

                    if (Object.keys(formFields).length > 7) {
                        isAvailableForBooking(formFields);
                    }
                    else if (Object.keys(formFields).length <= 7 && saveBookingButton.getAttribute('disabled') === 'disabled') {
                        saveBookingButton.removeAttribute('disabled');
                    }

                    (document.getElementById('list_bookings')).getElementsByTagName('tbody')[0].innerHTML = inner;
                });

            }

            function isAvailableForBooking(fieldsObj) {

                var queryObj = {
                    'RESTAURANT_ID': fieldsObj.RESTAURANT_ID,
                    'TABLE_ID': fieldsObj.TABLE_ID,
                    'START_TIME': fieldsObj.START_TIME,
                    'END_TIME': fieldsObj.END_TIME,
                    'DATE': fieldsObj.DATE,
                    'PERSON_NUM' : fieldsObj.PERSON_NUM
                };

                reservation.getQueriedBooking(queryObj, function (data) {
                    var result = JSON.parse(data);
                    var index = 0;
                    var rejected = 0;

                    for (; index < result.length; index = index + 1) {
                        var current = result[index];
                        rejected = (current.STATUS === 'rejected') ? rejected + 1 : rejected;
                    }

                    if (result.length > 0 && rejected < result.length) {
                        saveBookingButton.setAttribute('disabled', 'disabled');
                    }
                    else if (saveBookingButton.getAttribute('disabled') === 'disabled') {
                        saveBookingButton.removeAttribute('disabled');
                    }

                });
            }


            function getRestaurantById(restaurants, id) {
                var index = 0;

                for (; index < restaurants.length; index = index + 1) {
                    var current = restaurants[index];

                    if (parseInt(id) === parseInt(current['id'])) {
                        return current;
                    }
                }
                return false;
            }

        });
        
        $('.post-type-notification').bind('click change', function(evt){
            var $element = $(evt.target);
           
            
            switch (true) {
                case $element.hasClass('use-notification-template') :
                    useNotificationTemplate();
                    break;              
                case $element.hasClass('reservation_page_link') :
                    generateCancelLink();
                    break;              
                case $element.hasClass('insert-to-template') :
                    InsertLinkToTemplate();
                    break;              
            }
                      
            function InsertLinkToTemplate(){
                var $link =  $('#reservation_cancel_link').val();                
                $('#notification_template-text').val( $('#notification_template-text').val() + $link );
            }
            
            function generateCancelLink($element){
                var $link = $('.reservation_page_link').val();
                var $querySign = ( $link.indexOf('?') > -1 ) ? '&' : '?';
                var src = $('.reservation_page_link').val() + $querySign +'booking-access-key={{access_key}}';
                
                $('#reservation_cancel_link').val( '<a href="' + src + '">' + src + '</a>' );
            }
            
            function useNotificationTemplate(){
                evt.preventDefault();
                
                var $category = $('#notification-category-select').val();
                var $template = $('#notification-'+$category).val();
                
                $('#notification_template-text').val( $template );
                
                return false;
            }
            
        });      

        //APPLICATION SCOPED FUNCTIONS, these functions are available in applications scope
        function detectInputElementSupport(elemtype) {
            var inpElem = document.createElement('input');
            inpElem.type = elemtype;

            return inpElem.type !== "text";
        }

        function countUnusedTables() {
            return tablesInLayout.length + '/' + stored_tables.length;
        }



    });

}(jQuery));