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
 * tool_roland04 upgrade script.
 *
 * @package    tool_roland04
 * @copyright  2019 Mikel Martín
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Run all upgrade steps between the current DB version and the current version on disk.
 *
 * @param int $oldversion The old version of tool roland04 in the DB.
 * @return bool
 */
 function xmldb_tool_roland04_upgrade(int $oldversion){
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2019100100) {

        // Define table tool_roland04 to be created.
        $table = new xmldb_table('tool_roland04');

        // Adding fields to table tool_roland04.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('completed', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('priority', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table tool_roland04.
        $table->add_key('id', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for tool_roland04.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Roland04 savepoint reached.
        upgrade_plugin_savepoint(true, 2019100100, 'tool', 'roland04');
    }

    if ($oldversion < 2019100101) {

        // Define key courseid (foreign) to be added to tool_roland04.
        $table = new xmldb_table('tool_roland04');
        $key = new xmldb_key('courseid', XMLDB_KEY_FOREIGN, ['courseid'], 'course', ['id']);

        // Launch add key courseid.
        $dbman->add_key($table, $key);

        // Define index courseidname (unique) to be added to tool_roland04.
        $table = new xmldb_table('tool_roland04');
        $index = new xmldb_index('courseidname', XMLDB_INDEX_UNIQUE, ['courseid', 'name']);

        // Conditionally launch add index courseidname.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Roland04 savepoint reached.
        upgrade_plugin_savepoint(true, 2019100101, 'tool', 'roland04');
    }

 }