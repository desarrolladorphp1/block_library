<?php


namespace block_library\output;

use action_menu;
use action_menu_link_secondary;
use core\external\exporter;
use local_daa\senderos_project;
use local_daatool\levels;
use moodle_url;
use pix_icon;

/**
 * Class project_exporter
 * @package block_library\output
 */
class project_exporter extends exporter {
    /**
     * Return the list of properties.
     *
     * @return array
     */
    protected static function define_properties() {
        return [
            'id' => [
                'type' => PARAM_INT,
                'description' => 'Category ID'
            ],
            'name' => [
                'type' => PARAM_ALPHANUMEXT,
                'description', 'Project name',
            ],
            'projecturl' => [
                'type' => PARAM_URL
            ],
            'projectimage' => [
                'type' => PARAM_URL
            ],
            'hasimage' => [
                'type' => PARAM_BOOL
            ],
            'projectlevels' => [
                'type' => [
                    'levelname' => [
                        'type' => PARAM_ALPHANUMEXT,
                        'description' => 'Category ID'
                    ]
                ],
                'multiple' => true,
                'description' => 'Category ID'
            ],
            'projectareas' => [
                'type' => PARAM_ALPHANUMEXT
            ],
            'menu' => [
                'type' => PARAM_RAW
            ]
        ];
    }

    /**
     * Get the additional values to inject while exporting.
     *
     * @param \renderer_base $output The renderer.
     * @return array Keys are the property names, values are their values.
     * @throws \moodle_exception
     */
    protected function get_other_values(\renderer_base $output) {
        $projecturl = new moodle_url('/course/index.php', [
            'categoryid' => $this->data->id,
            'editon' => true]
        );
        $senderosproject = new senderos_project($this->data->id);
        $projectcover = $senderosproject->get_projectcover();
        $projectlevels = $senderosproject->get_levels();
        $subjects = $senderosproject->get_subjects('name');

        $subjectsnames = [];
        foreach ($subjects as $subject) {
            $subjectsnames[] = $subject->get('name');
        }

        $levelsmanager = new levels();
        $levelscategorized = [];
        $levels = [];
        if ($projectlevels) {
            $projectlevels = explode(';', $projectlevels);
            foreach ($projectlevels as $projectlevel) {
                $courselevelstr = $levelsmanager->get_course_level($projectlevel);
                $levelscategorized[$courselevelstr][] = $levelsmanager->get_level_name($projectlevel);
            }
            foreach ($levelscategorized as $levelname => $levelcategorized) {
                $levels[] = [
                    'name' => $levelname,
                    'levels' => implode(' / ', $levelcategorized)
                ];
            }
        }


        $menu = new action_menu();
        $rolesurl = new moodle_url('/admin/roles/assign.php', array('contextid' => $block->context->id,
            'returnurl' => 'ADAS'));
        $str = 'caca';
        $control = new action_menu_link_secondary($rolesurl, new pix_icon('i/assignroles', 'caca', 'moodle', array('class' => 'iconsmall', 'title' => '')), 'caca', []);
        $menu->add($control);
        $menu = $output->render($menu);

        return [
            'projectimage' => $projectcover,
            'hasimage' => $projectcover ? true : false,
            'projecturl' => $projecturl->out(false),
            'projectlevels' => $levels,
            'projectareas' => implode (' / ', $subjectsnames),
            'menu' => $menu
        ];
    }
}