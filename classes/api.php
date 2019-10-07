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
 * tool_roland04 API
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

define('ICON_CHECKED', 'fa-check-square');
define('ICON_UNCHECKED', 'fa-square');
define('BADGE_GREY', 'badge-secondary');
define('BADGE_ORANGE', 'badge-warning');
define('BADGE_RED', 'badge-danger');

/**
 * Class tool_roland04_api
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_roland04_api {

    /**
     * Get the form editor textfieldoptions
     *
     * @return array HTML code for the icon
     */
    public static function get_textfieldoptions(): array {
        global $PAGE;

        return array('trusttext' => true, 'subdirs' => true, 'maxfiles'=>-1, 'maxbytes'=>0, 'context' => $PAGE->context);
    }

    /**
     * Generates Boostrap Badge HTML code
     *
     * @param string $priority text for the badge
     * @return string HTML code for the badge
     */
    public static function print_priority_badge(string $priority): string {
        switch ($priority) {
            case 0:
                $badgeclass = BADGE_GREY;
                break;
            case 1:
                $badgeclass = BADGE_ORANGE;
                break;
            case 2:
                $badgeclass = BADGE_RED;
                break;
            default:
                $badgeclass = BADGE_GREY;
                break;
        }
        return html_writer::span(get_string('priority'.$priority, 'tool_roland04'), 'badge '.$badgeclass);
    }

    /**
     * Generates FA Checkbox icon
     *
     * @param int $completed
     * @return string HTML code for the icon
     */
    public static function print_completion_icon(int $completed): string {
        $iconclass = $completed ? ICON_CHECKED : ICON_UNCHECKED;
        $icontext = $completed ? get_string('completed', 'tool_roland04') : get_string('uncompleted', 'tool_roland04');
        return html_writer::tag('i', '', ['class' => 'icon fa '.$iconclass, 'title' => $icontext, 'aria-label' => $icontext]);
    }

    /**
     * Generates dump data
     *
     * @param int $q number of todos to generate
     * @param int $courseid
     */
    public static function generate_todos(int $q, int $courseid) {
        global $DB;

        $currenttime = time();
        $newtodos = array();
        for ($i = 0; $i < $q; $i++) {
            $newtodo = [
                'courseid' => $courseid,
                'name' => "TODO-".rand()."-".$i,
                'completed' => rand(0, 1),
                'priority' => rand(0, 1),
                'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                        do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
                'descriptionformat' => 1,
                'timecreated' => $currenttime,
                'timemodified' => $currenttime
            ];
            $newtodos[] = $newtodo;
        }
        $DB->insert_records('tool_roland04', $newtodos);
    }

    /**
     * Get a TODO
     *
     * @param int $id TODO id to retrieve
     * @param int $strictness
     * @return stdClass|bool retrieved TODO or false if not found
     */
    public static function get_todo(int $id, int $strictness = MUST_EXIST) {
        global $DB;

        return $DB->get_record('tool_roland04', ['id' => $id], '*', $strictness);
    }

    /**
     * Create a TODO
     *
     * @param stdClass $data data to create TODO
     * @return int $id id of the TODO created
     */
    public static function create_todo(stdClass $data): int {
        global $DB, $PAGE;

        $data->timecreated = $data->timemodified = time();
        $id = $DB->insert_record('tool_roland04', $data);

        $textfieldoptions = self::get_textfieldoptions();
        if (isset($data->description_editor)) {
            $data = file_postupdate_standard_editor($data, 'description', $textfieldoptions, 
                    $PAGE->context, 'tool_roland04', 'todo', $id);
        }
        $DB->update_record('tool_roland04', ['id' => $id, 'description' => $data->description]);

        return $id;
    }

    /**
     * Update a TODO
     *
     * @param stdClass $data data to update TODO
     */
    public static function update_todo(stdClass $data) {
        global $DB, $PAGE;

        $textfieldoptions = self::get_textfieldoptions();
        if (isset($data->description_editor)) {
            $data = file_postupdate_standard_editor($data, 'description', $textfieldoptions, 
                    $PAGE->context, 'tool_roland04', 'todo', $data->id);
        }

        $data->timemodified = time();
        $DB->update_record('tool_roland04', $data);
    }

    /**
     * Delete a TODO
     *
     * @param int $id TODO id to delete
     */
    public static function delete_todo(int $id) {
        global $DB;
        $DB->delete_records('tool_roland04', ['id' => $id]);
    }
}