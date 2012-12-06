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
        $rec_created = date('Y-m-d H:i:s', strtotime('now'));
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
     * @param string $value Value to insert for this attribute. Pass NULL to remove the attribute
     * @param string $attribute_name (Optional) Attribute name in human readable form, ex. 'Number of pages'
     */
    function set_attribute($id_decision, $id_attribute, $value, $attribute_name = '') {
        if(empty($id_decision) || empty($id_attribute)) {
            throw new InforMEAException(sprintf('Invalid attribute values (%s, %s, %s)', $id_decision, $id_attribute, $value));
        }
		global $wpdb;
		$this->success = false;
        if(!empty($value)) {
            $wpdb->query(
                $wpdb->prepare('REPLACE INTO ai_decision_attributes (id_decision, id_attribute, attribute_name, value) VALUES (%s, %s, %s, %s)',
                    $id_decision, $id_attribute, $attribute_name, $value)
            );
        } else {
            $wpdb->delete('ai_decision_attributes', array('id_decision' => $id_decision, 'id_attribute' => $id_attribute), array('%d', '%s'));
        }
        $this->check_db_error();

        // Log the action
        $this->add_activity_log('update', 'decision', sprintf('Set attribute (%s=%s) for the decision %s', $id_attribute, substr($value, 20), $id_decision));
        $this->success = true;
    }


    /**
     * Set the country for a decision.
     *
     * @param integer $id_decision
     * @param integer $id_country
     */
    function set_country($id_decision, $id_country) {
        if(empty($id_decision) || empty($id_country)) {
            throw new InforMEAException(sprintf('Invalid parameters (%d, %d)', $id_decision, $id_country));
        }
		global $wpdb;
		$this->success = false;
        $wpdb->query(
            $wpdb->prepare('REPLACE INTO ai_decision_country (id_decision, id_country) VALUES (%s, %s)',
                $id_decision, $id_country)
        );
        $this->check_db_error();

        // Log the action
        $this->add_activity_log('update', 'decision', sprintf('Set country #%s for the decision %s', $id_country, $id_decision));
        $this->success = true;
    }


    /**
     * Remove all countries for a decision
     *
     * @param integer $id_decision Decision ID
     * @throws InforMEAException
     */
    function remove_countries($id_decision) {
        global $wpdb;
        if(empty($id_decision)) {
            throw new InforMEAException('Invalid decision');
        }
        $wpdb->delete('ai_decision_country', array('id_decision' => $id_decision), array('%d'));
        $this->check_db_error();

        // Log the action
        $this->add_activity_log('update', 'decision', sprintf('Remove all countries for the decision %s', $id_decision));
        $this->success = true;
    }


    /**
     * Tag decision
     *
     * @param integer $id_decision Decision ID number
     * @param integer $id_term Term ID - voc_concept ID
     */
    function set_tag($id_decision, $id_term) {
        if(empty($id_decision) || empty($id_term)) {
            throw new InforMEAException(sprintf('Invalid parameters (%d, %d)', $id_decision, $id_term));
        }
		global $wpdb, $current_user;

        $rec_created = date('Y-m-d H:i:s', strtotime('now'));
        $user = $current_user->user_login;

        $this->success = false;
        $wpdb->query(
            $wpdb->prepare('REPLACE INTO ai_decision_vocabulary (id_decision, id_concept, rec_created, rec_author) VALUES (%s, %s, %s, %s)',
                $id_decision, $id_term, $rec_created, $user)
        );
        $this->check_db_error();

        // Log the action
        $this->add_activity_log('update', 'decision', sprintf('Set tags #%s for the decision %s', $id_term, $id_decision));
        $this->success = true;
    }


    /**
     * Remove all countries for a decision
     *
     * @param integer $id_decision Decision ID
     * @throws InforMEAException
     */
    function remove_tags($id_decision) {
        global $wpdb;
        if(empty($id_decision)) {
            throw new InforMEAException('Invalid decision');
        }
        $wpdb->delete('ai_decision_vocabulary', array('id_decision' => $id_decision), array('%d'));
        $this->check_db_error();

        // Log the action
        $this->add_activity_log('update', 'decision', sprintf('Remove all tags for the decision %s', $id_decision));
        $this->success = true;
    }
}
}