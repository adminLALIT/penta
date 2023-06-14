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
 * Run the code checker from the web.
 *
 * @package    local_team_coach
 * @copyright  2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once '../../config.php';
require "$CFG->libdir/tablelib.php";
require_once('lib.php');
require_once("menu_list_mform.php");
require "banner_list_form.php";

$return = new moodle_url('/local/team_coach/banner_list.php');

require_login();
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot . '/local/team_coach/banner_list.php');
$PAGE->set_title('Manage Banner');
$PAGE->set_heading('Banner List');
$PAGE->set_pagelayout('admin');
$PAGE->navbar->add('Manage theme', new moodle_url('/local/team_coach/team_list.php'));
$PAGE->navbar->add('Manage Banner ', new moodle_url('/local/team_coach/banner_list.php'));
$PAGE->navbar->add('Banner List');
echo $OUTPUT->header();
echo $OUTPUT->heading('Banner List');
if (optional_param('cancel', false, PARAM_BOOL)) {
    redirect(new moodle_url('/local/team_coach/banner_list.php'));
}

$table = new banner_list_form('uniqueid');
$mform = new menu_list_mform();
if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . '/local/team_coach/banner_list.php');
    // Handle form cancel operation, if cancel button is present on form.
} else if ($fromform = $mform->get_data()) {
if ($fromform->theme_id) {
    $where = 'td.id = '.$fromform->theme_id.'';
}
else {
    $where = '1=1';
}
}
else {
    $where = '1=1';
}

echo html_writer::start_tag('div', ['id'=>'buttonid', 'style'=>'float:right;']);
echo html_writer::link($CFG->wwwroot.'/local/team_coach/frontpage.php', 'Add Banner', ['class'=>'btn btn-secondary']);
echo html_writer::end_tag('div');
echo '<br><br>';
$field = 'tb.*, td.name';
$from = '{theme_banner} tb JOIN {theme_detail} td ON td.id = tb.theme_id';
// Work out the sql for the table.
$table->set_sql($field, $from, $where);
$table->define_baseurl("$CFG->wwwroot/local/team_coach/banner_list.php");
$mform->display();
$table->out(10, true);
echo $OUTPUT->footer();
