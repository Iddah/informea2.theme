<?php
/**
 * Author: Cristian Romanescu <cristi _at_ eaudeweb dot ro>
 * Created: 201303140915
 */


if (!class_exists('imea_goals_page')) {
    class imea_goals_page extends imea_page_base_page {


        static function get_aichi_targets_overview() {
            global $wpdb;

            return $wpdb->get_results(
                "SELECT
                    a.id, a.order,a.name, a.indicators, a.tools, a.id_strategic_goal,
                    b.colour, b.colour_text, b.name as goal
                  FROM ai_goals a
                  INNER JOIN ai_goals b ON (b.id = a.id_strategic_goal)
                  WHERE a.type = 'Aichi Target'
                  GROUP BY a.id
                  ORDER BY a.`order`"
            );
        }


        static function aichi_targets_overview_rowspan($targets) {
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
                'cites' => array(
                    'label' => 'CITES',
                    'logo' => 'http://informea.org/wp-content/uploads/2012/07/cites.png'
                ),
                'cms' => array(
                    'label' => 'CMS',
                    'logo' => 'http://informea.org/wp-content/uploads/2012/07/cms.png'
                ),
                'desa' => array('label' => 'DESA'),
                'fao' => array(
                    'label' => 'FAO',
                    'logo' => 'http://informea.org/wp-content/uploads/2012/07/plant_treaty.png'
                ),
                'ifad' => array('label' => 'IFAD'),
                'imo' => array('label' => 'IMO'),
                'unep' => array(
                    'label' => 'UNEP',
                    'logo' => 'http://informea.org/wp-content/uploads/2012/07/unep.png'
                ),

                'ramsar' => array(
                    'label' => 'Ramsar',
                    'logo' => 'http://informea.org/wp-content/uploads/2012/07/ramsar.png'
                ),
                'whc' => array(
                    'label' => 'WHC',
                    'logo' => 'http://informea.org/wp-content/uploads/2012/07/whc.png'
                ),
                'who' => array('label' => 'WHO'),
                'unu' => array('label' => 'UNU'),
                'wipo' => array('label' => 'WIPO'),
                'wto' => array('label' => 'WTO'),
                'undp' => array('label' => 'UNDP'),
                'wbg' => array('label' => 'WBG'),
                'unctad' => array('label' => 'UNCTAD'),
                'unesco' => array(
                    'label' => 'UNESCO',
                    'logo' => 'http://informea.org/wp-content/uploads/2012/07/whc.png',
                ),
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


        static function get_organization($odata_name) {
            $all = self::get_organizations();
            if(isset($all[$odata_name])) {
                return $all[$odata_name];
            }
            return NULL;
        }


        static function get_activities($target_id, $odata_name) {
            global $wpdb;
            $sql = $wpdb->prepare('SELECT a.activities FROM ai_goals_activities a
                    WHERE a.target_id=%d AND a.odata_name=%s', $target_id, $odata_name);
            return $wpdb->get_row($sql);
        }


        static function get_aichi_target_by_order($order) {
            global $wpdb;
            $sql = $wpdb->prepare('SELECT a.id, a.`order`, a.`type`, a.name, a.indicators, a.tools,
                        b.name AS strategic_goal_name, b.id AS strategic_goal_id,
                        b.colour, b.colour_text
                    FROM ai_goals a
                    LEFT JOIN ai_goals b ON (a.id_strategic_goal = b.id AND b.`type` = %s)
                    WHERE a.`order`=%d AND a.type=%s', GOAL_TYPE_STRAGETIC_GOAL, $order, GOAL_TYPE_AICHI_TARGET);
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
                        b.name AS strategic_goal_name, b.id AS strategic_goal_id,
                        c.title as geg_theme
                    FROM ai_goals a
                    LEFT JOIN ai_goals b ON a.id_strategic_goal = b.id
                    LEFT JOIN geg_ai_theme c ON a.id_theme_geg = c.id
                    WHERE a.id=%d', $target_id);
            return $wpdb->get_row($sql);
        }


        static function get_activities_for_treaty($odata_name) {
            global $wpdb;
            $ret = array();
            $rows = $wpdb->get_results(
                $wpdb->prepare(
                    'SELECT a.target_id, a.activities FROM ai_goals_activities a WHERE a.odata_name=%s',
                    $odata_name
                )
            );
            foreach ($rows as $row) {
                if (!isset($ret[$row->target_id])) {
                        $ret[$row->target_id] = array();
                    }
                $ret[$row->target_id] = $row->activities;
            }
            return $ret;
        }


        static function get_activities_for_target($target_id) {
            global $wpdb;
            $ret = array();
            $rows = $wpdb->get_results(
                $wpdb->prepare(
                        'SELECT a.odata_name, a.activities FROM ai_goals_activities a WHERE a.target_id=%s',
                    $target_id
                    )
            );
            foreach ($rows as $row) {
                if (!isset($ret[$row->odata_name])) {
                    $ret[$row->odata_name] = array();
                }
                $ret[$row->odata_name] = $row->activities;
            }
            return $ret;
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


        static function user_can_edit_target($target, $organization, $user = NULL) {
            return is_user_logged_in();
        }

        static function user_can_edit_target_activities($uid = NULL) {
            if(empty($uid)) {
                return current_user_can('edit_posts') ? 1 : 0 ;
            }
            return 0;
        }
    }
}
