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
 * Block library is defined here.
 *
 * @package     block_library
 * @copyright   2019 SM
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * library block.
 *
 * @package    block_library
 * @copyright  2019 SM
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_library extends block_base {

    /**
     * Initializes class member variables.
     */
    public function init() {
        $this->title = '';
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content() {
        global $PAGE;

        $block = optional_param('block', null, PARAM_TEXT);
        if ($block !== 'library') {
            return '';
        }

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        $output = $PAGE->get_renderer('block_library');
        $page = new \block_library\output\library_content();
        $this->content->text  = $output->render($page);

        return $this->content;
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats() {
        return ['my-index' => true];
    }
}
