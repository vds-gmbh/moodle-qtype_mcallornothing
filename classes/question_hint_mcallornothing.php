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
 * An extension of question_hint_with_parts for mcallornothing questions.
 *
 * This has an extra option for whether to show the feedback for each choice.
 *
 * @package    qtype_mcallornothing
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @copyright  2010 The Open University
 * @copyright  2026 onwards VdS Schadenverhütung
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace qtype_mcallornothing;

use question_display_options;
use question_hint_with_parts;

/**
 * An extension of question_hint_with_parts for mcallornothing questions.
 *
 * This has an extra option for whether to show the feedback for each choice.
 *
 * @package    qtype_mcallornothing
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @copyright  2010 The Open University
 * @copyright  2026 onwards VdS Schadenverhütung
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class question_hint_mcallornothing extends question_hint_with_parts {
    /** @var bool whether to show the feedback for each choice. */
    public $showchoicefeedback;

    /**
     * Constructor.
     *
     * @param int $id Question ID
     * @param string $hint The hint text
     * @param int $hintformat
     * @param bool $shownumcorrect whether the number of right parts should be shown
     * @param bool $clearwrong whether the wrong parts should be reset.
     * @param bool $showchoicefeedback whether to show the feedback for each choice.
     */
    public function __construct(
        $id,
        $hint,
        $hintformat,
        $shownumcorrect,
        $clearwrong,
        $showchoicefeedback
    ) {
        parent::__construct($id, $hint, $hintformat, $shownumcorrect, $clearwrong);
        $this->showchoicefeedback = $showchoicefeedback;
    }

    /**
     * Create a basic hint from a row loaded from the question_hints table in the database.
     *
     * @param object $row with $row->hint, ->shownumcorrect and ->clearwrong set.
     * @return question_hint_mcallornothing
     */
    public static function load_from_record($row) {
        return new question_hint_mcallornothing(
            $row->id,
            $row->hint,
            $row->hintformat,
            $row->shownumcorrect,
            $row->clearwrong,
            !empty($row->options)
        );
    }

    /**
     * Adjust the display options.
     *
     * @param question_display_options $options display options
     * @return void
     */
    public function adjust_display_options(question_display_options $options) {
        parent::adjust_display_options($options);
        $options->suppresschoicefeedback = !$this->showchoicefeedback;
    }
}
