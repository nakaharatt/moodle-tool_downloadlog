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
 * @package    tool
 * @subpackage downloadlog
 * @copyright  2019 Takahiro Nakahara <nakahara@3strings.co.jp
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../config.php');
require_once('locallib.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir . '/csvlib.class.php');

$url = new moodle_url("/admin/tool/downloadlog/index.php");

$PAGE->set_url('/admin/tool/downloadlog/index.php');
$PAGE->set_pagelayout('report');
require_login();
$context = context_system::instance();
$PAGE->set_context($context);
require_capability('report/log:view', $context);

$stradministration = get_string('administration');
$strreports = get_string('reports');

$mform = new downloadlog_form;
$fromform = $mform->get_data();

if($fromform){
    $sql = 'SELECT * from {logstore_standard_log} WHERE timecreated > '.$fromform->starttime.' AND timecreated < '.$fromform->endtime;
    $logdata = $DB->get_records_sql($sql);
    $csvexport = new \csv_export_writer();
    $filename = 'moodle-all-log';
    $csvexport->set_filename($filename);
    $csvexport->add_data(array('Course','Date','IP','User','Department','Action'));
    $sql = 'SELECT * FROM {user}';
    $users = $DB->get_records_sql($sql);
    if ($courserecords = $DB->get_records("course", null, "fullname", "id,shortname,fullname,category")) {
        foreach ($courserecords as $course) {
            if ($course->id == SITEID) {
                $courses[$course->id] = format_string($course->fullname) . ' (' . get_string('site') . ')';
                $courses[0] = format_string($course->fullname) . ' (' . get_string('site') . ')';
            } else {
                $courses[$course->id] = format_string(get_course_display_name_for_list($course));
            }
        }
    }
    foreach($logdata as $log){
        if($log->userid){
            $data = array();
            $data[] = $courses[$log->courseid];
            $data[] = userdate($log->timecreated);
            $data[] = $log->ip;
            $data[] = fullname($users[$log->userid]);
            $data[] = $users[$log->userid]->department;
            $eventname = $log->eventname::get_name_with_info();
            $data[] = $eventname;
            $line = implode(',',$data);
            $csvexport->add_data($data);
        }
    }
    $csvexport->download_file();
}

admin_externalpage_setup('tooldownloadlog');
$PAGE->set_title(get_string('pluginname','tool_downloadlog'));
$output = $PAGE->get_renderer('report_log');
echo $output->header();
echo $output->heading(get_string('pluginname', 'tool_downloadlog'));

echo '<p>';
print_string('pleasesetdate','tool_downloadlog');
echo '</p>';

$mform->display();

echo $output->footer();
