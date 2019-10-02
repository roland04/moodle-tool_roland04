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

$cid = optional_param('courseid', 0, PARAM_INT);

require_login();

$url = new moodle_url('/admin/tool/roland04/index.php');
$course = get_course($cid);
$pagetitle = get_string('plugintitle', 'tool_roland04');

$PAGE->set_pagelayout('standard');
$PAGE->set_context(context_course::instance($course->id));
$PAGE->set_url($url, array('courseid' => $cid));
$PAGE->set_title($course->shortname.': '.$pagetitle);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($pagetitle);

echo $OUTPUT->header();
echo $OUTPUT->heading($pagetitle);

// Generate TODOs.
tool_roland04_api::generate_todos(1, $course->id);

// Show general table.
$table = new tool_roland04_table('tool_roland04', $course->id);
$table->out(25, false);

echo $OUTPUT->footer();