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
 * Lib.
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * This function extends the navigation with the tool
 *
 * @param \navigation_node $parentnode The navigation node to extend
 * @param stdClass $course The course to object for the report
 * @param \context_course $context The context of the course
 */
function tool_roland04_extend_navigation_course(navigation_node $parentnode, stdClass $course, context_course $context) {
    if (has_capability('tool/roland04:view', $context)) {
        $url = new moodle_url('/admin/tool/roland04/index.php', array('courseid' => $course->id));
        $roland04node = navigation_node::create(get_string('pluginname', 'tool_roland04'), $url,
                navigation_node::TYPE_COURSE, null, null, new pix_icon('icon', '', 'tool_roland04'));
        $parentnode->add_node($roland04node);
    }
}