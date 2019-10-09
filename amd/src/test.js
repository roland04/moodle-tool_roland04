define(['jquery'], function($) {

    return {
        init: function() {

            // Put whatever you like here. $ is available
            // to you as normal.
            $(".badge-warning").click(function() {
                // eslint-disable-next-line no-console
                console.log("Badge clicked");
            });
        }
    };
});