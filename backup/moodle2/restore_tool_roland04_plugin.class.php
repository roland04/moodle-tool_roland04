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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/backup/moodle2/restore_tool_plugin.class.php');

class restore_tool_roland04_plugin extends restore_tool_plugin {
    protected function define_course_plugin_structure() {
        $paths = array();
        $elepath = $this->get_pathfor('/todo');
        $paths[] = new restore_path_element('tool_roland04', $elepath);
        return $paths;
    }

    public function after_execute_course() {
        $this->add_related_files('tool_roland04', 'todo', 'todo');
    }

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