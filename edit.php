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

global $DB, $PAGE;

if ($id = optional_param('id', 0, PARAM_INT)) {
    // If there is an id parameter, we get the TODO first, and then courseid.
    $todo = tool_roland04_api::get_todo($id);
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

$returnurl = new moodle_url('/admin/tool/roland04/index.php', ['courseid' => $courseid]);

if ($delete = optional_param('delete', 0, PARAM_INT)) {
    require_sesskey();
    tool_roland04_api::delete_todo($todo->id);
    redirect($returnurl);
}

$url = new moodle_url('/admin/tool/roland04/edit.php');
$pagetitle = get_string('addtodo', 'tool_roland04');

$PAGE->set_pagelayout('standard');
$PAGE->set_context($context);
$PAGE->set_url($url, array('courseid' => $courseid, 'id' => $id));
$PAGE->set_title($course->shortname.': '.$pagetitle);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($pagetitle);

$mform = new tool_roland04_form();

$textfieldoptions = array('trusttext'=>true, 'subdirs'=>true, 'maxfiles'=>-1, 'maxbytes'=>0, 'context'=>$PAGE->context);
if (isset($todo)){
    file_prepare_standard_editor($todo, 'description', $textfieldoptions, $PAGE->context, 'tool_roland04', 'todo', $todo->id);
}

$mform->set_data($toform);

$returnurl = new moodle_url('/admin/tool/roland04/index.php', ['courseid' => $courseid]);
if ($mform->is_cancelled()) {
    redirect($returnurl);
} else if (($data = $mform->get_data()) && confirm_sesskey()) {
    if ($id) {
        // Update todo.
        tool_roland04_api::update_todo($data);
    } else {
        // New todo.
        tool_roland04_api::create_todo($data);
    }
    redirect($returnurl);
}

echo $OUTPUT->header();
echo $OUTPUT->heading($pagetitle);

$mform->display();

echo $OUTPUT->footer();