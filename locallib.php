<?php

require_once("$CFG->libdir/formslib.php");

class downloadlog_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG,$DB,$COURSE;
        $mform = $this->_form;
        
        $mform->addElement('date_time_selector', 'starttime', get_string('reportstart', 'tool_downloadlog'),array('optional'=>false,'startyear' => 2000, 'stopyear' => date("Y"),'step' => 5));

        $mform->addElement('date_time_selector', 'endtime', get_string('reportend', 'tool_downloadlog'),array('optional'=>false,'startyear' => 2000, 'stopyear' => date("Y"),'step' => 5));
        
        $mform->addElement('submit', 'download', get_string('download','tool_downloadlog'));
    }
}

function col_eventname($event) {
    // Event name.
    if (logstore_standard\log\store instanceof logstore_legacy\log\store) {
        // Hack for support of logstore_legacy.
        $eventname = $event->eventname;
    } else {
        $eventname = $event->get_name();
    }
    return $eventname;
}
