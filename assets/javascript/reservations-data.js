var reservationData = (function ($) {
    var interactionName = false;

    /*
     Stored Interactions with the already supported interactions
     If you have added any new interactions just use the setInteraction method to change the interactionName directly
     Or follow the pattern:
     
     var restaurant = reservationData('wp');
     //Do some code
     
     restaurant.storeInteraction('ni', 'new_interaction');
     restaurant.switchInteraction('ni');
     
     //Do more code;
     
     */
    var storedInteractions = {        
        "bk": 'bookings',
        "tb": 'tables',
        'mlc': 'mailchimper'
    };

    //Constructor
    function reservationData(interaction) {
        interactionName = storedInteractions[interaction];

        return {
            "getInteraction": getInteractionName,
            "setInteraction": setInteractionName,
            "storeInteraction": setStoredInteraction,
            "switchInteraction": changeInteraction,
            //These work with working_periods, bookings and tables interactions
            "add": add,
            "get": get,
            "getAll": getAll,
            "edit": edit,
            "remove": remove,            
            //These works only with tables interaction
            "addMultipleTables": createMultipleTables,
            "setTableMeta": setTableMeta,
            //These work only with Bookings
            "getQueriedBooking": getQueriedBooking,
            "getBookingByKey": getBookingByKey,
            "cancelBooking": cancelBooking,
            //These work only with mailchimper interaction
            "getContactsLists": getContactsLists,
            //This only works with mailchimp
            "uploadToMailChimp": uploadToContactsLists
        };
    }

    //The Functionality Follows
    function ajaxcall(method, recordData, callback) {

        $.ajax({
            type: "post",
            url: kechup_rr_admin_vars.url,
            //contentType: "application/x-www-form-urlencoded;charset=utf-8",
            data: {
                'action': "kechup_rr_" + interactionName + "_interact",
                'validation_key': kechup_rr_admin_vars.kechup_rr_cnonce,
                'operation': method,
                'data': JSON.stringify(recordData)
            },
            success: function (data) {
                callback(data);
            }
        });
    }

    function getInteractionName(name) {
        return interactionName;
    }

    function setInteractionName(name) {
        interactionName = name;
        return interactionName;
    }

    function setStoredInteraction(sortname, name) {
        storedInteractions[sortname] = name;
        return true;
    }

    function changeInteraction(sortname) {
        interactionName = storedInteractions[sortname];
        return interactionName;
    }

    function add(recordData, callback) {
        ajaxcall('create', recordData, callback);
        return true;
    }

    function get(recordId, callback) {
        ajaxcall('get', [recordId], callback);
        return true;
    }

    function getAll(restaurantId, callback) {
        ajaxcall('getAll', restaurantId, callback);
        return true;
    }

    function edit(recordData, callback) {
        ajaxcall('edit', recordData, callback);
        return true;
    }

    function remove(recordId, callback) {
        ajaxcall('delete', recordId, callback);
        return true;
    }   

    function getQueriedBooking(recordData, callback) {
        if (interactionName === 'bookings') {
            ajaxcall('getQueried', recordData, callback);
            return true;
        } else {
            return false;
        }
    }
    
    function getBookingByKey(bookingKey, callback) {
        if ( interactionName === 'bookings') {
            ajaxcall('getByKey', bookingKey, callback);
            return true;
        } else {
            return false;
        }
    }

    function cancelBooking(bookingKey, callback) {
        if (interactionName === 'bookings') {
            ajaxcall('cancelBooking', bookingKey, callback);
            return true;
        } else {
            return false;
        }
    }

    function createMultipleTables(restaurantId, callback) {
        if (interactionName === 'tables') {
            ajaxcall('createMultiple', restaurantId, callback);
            return true;
        } else {
            return false;
        }
    }

    function setTableMeta(recordData, callback) {
        if (interactionName === 'tables') {
            ajaxcall('editMeta', recordData, callback);
            return true;
        } else {
            return false;
        }
    }

    function getContactsLists(callback) {
        if (interactionName === 'mailchimper') {
            ajaxcall('getLists', [], callback);
            return true;
        } else {
            return false;
        }
    }
    function uploadToContactsLists(recordData, callback) {
        if (interactionName === 'mailchimper') {
            ajaxcall('subscribeUsersFrom', recordData, callback);
            return true;
        } else {
            return false;
        }
    }



    //Return the Constructor
    return reservationData;
}(jQuery));