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

use core\output\inplace_editable;

defined('MOODLE_INTERNAL') || die();

/**
 * This function extends the navigation with the tool
 *
 * @param navigation_node $parentnode The navigation node to extend
 * @param stdClass $course The course to object for the report
 * @param context_course $context The context of the course
 */
function tool_roland04_extend_navigation_course(navigation_node $parentnode, stdClass $course, context_course $context) {
    if (has_capability('tool/roland04:view', $context)) {
        $url = new moodle_url('/admin/tool/roland04/index.php', array('courseid' => $course->id));
        $roland04node = navigation_node::create(get_string('pluginname', 'tool_roland04'), $url,
                navigation_node::TYPE_COURSE, null, null, new pix_icon('icon', '', 'tool_roland04'));
        $parentnode->add_node($roland04node);
    }
}

/**
 * Serve the files from the tool_roland04 file areas
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param context $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
 */
function tool_roland04_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    if ($context->contextlevel != CONTEXT_COURSE) {
        return false;
    }

    if ($filearea !== 'todo') {
        return false;
    }

    require_login($course);

    if (!has_capability('tool/roland04:view', $context)) {
        return false;
    }

    $itemid = array_shift($args);

    $filename = array_pop($args);
    if (!$args) {
        $filepath = '/';
    } else {
        $filepath = '/'.implode('/', $args).'/';
    }

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'tool_roland04', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false;
    }

    send_stored_file($file, 86400, 0, $forcedownload, $options);
}

/**
 * Inplace editable callback for tool_rolad04
 *
 * @param string $itemtype
 * @param string $itemid
 * @param string $newvalue
 * @return inplace_editable
 */
function tool_roland04_inplace_editable($itemtype, $itemid, $newvalue) {
    global $DB;

    $record = $DB->get_record('tool_roland04', array('id' => $itemid), '*', MUST_EXIST);

    $context = context_course::instance($record->courseid);
    \external_api::validate_context($context);
    require_capability('tool/roland04:edit', $context);

    $newvalue = clean_param($newvalue, PARAM_NOTAGS);

    if ($itemtype === 'todopriority') {
        $DB->update_record('tool_roland04', (object)['id' => $itemid, 'priority' => $newvalue, 'timemodified' => time()]);
        $record->priority = $newvalue;
        $tmpl = new inplace_editable('tool_roland04', 'todopriority', $record->id, true,
            tool_roland04_api::print_priority_badge($record->priority), $record->priority, get_string('edit'),
            format_string($record->name));
        $tagcollections = [0 => 'Low', 1 => 'Medium', 2 => 'High'];
        $tmpl->set_type_select($tagcollections);
    } else if ($itemtype === 'todoname') {
        // TODO: Bug with repeated name.
        $DB->update_record('tool_roland04', (object)['id' => $itemid, 'name' => $newvalue, 'timemodified' => time()]);
        $record->name = $newvalue;
        $tmpl = new inplace_editable('tool_roland04', 'todoname', $record->id, true,
           format_text($record->name) , $record->name, get_string('edit'), format_string($record->name));
    } else if ($itemtype === 'todocompleted') {
        $DB->update_record('tool_roland04', (object)['id' => $itemid, 'completed' => $newvalue, 'timemodified' => time()]);
        $record->completed = $newvalue;
        $tmpl = new inplace_editable('tool_roland04', 'todocompleted', $record->id, true,
            tool_roland04_api::print_completion_icon($record->completed), (int)$record->completed,
            get_string('edit'), format_string($record->completed));
        $tmpl->set_type_toggle(array(0, 1));
    }
    return $tmpl;
}