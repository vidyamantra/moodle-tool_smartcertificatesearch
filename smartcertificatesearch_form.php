<?php
// This file is part of the Smart Certificate Search module for Moodle - http://moodle.org/
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
 * ADD Smart Certificate Search
 *
 * @package    tool_smartcertificatesearch
 * @copyright  Vidya Mantra EduSystems Pvt. Ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot . '/user/editlib.php');


class tool_smartcertificatesearch_config_form extends moodleform {

    public function definition() {
        global $CFG;

        $mform = & $this->_form;

        $mform->addElement('text', 'userinput', get_string('userinput', 'tool_smartcertificatesearch'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('userinput', PARAM_TEXT);
        } else {
            $mform->setType('userinput', PARAM_CLEANHTML);
        }
        $mform->setDefault('userinput', '');
        $mform->addHelpButton('userinput', 'userinput', 'tool_smartcertificatesearch');
        $this->add_action_buttons();
    }

}
