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

namespace block_library\output;

use local_daatool\api;
use renderable;
use renderer_base;
use templatable;

defined('MOODLE_INTERNAL') || die();


global $CFG;
require_once($CFG->dirroot.'/lib/coursecatlib.php');

/**
 * Class library_content
 * @package block_library\output
 */
class library_content implements renderable, templatable {

    /**
     * @param renderer_base $output
     * @return array|\stdClass|void
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    public function export_for_template(renderer_base $output) {
        // Obtenemos todos los proyectos base de la herramienta base
        // De la institución del usuario.
        global $DB, $USER, $OUTPUT;

        $projects = [];

        $institution = $USER->institution;
        if (!$institution) {
            $institution = 0;
        }
        $toolcat = 'tool_base-' . $institution;
        $institutioncatid = $DB->get_field('course_categories', 'id', ['idnumber' => $toolcat]);

        if ($institutioncatid) {
            $categories = \coursecat::get($institutioncatid)->get_children();
            foreach ($categories as $category) {
                $data = (object) ['id' => $category->id, 'name' => $category->name];
                // The only time we can give data to our exporter is when instantiating it.
                $projectexporter = new project_exporter($data);
                // To export, we must pass the reference to a renderer.
                $projectexported = $projectexporter->export($OUTPUT);
                $projects[] = $projectexported;
            }
        }

        return [
            'projects' => $projects,
            'hasprojects' => count($projects)
        ];
    }
}