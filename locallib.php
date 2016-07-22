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
 * smart certificate search module internal API,
 * this is in separate file to reduce memory use on non-smartcertificate pages.
 *
 * @package    tool_smartcertificatesearch
 * @copyright  Vidya Mantra EduSystems Pvt. Ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Produces links to the issued certificates of student.
 *
 * @param stdClass $smartcertificate
 * @param int $userid
 * @param int $contextid
 * @return string return the user files
 */
function smartcertificatesearch_print_user_files($smartcertificate, $userid, $contextid) {
    global $CFG, $DB, $OUTPUT;

    $output = '';

    $certrecord = $DB->get_record('smartcertificate_issues', array('userid' => $userid, 'smartcertificateid' => $smartcertificate));
    $fs = get_file_storage();

    $component = 'mod_smartcertificate';
    $filearea = 'issue';
    $files = $fs->get_area_files($contextid, $component, $filearea, $certrecord->id);
    foreach ($files as $file) {
        $filename = $file->get_filename();
        $link = file_encode_url($CFG->wwwroot . '/pluginfile.php', '/' . $contextid . '/mod_smartcertificate/issue/' . $certrecord->id . '/' . $filename);

        $output = '<img src="' . $OUTPUT->pix_url(file_mimetype_icon($file->get_mimetype())) . '" height="16" width="16" alt="' . $file->get_mimetype() . '" />&nbsp;' .
                '<a href="' . $link . '" >' . s($filename) . '</a>';
    }
    $output .= '<br />';
    $output = '<div class="files">' . $output . '</div>';

    return $output;
}
// Find course module id.
function smartcertificatesearch_cm_id($id, $code) {
    global $DB;
    $sql = "SELECT ci.smartcertificateid As smartcertificateid,ci.code AS code,nc.course As course,m.id As id
    FROM
        {smartcertificate_issues} ci
    INNER JOIN
        {smartcertificate} nc
    INNER JOIN
        {modules} m
    ON nc.id = ci.smartcertificateid where smartcertificateid = $id and code = '$code' And m.name = 'smartcertificate'";
    $rec = $DB->get_records_sql($sql);
    foreach ($rec as $records) {
        $course = $records->course;
        $moduleid = $records->id;
    }
    $cmid = $DB->get_field('course_modules','id', array('course' => $course, 'module' => $moduleid, 'instance' => $id));
    
    return $cmid;
}
// Find Full Name of User.
function smartcertificatesearch_find_username($id) {
    global $DB;
    $sql = "SELECT ci.id As id,u.firstname As firstname,u.lastname As lastname
    FROM
        {smartcertificate_issues} ci
    INNER JOIN
        {user} u
        ON ci.userid = u.id where ci.id = $id";
    $record = $DB->get_records_sql($sql);
    foreach ($record as $records) {
        $sudentname = $records->firstname . " " . $records->lastname;
    }
    return $sudentname;
}
