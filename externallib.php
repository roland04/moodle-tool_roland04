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
 * Class tool_roland04_external
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tool_roland04\output\todo_list;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . "/externallib.php");

/**
 * Web services for tool_roland04
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_roland04_external extends external_api {

    /**
     * Parameters for delete_todo
     *
     * @return external_function_parameters
     */
    public static function delete_todo_parameters() {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'id of the TODO'),
            )
        );
    }

    /**
     * Return for delete_todo
     *
     * @return null
     */
    public static function delete_todo_returns() {
        return null;
    }

    /**
     * delete_todo API function
     *
     * @param $id
     */
    public static function delete_todo($id) {
        $params = self::validate_parameters(self::delete_todo_parameters(),
            array('id' => $id));
        $id = $params['id'];
        $todo = tool_roland04_api::get_todo($id);

        if (!$todo) {
            throw new invalid_parameter_exception('TODO does not exist with that ID');
        }
        $context = context_course::instance($todo->courseid);
        self::validate_context($context);
        require_capability('tool/roland04:edit', $context);

        tool_roland04_api::delete_todo($id);
    }

    /**
     * Parameters for get_todo_list
     *
     * @return external_function_parameters
     */
    public static function get_todo_list_parameters() {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'id of the course'),
            )
        );
    }

    /**
     * Return for get_todo_list
     *
     * @return external_single_structure
     */
    public static function get_todo_list_returns() {
        return new external_single_structure(
            array(
                'button' => new external_value(PARAM_RAW, 'Add todo button'),
                'table' => new external_value(PARAM_RAW, 'Table with TODO list'),
                'courseid' => new external_value(PARAM_INT, 'Course id')
            )
        );
    }

    /**
     * get_todo_list API function
     *
     * @param $courseid
     * @return stdClass
     */
    public static function get_todo_list($courseid) {
        global $PAGE;

        $params = self::validate_parameters(self::get_todo_list_parameters(),
            array('courseid' => $courseid));
        $courseid = $params['courseid'];

        $context = context_course::instance($courseid);
        self::validate_context($context);
        require_capability('tool/roland04:view', $context);

        $outputpage = new todo_list($courseid);
        $renderer = $PAGE->get_renderer('tool_roland04');
        return $outputpage->export_for_template($renderer);
    }
}