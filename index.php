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
// admin_externalpage_setup('roland04');

$url = new moodle_url('/admin/tool/roland04/index.php');
$pagetitle = get_string('plugintitle', 'tool_roland04');
$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_title($pagetitle);
$PAGE->set_heading(get_string('pluginname', 'tool_roland04'));

echo $OUTPUT->header();
echo $OUTPUT->heading($pagetitle);

echo html_writer::div(get_string('helloworld', 'tool_roland04'));
echo html_writer::tag('p', get_string('courseid', 'tool_roland04', $cid));

echo $OUTPUT->footer();