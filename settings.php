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
 * Link to downloadlog.
 *
 * For now keep in Reports folder, we should move it elsewhere once we deal with contexts in general reports and navigation
 *
 * @package    tool
 * @subpackage downloadlog
 * @copyright  2019 Takahiro Nakahara <nakahara@3strings.co.jp
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $ADMIN->add('reports', new admin_externalpage('tooldownloadlog', get_string('pluginname', 'tool_downloadlog'), "$CFG->wwwroot/$CFG->admin/tool/downloadlog/index.php", 'moodle/site:config'));
}
