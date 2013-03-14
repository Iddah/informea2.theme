<?php
/**
 * Author: Cristian Romanescu <cristi _at_ eaudeweb dot ro>
 * Created: 201303140915
 */

if(!class_exists( 'imea_goals_page')) {
class imea_goals_page extends imea_page_base_page {

    function get_aichi_targets_overview() {
        global $wpdb;
        return $wpdb->get_results(
            "SELECT a.id, a.order,a.name, a.id_strategic_goal, b.name as goal FROM ai_goals a
                INNER JOIN ai_goals b ON (b.id_strategic_goal = a.id_strategic_goal)
                WHERE a.type = 'Aichi Target' GROUP BY a.id ORDER BY a.`order`"
        );
    }


    function aichi_targets_overview_rowspan($targets) {
        $ret = array();
        $prev = $targets[0];
        $pos = 0;
        $val = 0;
        foreach($targets as $idx => $current) {
            $ret[$idx] = 0;
            if($current->goal == $prev->goal) {
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
}
}
