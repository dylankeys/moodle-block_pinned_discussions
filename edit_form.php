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
 * Form for editing pinned discussion block instances.
 *
 * @package   block_pinned_discussions
 * @copyright 2020 Dylan Keys <dylan.keys95@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing tag block instances.
 *
 * @package   block_pinned_discussions
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_pinned_discussions_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $numberofentries = array();
        for ($i = 1; $i <= 20; $i++) {
            $numberofentries[$i] = $i;
        }

        $mform->addElement('select', 'config_numberofblogentries', get_string('numentriestodisplay', 'block_pinned_discussions'), $numberofentries);
        $mform->setDefault('config_numberofrecentblogentries', 4);
    }
}
