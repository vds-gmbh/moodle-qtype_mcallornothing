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
 * Install script for qtype_mcallornothing.
 *
 * Migrates questions and configuration from the predecessor plugin
 * qtype_multichoiceset if it is installed.
 *
 * @package    qtype_mcallornothing
 * @copyright  2026 onwards VdS Schadenverhütung
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Install hook: migrate data from qtype_multichoiceset if present.
 *
 * @return bool
 */
function xmldb_qtype_mcallornothing_install(): bool {
    global $DB;

    $dbman = $DB->get_manager();

    // Nothing to do if the predecessor was never installed.
    $source = new \xmldb_table('qtype_multichoiceset_options');
    if (!$dbman->table_exists($source)) {
        return true;
    }

    mtrace('qtype_mcallornothing: detected qtype_multichoiceset, migrating data...');

    $copied = qtype_mcallornothing_migrate_options($DB);
    $retyped = qtype_mcallornothing_migrate_question_qtype($DB);
    $configs = qtype_mcallornothing_migrate_plugin_config($DB);
    $files = qtype_mcallornothing_migrate_files($DB);

    mtrace("qtype_mcallornothing: migrated {$copied} option rows, " .
        "retyped {$retyped} questions, moved {$configs} config settings and {$files} files.");

    return true;
}

/**
 * Copy rows from qtype_multichoiceset_options to qtype_mcallornothing_options.
 *
 * Existing rows in the destination (matched by questionid) are left untouched
 * so the migration is idempotent if the install script is ever re-run.
 *
 * @param \moodle_database $db
 * @return int Number of rows inserted.
 */
function qtype_mcallornothing_migrate_options(\moodle_database $db): int {
    $existing = $db->get_fieldset_select('qtype_mcallornothing_options', 'questionid', '1=1');
    $existing = array_flip($existing);

    $records = $db->get_recordset('qtype_multichoiceset_options');
    $batch = [];
    $batchsize = 1000;
    $inserted = 0;

    foreach ($records as $record) {
        if (isset($existing[$record->questionid])) {
            continue;
        }
        unset($record->id);
        $batch[] = $record;

        if (count($batch) >= $batchsize) {
            $db->insert_records('qtype_mcallornothing_options', $batch);
            $inserted += count($batch);
            $batch = [];
        }
    }

    if (!empty($batch)) {
        $db->insert_records('qtype_mcallornothing_options', $batch);
        $inserted += count($batch);
    }

    $records->close();

    return $inserted;
}

/**
 * Rebrand existing questions: set question.qtype = 'mcallornothing' where it is 'multichoiceset'.
 *
 * @param \moodle_database $db
 * @return int Number of questions updated.
 */
function qtype_mcallornothing_migrate_question_qtype(\moodle_database $db): int {
    $count = $db->count_records('question', ['qtype' => 'multichoiceset']);
    if ($count === 0) {
        return 0;
    }

    $db->set_field('question', 'qtype', 'mcallornothing', ['qtype' => 'multichoiceset']);

    return $count;
}

/**
 * Copy plugin-level settings from qtype_multichoiceset to qtype_mcallornothing.
 *
 * Settings already defined for qtype_mcallornothing are not overwritten.
 *
 * @param \moodle_database $db
 * @return int Number of settings copied.
 */
function qtype_mcallornothing_migrate_plugin_config(\moodle_database $db): int {
    $oldconfig = $db->get_records('config_plugins', ['plugin' => 'qtype_multichoiceset']);
    $copied = 0;

    foreach ($oldconfig as $setting) {
        $exists = $db->record_exists('config_plugins', [
            'plugin' => 'qtype_mcallornothing',
            'name' => $setting->name,
        ]);

        if (!$exists) {
            set_config($setting->name, $setting->value, 'qtype_mcallornothing');
            $copied++;
        }
    }

    return $copied;
}

/**
 * Re-component files stored under the old plugin so they continue to resolve.
 *
 * @param \moodle_database $db
 * @return int Number of file records re-componented.
 */
function qtype_mcallornothing_migrate_files(\moodle_database $db): int {
    $count = $db->count_records('files', ['component' => 'qtype_multichoiceset']);
    if ($count === 0) {
        return 0;
    }

    $db->set_field('files', 'component', 'qtype_mcallornothing', ['component' => 'qtype_multichoiceset']);

    return $count;
}
