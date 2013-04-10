<?php
/**
 * Author: Cristian Romanescu <cristi _at_ eaudeweb dot ro>
 * Created: 201303140915
 */

add_action('wp_ajax_nopriv_ai_goals_get_activity', 'ajax_ai_goals_get_activity');
add_action('wp_ajax_ai_goals_get_activity', 'ajax_ai_goals_get_activity');

add_action('wp_ajax_nopriv_ai_goals_get_target_indicators', 'ajax_ai_goals_get_target_indicators');
add_action('wp_ajax_ai_goals_get_target_indicators', 'ajax_ai_goals_get_target_indicators');

if(!defined('GOAL_TYPE_AICHI_TARGET')) {
    define ('GOAL_TYPE_AICHI_TARGET', 'Aichi Target');
}
if(!defined('GOAL_TYPE_STRAGETIC_GOAL')) {
    define ('GOAL_TYPE_STRAGETIC_GOAL', 'Strategic Goal');
}

function ajax_ai_goals_get_activity() {
    $target_id = get_request_int('target_id');
    $odata_name = get_request_value('odata_name');
    $row = imea_goals_page::get_activities($target_id, $odata_name);

    $ret = array('success' => FALSE,
        'title' => 'An error has occurred',
        'data' => '<p>There is no activity recorded on the selected target, for this instrument</p>'
    );
    if ($row) {
        $ret['success'] = TRUE;
        $target = imea_goals_page::get_target($target_id);
        $ret['title'] = sprintf('%s activities regarding %s', imea_goals_page::get_organization_name($odata_name),
            $target->name);
        $html = sprintf('%s', $row->activities);
        $ret['data'] = $html;
    }

    if (is_user_logged_in()) {
        $url = sprintf('%sgoals/%d/%s/edit', INFORMEA_MANAGEMENT_URL, $target_id, $odata_name);
        $ret['data'] .= sprintf('<p><a class="btn" href="%s" target="_blank">Edit activities</a></p>', $url);
    }

    header('Content-Type:application/json');
    echo json_encode($ret);
    die();
}

function ajax_ai_goals_get_target_indicators() {
    $target_id = get_request_int('target_id');
    $ret = array('success' => FALSE,
        'title' => 'An error has occurred',
        'data' => '<p>There are no indicators recorded on the selected target</p>'
    );
    $row = imea_goals_page::get_aichi_target($target_id);
    if (!empty($row->indicators)) {
        $ret['success'] = TRUE;
        $ret['title'] = sprintf('Biodiversity indicators for: %s', $row->name);
        $html = sprintf('%s', $row->indicators);
        $ret['data'] = $html;
    }

    if (is_user_logged_in()) {
        $url = sprintf('%sgoals/%d/edit', INFORMEA_MANAGEMENT_URL, $target_id);
        $ret['data'] .= sprintf('<p><a class="btn" href="%s" target="_blank">Edit indicators</a></p>', $url);
    }

    header('Content-Type:application/json');
    echo json_encode($ret);
    die();
}

if (!class_exists('imea_goals_page')) {
    class imea_goals_page extends imea_page_base_page {

        function get_aichi_targets_overview() {
            global $wpdb;

            return $wpdb->get_results(
                "SELECT a.id, a.order,a.name, a.indicators, a.id_strategic_goal, b.name as goal FROM ai_goals a
                INNER JOIN ai_goals b ON (b.id_strategic_goal = a.id_strategic_goal)
                WHERE a.type = 'Aichi Target' GROUP BY a.id ORDER BY a.`order`"
            );
        }


        function aichi_targets_overview_rowspan($targets) {
            $ret = array();
            $prev = $targets[0];
            $pos = 0;
            $val = 0;
            foreach ($targets as $idx => $current) {
                $ret[$idx] = 0;
                if ($current->goal == $prev->goal) {
                    $val++;
                    continue;
                } else {
                    $ret[$pos] = $val;
                    $val = 1;
                    $pos = $idx;
                }
                $prev = $current;
            }
            $ret[$pos] = $val;
            return $ret;
        }


        static function get_organizations() {
            return array(
                'cites' => array('label' => 'CITES'),
                'cms' => array('label' => 'CMS'),
                'desa' => array('label' => 'DESA'),
                'fao' => array('label' => 'FAO'),
                'ifad' => array('label' => 'IFAD'),
                'imo' => array('label' => 'IMO'),
                'unep' => array('label' => 'UNEP'),
                'ramsar' => array('label' => 'Ramsar'),
                'whc' => array('label' => 'WHC'),
                'who' => array('label' => 'WHO'),
                'unu' => array('label' => 'UNU'),
                'wipo' => array('label' => 'WIPO'),
                'wto' => array('label' => 'WTO'),
                'undp' => array('label' => 'UNDP'),
                'wbg' => array('label' => 'WBG'),
                'unctad' => array('label' => 'UNCTAD'),
                'unesco' => array('label' => 'UNESCO'),
            );
        }

        static function get_organization_name($odata_name) {
            $ret = NULL;
            $orgs = self::get_organizations();
            if(!empty($orgs[$odata_name]['label'])) {
                $ret = $orgs[$odata_name]['label'];
            }
            return $ret;
        }

        static function get_activities($target_id, $odata_name) {
            global $wpdb;
            $sql = $wpdb->prepare('SELECT a.activities FROM ai_goals_activities a
                    WHERE a.target_id=%d AND a.odata_name=%s', $target_id, $odata_name);
            return $wpdb->get_row($sql);
        }


        static function get_aichi_target($target_id) {
            global $wpdb;
            $sql = $wpdb->prepare('SELECT a.id, a.`order`, a.`type`, a.name, a.indicators, b.name AS strategic_goal_name
                    FROM ai_goals a
                    LEFT JOIN ai_goals b ON (a.id_strategic_goal = b.id AND b.`type` = %s)
                    WHERE a.id=%d AND a.type=%s', GOAL_TYPE_STRAGETIC_GOAL, $target_id, GOAL_TYPE_AICHI_TARGET);
            return $wpdb->get_row($sql);
        }


        static function get_target($target_id) {
            global $wpdb;
            $sql = $wpdb->prepare('SELECT a.id, a.`order`, a.`type`, a.name, a.indicators,
                        b.name AS strategic_goal_name, c.title as geg_theme
                    FROM ai_goals a
                    LEFT JOIN ai_goals b ON a.id_strategic_goal = b.id
                    LEFT JOIN geg_ai_theme c ON a.id_theme_geg = c.id
                    WHERE a.id=%d', $target_id);
            return $wpdb->get_row($sql);
        }


        /**
         * Retrieve all activities grouped by Aichi target, then by instrument
         * @return array array('target_1' => ('instr_1' => 'act 1', 'instr_2' => 'act 2'), 'target_2' => ( ... ))
         */
        static function get_activities_all() {
            global $wpdb;
            $ret = array();
            $rows = $wpdb->get_results('SELECT a.odata_name, a.target_id, a.activities FROM ai_goals_activities a');
            foreach ($rows as $row) {
                if (!isset($ret[$row->target_id])) {
                    $ret[$row->target_id] = array();
                }
                $ret[$row->target_id][$row->odata_name] = $row->activities;
            }
            return $ret;
        }
    }
}
