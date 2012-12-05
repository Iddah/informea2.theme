<?php

if(!class_exists( 'imea_decisions_admin')) {
class imea_decisions_admin extends imea_decisions_page {

    /**
     * Insert new decision into database.
     * This method does not open a transaction.
     *
     * @param array $array Array with decision properties (columns).
     * Supported keys:
     * - original_id
     * - link
     * - short_title
     * - long_title
     * - summary
     * - type ()
     * - status ()
     * - number
     * - id_treaty
     * - published
     * - updated
     * - id_meeting
     * - meeting_title - *obsolete*
     * - meeting_url - *obsolete*
     * - body
     * - display_order
     * - is_indexed
     *
     * Internal fields - Set by this method
     * - rec_created
     * - rec_author
     *
     * Internal fields - Set to NULL by this method
     * - rec_updated
     * - rec_updated_author
     *
     * @return object decision object or FALSE if error occurred
     * @todo Write tests
     */
    function create_decision($array) {
		global $wpdb;
		global $current_user;

        if(empty($array['short_title'])) {
            throw new InforMEAException(sprintf('Decision has no title (%s)', print_r($array, TRUE)));
        }

        if(!empty($array['id_treaty']) && empty($array['id_meeting'])) {
            throw new InforMEAException(sprintf('Must specify both id_treaty and id_meeting (%s)', print_r($array, TRUE)));
        }

		$this->success = false;
        $rec_created = date('Y-m-d H:i:s', strtotime("now"));
        $user = $current_user->user_login;
        $data = array_merge(array('rec_created' => $rec_created, 'rec_author' => $user), $array);

        $wpdb->insert('ai_decision', $data);
        $this->check_db_error();
        $id_decision = $wpdb->insert_id;

        // Log the action
        $decision = $this->get_decision($id_decision);
        if(!$decision) {
            throw new InforMEAException(sprintf('Decision was not added into the database (%s)', print_r($array, TRUE)));
        }
        $id_treaty = $decision->id_treaty;
        $url = sprintf('%s/treaties/%d/decisions/?showall=true#decision-%d', get_bloginfo('url'), $id_treaty, $id_decision);
        $this->add_activity_log('insert', 'decision', "Insert new decision '{$decision->number} {$decision->short_title}'", NULL, $url);
        $this->success = true;

        return $decision;
    }


    /**
     * Set an additional attribute to a decision.
     *
     * @param integer $id_decision Decision ID number
     * @param string $id_attribute Name of the attribute to add, ex. 'pages'
     * @param string $value Value to insert for this attribute
     * @param string $attribute_name (Optional) Attribute name in human readable form, ex. 'Number of pages'
     */
    function set_attribute($id_decision, $id_attribute, $value, $attribute_name = '') {
        // REPLACE INTO
    }


    /**
     * Set the country for a decision
     *
     * @param integer $id_decision
     * @param integer $id_country
     */
    function set_country($id_decision, $id_country) {
        // REPLACE INTO
    }


    /**
     * Tag decision
     *
     * @param integer $id_decision Decision ID number
     * @param integer $id_term Term ID - voc_concept ID
     */
    function set_tag($id_decision, $id_term) {
        // REPLACE INTO
    }
}
}