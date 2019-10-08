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

use tool_roland04\output\todo_list;

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

$outputpage = new todo_list($courseid);
$output = $PAGE->get_renderer('tool_roland04');
echo $output->header();
echo $output->heading($pagetitle);
echo $output->render($outputpage);
echo $output->footer();