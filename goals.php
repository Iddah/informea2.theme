<?php
/**
 * Author: Cristian Romanescu <cristi _at_ eaudeweb dot ro>
 * Created: 201303140915
 */

add_action('wp_ajax_nopriv_ai_goals_get_activity', 'ajax_ai_goals_get_activity');
add_action('wp_ajax_ai_goals_get_activity', 'ajax_ai_goals_get_activity');


function ajax_ai_goals_get_activity() {
    $target_id = get_request_int('target_id');
    $odata_name = get_request_value('odata_name');
    $row = imea_goals_page::get_activities($target_id, $odata_name);

    $ret = array('success'=> FALSE,
        'title' => 'An error has occurred',
        'data' => '<p>There is no activity recorded on the selected target, for this instrument</p>'
    );
    if($row) {
        $ret['success'] = TRUE;
        $ret['title'] = sprintf('%s activities regarding %s', $odata_name, $target_id);
        $html = sprintf('
            %s
        ', $row->activities);
        $ret['data'] = $html;
    }

    if(is_user_logged_in()) {
        $url = sprintf('%sgoals/%d/%s/edit', INFORMEA_MANAGEMENT_URL, $target_id, $odata_name);
        $ret['data'] .= sprintf('<p><a class="btn" href="%s" target="_blank">Edit activities</a></p>', $url);
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


		function get_organizations() {
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

        static function get_activities($target_id, $odata_name) {
            global $wpdb;
            $sql = $wpdb->prepare('SELECT a.activities FROM ai_goals_activities a
                    WHERE a.target_id=%d AND a.odata_name=%s', $target_id, $odata_name);
            return $wpdb->get_row($sql);
        }

		static $table_data = array(
			1 => array(
				'imo' => 'Through the Integrated Technical Cooperation Programme (ITCP) and the GEF-UND-IMO GloBallast programme
				(two phases, 2000-2005 and 2007-2014) IMO have continuously been building awareness regarding biodiversity and in particular the invasive species issue,
				through training courses and workshops at national and regional level.
				Five different training packages, from introductory to advanced level, have been developed and translated into different languages.
				Individuals from national and local governments, private sector, and civil society groups have been trained in national and regional level training courses
				in all regions of the world.
				<br /><br />
				Strategic Plan Targets 3.1 (Levels of engagement in and commitment of existing Parties to CMS increased), 3.2 (Level of engagement in CMS work of priority target
				non-Parties increased), 3.3 (Number of Partners supporting and participating in the work of CMS increased), 3.4 (Awareness of key media of CMS and its leading
				role in the conservation of migratory species enhanced), 3.5 (Opinion-leaders of key sectoral groups impacting on migratory species influenced, including by
				expert advice, through CMS), 3.6 (Key [CMS/migratory species] information material in appropriate UN languages disseminated to identified target audiences)
				and 4.1 (CMS membership increased by 30 Parties, particularly those that are of high importance for migratory species, and/or for which there is a high priority
				for securing new agreements.))',

				'cms' => 'Production of information material',
				'unwto' => 'Has produced a publication “Tourism and Biodiversity – Achieving Common Goals Towards Sustainability” illustrating the high value of biodiversity for tourism, outlines current policies, guidelines and global initiatives in which the interrelationship between tourism and biodiversity is addressed, as well as identifies risks and challenges for the tourism sector from the global loss of biodiversity and ecosystem services. It supports the application of sustainable tourism practices, projects and tools (incl. the CBD Guidelines on Biodiversity and Tourism Development) linked with biodiversity conservation in all natural destinations through: <ul><li>Awareness raising and capacity building activities in the different levels of the tourism industry and administration on the links between biodiversity and tourism and on the value of biodiversity and its associated services, to tourism.</li><li>Provision of strategic advice to members to minimise and avoid negative impacts of tourism-related activities on the conservation and sustainable use of biodiversity</li><li>On-going research and publication of relevant materials and inter-agency cooperation on biodiversity and tourism issues</li></ul>',
				'unep' => 'Awareness raising through interactive social media based website such as “Protected Planet”, and smart phone apps on indicators for achieving the Aichi Biodiversity Targets. TEEB’s Ecological and Economic Foundations and the work of UNEP in following up to TEEB to assess the state-of-the-art science and economics behind environmental valuation. UNEP-WCMC is reviewing lessons learnt from incorporating the valuation of biodiversity and ecosystem services into the development of NBSAPs, and also promoting the integration of biodiversity and development issues into the second generation of NBSAPs in Southern Africa.',
				'unu' => 'The work of UNU Media Studio focuses on highlighting research conducted by different UNU- Research and Training Centres (RTCs) through a web portal (www.ourworld.unu.edu) and through producing video briefs in collaboration with researchers on pertinent issues related to biodiversity.'
			)
		);
	}
}
