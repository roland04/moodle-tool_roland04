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
 * Edit TODO page.
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');

global $DB;

$id = optional_param('id', 0, PARAM_INT);

if ($id) {
    $todo = $DB->get_record('tool_roland04', ['id' => $id], '*' , MUST_EXIST);
    $courseid = $todo->courseid;
    $pagetitle = get_string('edittodo', 'tool_roland04');
    $toform = $todo;
} else {
    $courseid = required_param('courseid', PARAM_INT);
    $pagetitle = get_string('addtodo', 'tool_roland04');
    $toform = new stdClass();
}

$course = get_course($courseid);
$toform->courseid = $courseid;

require_login();
$context = context_course::instance($courseid);
require_capability('tool/roland04:edit', $context);

$url = new moodle_url('/admin/tool/roland04/edit.php');
$pagetitle = get_string('addtodo', 'tool_roland04');

$PAGE->set_pagelayout('standard');
$PAGE->set_context($context);
$PAGE->set_url($url, array('courseid' => $courseid, 'id' => $id));
$PAGE->set_title($course->shortname.': '.$pagetitle);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($pagetitle);

$mform = new tool_roland04_form();
$mform->set_data($toform);

$returnurl = new moodle_url('/admin/tool/roland04/index.php', ['courseid' => $courseid]);
if ($mform->is_cancelled()) {
    redirect($returnurl);
} else if ($data = $mform->get_data()) {
    if ($id) {
        // Update todo.
        $data->timemodified = time();
        $DB->update_record('tool_roland04', $data);
    } else {
        // New todo.
        $data->timecreated = $data->timemodified = time();
        $DB->insert_record('tool_roland04', $data);
    }
    redirect($returnurl);
}

echo $OUTPUT->header();
echo $OUTPUT->heading($pagetitle);

$mform->display();

echo $OUTPUT->footer();