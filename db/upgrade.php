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
 * Upgrade steps for qtype_mcallornothing.
 *
 * @package    qtype_mcallornothing
 * @copyright  2026 onwards VdS Schadenverhütung
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade the plugin database schema.
 *
 * @param int $oldversion the version we are upgrading from.
 * @return bool
 */
function xmldb_qtype_mcallornothing_upgrade(int $oldversion): bool {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2026041500) {
        // Add the grace column used by the built-in partial marking. Default 0
        // (strict all-or-nothing) matches the upstream qtype_multichoiceset
        // semantics; enabling grace on an existing question is an opt-in.
        $table = new xmldb_table('qtype_mcallornothing_options');
        $field = new xmldb_field(
            'grace',
            XMLDB_TYPE_INTEGER,
            '2',
            null,
            XMLDB_NOTNULL,
            null,
            '0',
            'showstandardinstruction'
        );

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_plugin_savepoint(true, 2026041500, 'qtype', 'mcallornothing');
    }

    return true;
}
