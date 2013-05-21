<?php
/**
 * Author: Cristian Romanescu <cristi _at_ eaudeweb dot ro>
 * Created: 201303140915
 */


if (!class_exists('imea_goals_page')) {
    class imea_goals_page extends imea_page_base_page {

        static function get_organizations() {
            global $wpdb;
            return $wpdb->get_results(
                "SELECT a.* FROM ai_treaty a ORDER BY `order`"
            );
        }


        static function get_organization_name($odata_name) {
            global $wpdb;
            return $wpdb->get_var($wpdb->prepare(
                "SELECT a.short_title FROM ai_treaty a WHERE a.odata_name=%s", $odata_name)
            );
        }


        static function get_aichi_targets_overview() {
            global $wpdb;

            return $wpdb->get_results(
                "SELECT
                    a.id, a.order,a.name, a.indicators, a.id_strategic_goal,
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


        static function get_organization($odata_name) {
            global $wpdb;
            return $wpdb->get_row($wpdb->prepare(
                    "SELECT a.* FROM ai_treaty a WHERE a.odata_name=%s", $odata_name
                )
            );
        }


        static function get_activities($target_id, $odata_name) {
            global $wpdb;
            $sql = $wpdb->prepare('SELECT a.activities FROM ai_goals_activities a
                    WHERE a.target_id=%d AND a.odata_name=%s', $target_id, $odata_name);
            return $wpdb->get_row($sql);
        }


        static function get_tools($target_id, $odata_name) {
            global $wpdb;
            $sql = $wpdb->prepare('SELECT a.tools FROM ai_goals_tools a
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
                        b.name AS strategic_goal_name, b.id AS strategic_goal_id
                    FROM ai_goals a
                    LEFT JOIN ai_goals b ON a.id_strategic_goal = b.id
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


        static function get_tools_for_treaty($odata_name) {
            global $wpdb;
            $ret = array();
            $rows = $wpdb->get_results(
                $wpdb->prepare(
                    'SELECT a.target_id, a.tools FROM ai_goals_tools a WHERE a.odata_name=%s',
                    $odata_name
                )
            );
            foreach ($rows as $row) {
                if (!isset($ret[$row->target_id])) {
                        $ret[$row->target_id] = array();
                    }
                $ret[$row->target_id] = $row->tools;
            }
            return $ret;
        }


        static function get_activities_for_target($target_id) {
            global $wpdb;
            $ret = array();
            $rows = $wpdb->get_results(
                $wpdb->prepare(
                        "SELECT a.odata_name, a.activities FROM ai_goals_activities a
                            WHERE a.target_id=%s
                            AND (TRIM(a.activities) <> '' AND a.activities IS NOT NULL)",
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


        static function get_tools_for_target($target_id) {
            global $wpdb;
            $ret = array();
            $rows = $wpdb->get_results(
                $wpdb->prepare(
                        "SELECT a.odata_name, a.tools FROM ai_goals_tools a
                            WHERE a.target_id=%s
                            AND (TRIM(a.tools) <> '' AND a.tools IS NOT NULL)",
                    $target_id
                    )
            );
            foreach ($rows as $row) {
                if (!isset($ret[$row->odata_name])) {
                    $ret[$row->odata_name] = array();
                }
                $ret[$row->odata_name] = $row->tools;
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

        static function user_can_edit_target_tools($uid = NULL) {
            if(empty($uid)) {
                return current_user_can('edit_posts') ? 1 : 0 ;
            }
            return 0;
        }
    }
}
