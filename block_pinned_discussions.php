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
 * Recent Blog Entries Block page.
 *
 * @package   block_pinned_discussions
 * @copyright 2020 Dylan Keys <dylan.keys95@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_pinned_discussions\local\pinned_discussions;

defined('MOODLE_INTERNAL') || die();

/**
 * This block outputs a list of links to pinned forum discussions from the current course
 */
class block_pinned_discussions extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_pinned_discussions');
        $this->content_type = BLOCK_TYPE_TEXT;
    }

    function applicable_formats() {
        return array('all' => true, 'my' => false, 'tag' => false);
    }

    function instance_allow_config() {
        return true;
    }

    function get_content() {
        global $CFG, $COURSE;

        if ($this->content !== NULL) {
            return $this->content;
        }

        if (empty($this->config)) {
            $this->config = new stdClass();
        }

        if (empty($this->config->numberofblogentries)) {
            $this->config->numberofblogentries = 4;
        }

        $this->content = new stdClass();
        $this->content->footer = '';
        $this->content->text = '';

        $context = $this->page->context;

        $discussionurl = new moodle_url('/mod/forum/discuss.php');

        $pinnedposts = pinned_discussions::fetch_pinned_discussions($COURSE->id);

        if (!empty($pinnedposts)) {
            $pinnedpostlist = array();

            foreach ($pinnedposts as $pinnedpost) {
                $discussionurl->param('d', $pinnedpost->id);
                $postlink = html_writer::link($discussionurl, shorten_text($pinnedpost->name));
                $pinnedpostlist[] = $postlink;
            }

            $this->content->text .= html_writer::alist($pinnedpostlist, array('class'=>'list'));
        }
        else {
            $this->content->text .= get_string('nopinneddiscussions', 'block_pinned_discussions');
        }
    }

    /**
     * Return the plugin config settings for external functions.
     *
     * @return stdClass the configs for both the block instance and plugin
     * @since Moodle 3.8
     */
    public function get_config_for_external() {
        // Return all settings for all users since it is safe (no private keys, etc..).
        $configs = !empty($this->config) ? $this->config : new stdClass();

        return (object) [
            'instance' => $configs,
            'plugin' => new stdClass(),
        ];
    }
}
