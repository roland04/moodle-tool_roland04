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
 * Class tool_roland04_events_testcase
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_roland04_events_testcase extends advanced_testcase {

    /**
     * Set up the test
     */
    protected function setUp() {
        parent::setUp();
        $this->resetAfterTest();
    }

    /**
     * Test TODO creation event
     */
    public function test_todo_created() {
        $course = $this->getDataGenerator()->create_course();
        $sink = $this->redirectEvents();
        $todoid = tool_roland04_api::create_todo((object)[
            'courseid' => $course->id,
            'name' => 'TODO-01'
        ]);

        $events = $sink->get_events();
        $this->assertCount(1, $events);

        $event = reset($events);
        $this->assertInstanceOf('\tool_roland04\event\todo_created', $event);
        $this->assertEquals($event->objectid, $todoid);
        $this->assertEquals($event->courseid, $course->id);
    }

    // TODO: update and delete tests
}