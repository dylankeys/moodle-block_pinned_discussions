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
 * Privacy Subsystem implementation for block_pinned_discussions.
 *
 * @package    block_pinned_discussions
 * @copyright  2020 Dylan Keys <dylan.keys95@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_pinned_discussions\local;
defined('MOODLE_INTERNAL') || die();

/**
 * Class to fetch pinned forum discussions
 *
 * @package block_pinned_discussions
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class pinned_discussions {
    public static function fetch_pinned_discussions($course) {
        global $DB;

        $accessibleforums = array();

        $sql = "SELECT id, instance
                FROM {course_modules}
                WHERE course = :course
                AND module = 9";

        $forumcms = $DB->get_records_sql($sql, ['course' => $course]);

        foreach ($forumcms as $forum) {
            $modinfo = get_fast_modinfo($course);
            $cm = $modinfo->get_cm($forum->id);

            if ($cm->uservisible) {
                $accessibleforums[] = $forum->instance;
            }
        }

        if (!empty($accessibleforums)) {
            $accessibleforums = implode(",", $accessibleforums);

            $sql = "SELECT id, name, forum
                    FROM {forum_discussions}
                    WHERE forum IN (:forums)
                    AND pinned = 1";

            $pinneddiscussions = $DB->get_records_sql($sql, ['forums' => $accessibleforums]);

            return $pinneddiscussions;
        }
        else {
            return false;
        }
    }
}