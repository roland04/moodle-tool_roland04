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

require_once($CFG->dirroot . '/backup/moodle2/backup_tool_plugin.class.php');

class backup_tool_roland04_plugin extends backup_tool_plugin {

    protected function define_course_plugin_structure() {
        $plugin = $this->get_plugin_element();

        $pluginwrapper = new backup_nested_element($this->get_recommended_name());
        $plugin->add_child($pluginwrapper);
        $todo = new backup_nested_element('todo', array('id'), array(
            'courseid', 'name', 'completed', 'priority', 'timecreated', 'timemodified', 'description', 'descriptionformat'));
        $pluginwrapper->add_child($todo);

        $todo->set_source_table('tool_roland04', array('courseid' => backup::VAR_COURSEID));

        $todo->annotate_files('tool_roland04', 'todo', 'id');

        return $plugin;
    }
}