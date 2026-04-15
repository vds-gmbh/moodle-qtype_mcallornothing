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
 * Mobile app addon definition for qtype_mcallornothing.
 *
 * @package    qtype_mcallornothing
 * @copyright  2018 Jean-Michel Vedrine
 * @copyright  2026 onwards VdS Schadenverhütung
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$addons = [
    'qtype_mcallornothing' => [
        'handlers' => [
            'mcallornothing' => [
                'displaydata' => [
                    'title' => 'All-or-Nothing Multiple Choice question',
                    'icon' => $CFG->wwwroot . '/question/type/mcallornothing/pix/icon.gif',
                    'class' => '',
                ],
                'delegate' => 'CoreQuestionDelegate',
                'method' => 'mobile_get_mcallornothing',
                'offlinefunctions' => [
                    'mobile_get_mcallornothing' => [],
                ],
            ],
        ],
        'lang' => [
            ['pluginname', 'qtype_mcallornothing'],
        ],
    ],
];
