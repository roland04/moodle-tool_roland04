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
 * API tests.
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Class tool_roland04_api_testcase
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_roland04_api_testcase extends advanced_testcase {

    protected function setUp() {
        parent::setUp();
        $this->resetAfterTest();
    }

    /**
     * Test for tool_roland04::create_todo and tool_roland04::get_todo
     */
    public function test_create_todo() {
        // Create a course.
        $course = $this->getDataGenerator()->create_course();

        // Create the TODO.
        $data = new stdClass();
        $data->courseid = $course->id;
        $data->name = 'todo1';
        $data->priority = 0;
        $data->completed = 0;
        $data->description = 'desc';
        $todoid = tool_roland04_api::create_todo($data);

        // Get the TODO.
        $todo = tool_roland04_api::get_todo($todoid);

        // Test if TODO was created correctly.
        $this->assertEquals($todo->courseid, $course->id);
        $this->assertEquals($todo->name, $data->name);
        $this->assertEquals($todo->priority, $data->priority);
        $this->assertEquals($todo->completed, $data->completed);
        $this->assertEquals($todo->description, $data->description);
    }

    /**
     * Test for tool_roland04::update_todo
     */
    public function test_update_todo() {
        // Create a course.
        $course = $this->getDataGenerator()->create_course();

        // Create the TODO.
        $data = new stdClass();
        $data->courseid = $course->id;
        $data->name = 'todo1';
        $todoid = tool_roland04_api::create_todo($data);

        // Update the TODO.
        tool_roland04_api::update_todo((object)['id' => $todoid, 'name' => 'todo2', 'completed' => 1, 'description' => 'desc2']);

        // Get the TODO.
        $todo = tool_roland04_api::get_todo($todoid);

        // Test if TODO was created correctly.
        $this->assertEquals($todo->name, 'todo2');
        $this->assertEquals($todo->completed, 1);
        $this->assertEquals($todo->description, 'desc2');
    }

    /**
     * Test for tool_roland04_api::delete_todo
     */
    public function test_delete_todo() {
        // Create a course.
        $course = $this->getDataGenerator()->create_course();

        // Create the TODO.
        $data = new stdClass();
        $data->courseid = $course->id;
        $data->name = 'todo1';
        $todoid = tool_roland04_api::create_todo($data);

        // Delete the TODO.
        tool_roland04_api::delete_todo($todoid);

        // Get the TODO.
        $todo = tool_roland04_api::get_todo($todoid, IGNORE_MISSING);

        // Test if TODO was deleted correctly.
        $this->assertEmpty($todo);
    }
}