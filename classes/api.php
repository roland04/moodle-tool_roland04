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

/**
 * Class tool_roland04_api
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_roland04_api {

    /**
     * Retrieve number of registered users with firstname like "%ike%"
     *
     * @param string $likestr string for the LIKE comparison
     * @return int retrieved integer
     */
    public static function count_users_like(string $likestr = ""): int {
        global $DB;

        $sqllike = $DB->sql_like('firstname', ':likestr');
        $params = ['likestr' => '%'.$likestr.'%'];
        return $DB->count_records_sql('SELECT COUNT(id) FROM {user} WHERE '.$sqllike, $params);
    }

    /**
     * Generates Boostrap Badge HTML code
     *
     * @param string $text text for the badge
     * @param string $bscolor boostrap color class (success/warning/...)
     * @return string HTML code for the badge
     */
    public static function bootstrap_badge(string $text, string $bscolor): string {
        return html_writer::span($text, 'badge badge-'.$bscolor);
    }

    /**
     * Generates dump data
     *
     * @param int $i
     * @param int $courseid
     */
    public static function generate_todos(int $q, int $courseid) {
        global $DB;

        $currenttime = time();
        $newtodos = array();
        for ($i=0; $i < $q; $i++) { 
            $newtodo = [
                'courseid' => $courseid,
                'name' => "TODO-".rand()."-".$i,
                'completed' => rand(0,1),
                'priority' => rand(0,1),
                'timecreated' => $currenttime,
                'timemodified' => $currenttime
            ];
            $newtodos[] = $newtodo;
        }
        $DB->insert_records('tool_roland04', $newtodos);
    }
}