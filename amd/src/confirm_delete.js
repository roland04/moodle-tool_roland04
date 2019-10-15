// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Add confirmation
 *
 * @module     tool_roland04/confirm_delete
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'core/str', 'core/notification', 'core/ajax', 'core/templates'], function($, str, notification, ajax, templates) {
    var processDelete = function(id, courseId, todoList) {
        var promises = ajax.call([{
            methodname: 'tool_roland04_delete_todo',
            args: {id: id}
        }]);
        promises[0].done(function() {
            var promises = ajax.call([{
                methodname: 'tool_roland04_get_todo_list',
                args: {courseid: courseId}
            }]);
            promises[0].done(function(data) {
                // eslint-disable-next-line no-console
                console.log('todolist', todoList);
                templates.render('tool_roland04/todo_list', data)
                    .done(function(html) {
                        todoList.html(html);
                    });
            }).fail(notification.exception);
        }).fail(notification.exception);
    };

    return {
        init: function() {
                $(document).on('click', '[data-action=deletetodo]', function(e) {
                    e.preventDefault();
                    var todoId = $(e.currentTarget).attr('data-todoid');
                    var todoList = $("#tool_roland04_TODO_list");
                    var courseId = todoList.attr('data-courseid');
                    str.get_strings([
                        {key: 'confirmation', component:  'admin'},
                        {key: 'confirmdeletetodo', component:  'tool_roland04'},
                        {key: 'yes', component: 'moodle'},
                        {key: 'no', component:  'moodle'}
                    ])
                    .then(function(s) {
                        notification.confirm(s[0], s[1], s[2], s[3], function() {
                            processDelete(todoId, courseId, todoList);
                        });
                        return true;
                    })
                    .catch();
                }
            );
        }
    };
});