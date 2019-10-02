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
 * Main page.
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

$courseid = required_param('courseid', PARAM_INT);

$course = get_course($courseid);

require_login($courseid);
$context = context_course::instance($courseid);
require_capability('tool/roland04:view', $context);

$url = new moodle_url('/admin/tool/roland04/index.php');
$pagetitle = get_string('viewtodos', 'tool_roland04');

$PAGE->set_pagelayout('standard');
$PAGE->set_context($context);
$PAGE->set_url($url, array('courseid' => $courseid));
$PAGE->set_title($course->shortname.': '.$pagetitle);
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading($pagetitle);

// Show general table.
$table = new tool_roland04_table('tool_roland04', $courseid);
$table->out(25, false);

echo $OUTPUT->box_start();
echo $OUTPUT->single_button(new moodle_url('./edit.php', ['courseid' => $courseid]), get_string('addtodo', 'tool_roland04'), 'get');
echo $OUTPUT->box_end();

echo $OUTPUT->footer();