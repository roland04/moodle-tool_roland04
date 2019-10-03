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
        return html_writer::tag('i', '', ['class' => 'icon fa '.$iconclass]);
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
                'timecreated' => $currenttime,
                'timemodified' => $currenttime
            ];
            $newtodos[] = $newtodo;
        }
        $DB->insert_records('tool_roland04', $newtodos);
    }
}