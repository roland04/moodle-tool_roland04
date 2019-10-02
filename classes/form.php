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
 * Class tool_roland04_form
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

/**
 * Class tool_roland04_form
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_roland04_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        $mform->addElement('text', 'name', get_string('name'));
        $mform->setType('name', PARAM_NOTAGS);

        $mform->addElement('advcheckbox', 'completed', get_string('completed', 'tool_roland04'));
        $mform->setType('completed', PARAM_NOTAGS);
        $mform->setDefault('completed', 0);

        $priorityoptions = [get_string('priority0', 'tool_roland04'), get_string('priority1', 'tool_roland04'), get_string('priority2', 'tool_roland04')];
        $mform->addElement('select', 'priority', get_string('priority', 'tool_roland04'), $priorityoptions);

        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons();
    }

    /**
     * Form validation
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    function validation($data, $files) {
        global $DB;

        $errors = parent::validation($data, $files);

        $select = 'id != :id AND name = :name AND courseid = :courseid';
        $params = ['id' => $data['id'], 'name' => $data['name'], 'courseid' => $data['courseid']];
        if ($DB->record_exists_select('tool_roland04', $select, $params)) {
            $errors['name'] = get_string('errornameexists', 'tool_roland04');
        }
        return $errors;
    }
}