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
        $columns = array('completed', 'name', 'priority', 'timecreated', 'timemodified');
        if (has_capability('tool/roland04:edit', context_course::instance($courseid))) {
            $columns[] = 'actions';
        }
        $headers = array(
            '',
            get_string('name', 'tool_roland04'),
            get_string('priority', 'tool_roland04'),
            get_string('timecreated', 'tool_roland04'),
            get_string('timemodified', 'tool_roland04'),
            ''
        );

        $this->define_columns($columns);
        $this->define_headers($headers);
        $this->pageable(true);
        $this->collapsible(false);
        $this->sortable(false);
        $this->is_downloadable(false);
        $this->define_baseurl($PAGE->url);
        $this->set_sql('id, name, completed, priority, timecreated, timemodified', '{tool_roland04}', 'courseid = :courseid',
                ['courseid' => $courseid]);
    }

    /**
     * Generates column name
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_name($row) {
        return format_string($row->name);
    }

    /**
     * Generates column completed
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_completed($row) {
        return tool_roland04_api::print_completion_icon($row->completed);
    }

    /**
     * Generates column priority
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_priority($row) {
        return tool_roland04_api::print_priority_badge($row->priority);
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
        $editlink = html_writer::link(new moodle_url('./edit.php', ['id' => $row->id]), 
                html_writer::tag('i', '', ['class' => 'icon fa fa-pencil']));
        $deletelink = html_writer::link(new moodle_url('./edit.php', ['delete' => $row->id]), 
                html_writer::tag('i', '', ['class' => 'icon fa fa-trash']));
        return $editlink.$deletelink;
    }
}