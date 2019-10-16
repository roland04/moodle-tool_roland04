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
 * Backup
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/backup/moodle2/restore_tool_plugin.class.php');

/**
 * restore_tool_roland04_plugin
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_tool_roland04_plugin extends restore_tool_plugin {

    /**
     * Get plugin backup elements
     *
     * @return restore_path_element[]
     */
    protected function define_course_plugin_structure() {
        $paths = array();
        $elepath = $this->get_pathfor('/todo');
        $paths[] = new restore_path_element('tool_roland04', $elepath);
        return $paths;
    }

    /**
     * Add related files
     */
    public function after_execute_course() {
        $this->add_related_files('tool_roland04', 'todo', 'todo');
    }

    /**
     * Restore process
     *
     * @param stdClass $data
     */
    public function process_tool_roland04($data) {
        global $DB;

        $data = (object)$data;
        $previousid = $data->id;

        $data->courseid = $this->task->get_courseid();
        $data->timecreated = time();
        $data->timemodified = $data->timecreated;

        $data->id = $DB->insert_record('tool_roland04', $data);

        $this->set_mapping('todo', $previousid, $data->id, true);
    }
}