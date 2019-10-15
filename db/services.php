
<?php
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
 * Web services for tool_devcourse
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$functions = array(
    'tool_roland04_delete_todo' => array(
        'classname'    => 'tool_roland04_external',
        'methodname'   => 'delete_todo',
        'description'  => 'Deletes a TODO',
        'type'         => 'write',
        'capabilities' => 'tool/roland04:edit',
        'ajax'         => true,
    ),
    'tool_roland04_get_todo_list' => array(
        'classname'    => 'tool_roland04_external',
        'methodname'   => 'get_todo_list',
        'description'  => 'Gets todo list renderable',
        'type'         => 'read',
        'capabilities' => 'tool/roland04:view',
        'ajax'         => true,
    )
);