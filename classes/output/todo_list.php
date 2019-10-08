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
 * Todo list page
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_roland04\output;

use context_course;
use moodle_url;
use renderer_base;
use stdClass;

defined('MOODLE_INTERNAL') || die();

/**
 * Class todo_list
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class todo_list implements \renderable, \templatable {

    /**
     * models
     *
     * @var int $courseid
     */
    protected $courseid;

    /**
     * __construct
     *
     * @param int $courseid
     * @return void
     */
    public function __construct(int $courseid) {
        $this->courseid = $courseid;
    }

    /**
     * Exports the data.
     *
     * @param renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $data = new stdClass();
        $context = context_course::instance($this->courseid);

        // Generate "Add TODO" button.
        if (has_capability('tool/roland04:edit', $context)) {
            $addbutton = $output->single_button(new moodle_url('./edit.php',
                ['courseid' => $this->courseid]),
                get_string('addtodo', 'tool_roland04'),
                'get');
            $data->button = $addbutton;
        }

        // Generate the table.
        $todotable = new \tool_roland04_table('tool_roland04', $this->courseid);
        ob_start();
        $todotable->out(25,false);
        $data->table = ob_get_clean();

        return $data;
    }
}