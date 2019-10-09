define(['jquery', 'core/str', 'core/notification'], function($, str, notification) {
    return {
        init: function() {
                $(document).on('click', '[data-action=deletetodo]', function(e) {
                    e.preventDefault();
                    let targetUrl = $(e.currentTarget).attr('href');
                    str.get_strings([
                        {
                            key:        'confirmation',
                            component:  'admin'
                        },
                        {
                            key:        'confirmdeletetodo',
                            component:  'tool_roland04'
                        },
                        {
                            key:        'yes',
                            component:  'moodle'
                        },
                        {
                            key:        'no',
                            component:  'moodle'
                        }
                    ])
                    .then(function(s) {
                        notification.confirm(s[0], s[1], s[2], s[3], function() {
                            window.location = targetUrl;
                        });
                        return true;
                    })
                    .catch();
                }
            );
        }
    };
});