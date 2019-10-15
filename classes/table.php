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
 * Class tool_roland04_table
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/tablelib.php');

/**
 * Class tool_roland04_table for displaying tool_roland04 table
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_roland04_table extends table_sql {

    /**
     * Sets up the tool_roland04_table parameters.
     *
     * @param string $uniqueid unique id of form.
     * @param int $courseid
     */
    public function __construct($uniqueid, $courseid) {
        global $PAGE;

        parent::__construct($uniqueid);

        $this->set_attribute('id', 'tool_roland04_general');
        $columns = array('completed', 'name', 'priority', 'description', 'timecreated', 'timemodified');
        if (has_capability('tool/roland04:edit', context_course::instance($courseid))) {
            $columns[] = 'actions';
            $this->no_sorting('actions');
        }
        $headers = array(
            get_string('completed', 'tool_roland04'),
            get_string('name', 'tool_roland04'),
            get_string('priority', 'tool_roland04'),
            get_string('description', 'tool_roland04'),
            get_string('timecreated', 'tool_roland04'),
            get_string('timemodified', 'tool_roland04'),
            get_string('actions')
        );

        $this->define_columns($columns);
        $this->define_headers($headers);
        $this->pageable(true);
        $this->collapsible(false);
        $this->sortable(true, 'timemodified', 'DESC');
        $this->no_sorting('description');
        $this->is_downloadable(false);
        $this->define_baseurl($PAGE->url);
        $this->set_sql('id, name, completed, priority, description, descriptionformat, timecreated,
                timemodified', '{tool_roland04}', 'courseid = :courseid', ['courseid' => $courseid]);
    }

    /**
     * Generates column name
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_name($row) {
        global $OUTPUT;

        $tmpl = new \core\output\inplace_editable('tool_roland04', 'todoname',
            $row->id, has_capability('tool/roland04:edit', context_system::instance()),
            $this->format_text($row->name),
            $row->name,
            get_string('editnamehint', 'tool_roland04'),
            get_string('editnamelabel', 'tool_roland04')
        );
        return $OUTPUT->render($tmpl);
    }

    /**
     * Generates column completed
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_completed($row) {
        global $OUTPUT;

        $tmpl = new \core\output\inplace_editable(
            'tool_roland04',
            'todocompleted',
            $row->id,
            has_capability('tool/roland04:edit', context_system::instance()),
            tool_roland04_api::print_completion_icon($row->completed),
            (int)$row->completed,
            get_string('editcompletedhint', 'tool_roland04')
        );
        $tmpl->set_type_toggle(array(0, 1));

        return $OUTPUT->render($tmpl);
    }

    /**
     * Generates column priority
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_priority($row) {
        global $OUTPUT;

        $tagcollections = [0 => 'Low', 1 => 'Medium', 2 => 'High'];
        $tmpl = new \core\output\inplace_editable(
            'tool_roland04',
            'todopriority',
            $row->id,
            has_capability('tool/roland04:edit', context_system::instance()),
            tool_roland04_api::print_priority_badge($row->priority),
            $row->priority,
            get_string('editpriorityhint', 'tool_roland04'),
            get_string('editprioritylabel', 'tool_roland04')
        );
        $tmpl->set_type_select($tagcollections);
        return $OUTPUT->render($tmpl);
    }

    /**
     * Generates column description
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_description($row) {
        global $PAGE;

        $textfieldoptions = tool_roland04_api::get_textfieldoptions();
        $description = file_rewrite_pluginfile_urls($row->description, 'pluginfile.php',
                $PAGE->context->id, 'tool_roland04', 'todo', $row->id, $textfieldoptions);

        return format_text($description, $row->descriptionformat, $textfieldoptions);
    }

    /**
     * Generates column timecreated
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_timecreated($row) {
        return userdate($row->timecreated, get_string('strftimedatetime'));
    }

    /**
     * Generates column timemodified
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_timemodified($row) {
        return userdate($row->timemodified, get_string('strftimedatetime'));
    }

    /**
     * Generates column actions
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_actions($row) {
        $edittext = get_string('edittodo', 'tool_roland04');
        $editicon = html_writer::tag('i', '',
                ['class' => 'icon fa fa-pencil todo-edit-link', 'title' => $edittext, 'aria-label' => $edittext]);
        $deletetext = get_string('deletetodo', 'tool_roland04');
        $deleteicon = html_writer::tag('i', '',
                ['class' => 'icon fa fa-trash todo-delete-link', 'title' => $deletetext, 'aria-label' => $deletetext]);

        $editlink = html_writer::link(new moodle_url('./edit.php', ['id' => $row->id]),
                $editicon,
                ['aria-label' => $edittext, 'data-action' => 'edittodo']
            );
        $deletelink = html_writer::link(new moodle_url('./edit.php', ['id' => $row->id, 'delete' => 1, 'sesskey' => sesskey()]),
                $deleteicon,
                ['aria-label' => $deletetext, 'data-action' => 'deletetodo', 'data-todoid' => $row->id]
            );
        return $editlink.$deletelink;
    }
}