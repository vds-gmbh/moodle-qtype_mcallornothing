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
 * Combinable type class for qtype_mcallornothing (qtype_combined integration).
 *
 * @package    qtype_mcallornothing
 * @copyright  2019 Jean-Michel Vedrine
 * @copyright  2026 onwards VdS Schadenverhütung
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Defines the combinable type for the mcallornothing question type.
 *
 * @copyright  2019 Jean-Michel Vedrine
 * @copyright  2026 onwards VdS Schadenverhütung
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_combined_combinable_type_mcallornothing extends qtype_combined_combinable_type_base {
    /** @var string Name of identifier */
    protected $identifier = 'allornothing';

    /**
     * Get the extra question properties.
     *
     * @return array containing the numbering format
     */
    protected function extra_question_properties() {
        return ['answernumbering' => 'abc'] + $this->combined_feedback_properties();
    }

    /**
     * Get the extra answer properties.
     *
     * @return array containing the answer properties
     */
    protected function extra_answer_properties() {
        return ['feedback' => ['text' => '', 'format' => FORMAT_PLAIN]];
    }

    /**
     * Get the subquestion option fields.
     *
     * @return array containing the options
     */
    public function subq_form_fragment_question_option_fields() {
        return ['shuffleanswers' => false];
    }

    /**
     * Process the subquestion data.
     *
     * @param array $subqdata Subquestion data
     * @return array containing the answer properties
     */
    protected function transform_subq_form_data_to_full($subqdata) {
        $data = parent::transform_subq_form_data_to_full($subqdata);
        foreach ($data->answer as $anskey => $answer) {
            $data->answer[$anskey] = ['text' => $answer['text'], 'format' => $answer['format']];
        }
        return $this->add_per_answer_properties($data);
    }

    /**
     * Get the extra parameters for default.
     *
     * @return string
     */
    protected function third_param_for_default_question_text() {
        return 'v';
    }
}
