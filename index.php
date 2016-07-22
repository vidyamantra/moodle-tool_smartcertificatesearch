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
 * Process for search certificate
 *
 * File         smartcertificatesearch.php
 * Encoding     UTF-8
 *
 * @package     tool_smartcertificatesearch
 *
 * @copyright   Vidya Mantra EduSystems Pvt. Ltd.
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once("$CFG->dirroot/admin/tool/smartcertificatesearch/locallib.php");
require_once($CFG->dirroot . '/' . $CFG->admin . '/tool/smartcertificatesearch/smartcertificatesearch_form.php');
$delete = optional_param('delete', 0, PARAM_INT);
$confirm = optional_param('confirm', '', PARAM_ALPHANUM);
require_login();
$sitecontext = context_system::instance();
$site = get_site();
admin_externalpage_setup('toolsmartcertificatesearch');


// Delete Certificate record from certificate Issued table sothat user can download updated certificate.

$returnurl = new moodle_url('/admin/tool/smartcertificatesearch/index.php');

if ($delete and confirm_sesskey()) {
    $name = smartcertificatesearch_find_username($delete);
    // Delete certificate record , after confirmation.
    if ($confirm != md5($delete)) {
        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('delete', 'tool_smartcertificatesearch', "'$name'"));
        $optionsyes = array('delete' => $delete, 'confirm' => md5($delete), 'sesskey' => sesskey());
        echo $OUTPUT->confirm(get_string('deletecheck', 'tool_smartcertificatesearch', "'$name'"), new moodle_url($returnurl, $optionsyes), $returnurl);
        echo $OUTPUT->footer();
        die;
    } else if (data_submitted()) {
        global $DB;
        if (!empty($delete)) {
            $DB->delete_records('smartcertificate_issues', array('id' => $delete));
            \core\session\manager::gc(); // Remove stale sessions.
            redirect($returnurl);
        } else {
            \core\session\manager::gc(); // Remove stale sessions.
            echo $OUTPUT->notification($returnurl, get_string('deletednot', 'tool_smartcertificatesearch'));
        }
    }
}
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('smartcertificatesearch', 'tool_smartcertificatesearch'));

$mform = new tool_smartcertificatesearch_config_form();
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/admin/tool/smartcertificatesearch/index.php'));
} else if ($fromform = $mform->get_data()) {

    $userinput = $fromform->userinput;
}
echo $mform->display();

if (!empty($userinput)) {
    $strdelete = get_string('delete');
    $sql = "SELECT ci.id As id,ci.userid As userid,ci.code AS code,ci.smartcertificateid As smartcertificateid,ci.timecreated As timecreated, u.username As username, u.firstname As firstname, u.lastname As lastname, u.email AS email
    FROM
        {smartcertificate_issues} ci
    INNER JOIN
        {user} u
    ON u.id = ci.userid where (u.username = '$userinput') OR (concat(u.firstname,' ',lastname)  = '$userinput') OR (ci.code = '$userinput')";

    $record = $DB->get_records_sql($sql);
    $table = new html_table();
    $table->head = array('Student Name', 'E-mail', 'Certificate code', 'Certificate Received Date', 'Student Certificate', 'Action');
    foreach ($record as $records) {
        $cm = smartcertificatesearch_cm_id($records->smartcertificateid, $records->code);
        $cm = get_coursemodule_from_id('smartcertificate', $cm);
        $context = context_module::instance($cm->id);
        $sudentname = $records->firstname . " " . $records->lastname;
        $email = $records->email;
        $code = $records->code;
        $date = new DateTime("@$records->timecreated");
        $certificatereceiveddate = $date->format('Y-m-d');
        $id = $records->id;
        $certificatelink = smartcertificatesearch_print_user_files($records->smartcertificateid, $records->userid, $context->id);
        $link = html_writer::link(new moodle_url('/admin/tool/smartcertificatesearch/index.php',
            array('delete' => $id, 'sesskey' => sesskey())),
            html_writer::empty_tag('img', array('src' => $OUTPUT->pix_url('t/delete'), 'alt' => $strdelete, 'class' => 'iconsmall')),
            array('title' => $strdelete));
        $table->data[] = array($sudentname, $email, $code, $certificatereceiveddate, $certificatelink, $link);
    }
    if (!empty($record)) {
        echo html_writer::table($table);
    } else {
        echo "Please provide valid information";
    }
}
echo $OUTPUT->footer();

