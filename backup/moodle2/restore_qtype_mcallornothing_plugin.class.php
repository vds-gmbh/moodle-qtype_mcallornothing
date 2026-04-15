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
 * Restore handler for Moodle 2.x/3.x Multichoiceset questions
 *
 * @package    qtype_mcallornothing
 * @copyright  2026 onwards VdS Schadenverhütung
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Restore plugin class that provides the information needed to restore one mcallornothing qtype.
 *
 * @package    qtype_mcallornothing
 * @copyright  2026 onwards VdS Schadenverhütung
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_qtype_mcallornothing_plugin extends restore_qtype_plugin {

    /**
     * Returns the paths to be handled by the plugin at question level
     */
    protected function define_question_plugin_structure() {

        $paths = [];

        // This qtype uses question_answers, add them.
        $this->add_question_question_answers($paths);

        // Add own qtype stuff.
        $elename = 'mcallornothing';
        // We used get_recommended_name() so this works.
        $elepath = $this->get_pathfor('/mcallornothing');
        $paths[] = new restore_path_element($elename, $elepath);

        return $paths; // And we return the interesting paths.
    }

    /**
     * Process the qtype/mcallornothing element
     *
     * @param stdObject $data question data
     * @return void
     */
    public function process_mcallornothing($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        // Detect if the question is created or mapped.
        $oldquestionid   = $this->get_old_parentid('question');
        $newquestionid   = $this->get_new_parentid('question');
        $questioncreated = (bool) $this->get_mappingid('question_created', $oldquestionid);

        // If the question has been created by restore, we need to create its
        // qtype_mcallornothing_options too.
        if ($questioncreated) {
            // Adjust some columns.
            $data->questionid = $newquestionid;

            // It is possible for old backup files to contain unique key violations.
            // We need to check to avoid that.
            if (!$DB->record_exists('qtype_mcallornothing_options', ['questionid' => $data->questionid])) {
                $newitemid = $DB->insert_record('qtype_mcallornothing_options', $data);
                $this->set_mapping('qtype_mcallornothing_options', $oldid, $newitemid);
            }
        }
    }

    /**
     * Recode the response to the question
     *
     * @param int $questionid Question ID
     * @param int $sequencenumber Sequence number of question (or attempt?)
     * @param array $response re-ordered response
     */
    public function recode_response($questionid, $sequencenumber, array $response) {
        if (array_key_exists('_order', $response)) {
            $response['_order'] = $this->recode_choice_order($response['_order']);
        }
        return $response;
    }

    /**
     * Recode the choice order as stored in the response.
     *
     * @param string $order the original order.
     * @return string the recoded order.
     */
    protected function recode_choice_order($order) {
        $neworder = [];
        foreach (explode(',', $order) as $id) {
            if ($newid = $this->get_mappingid('question_answer', $id)) {
                $neworder[] = $newid;
            }
        }
        return implode(',', $neworder);
    }

    /**
     * Return the contents of this qtype to be processed by the links decoder
     */
    public static function define_decode_contents() {

        $contents = [];

        $fields = ['correctfeedback', 'incorrectfeedback'];
        $contents[] = new restore_decode_content('qtype_mcallornothing_options',
                $fields, 'qtype_mcallornothing_options');

        return $contents;
    }
}
