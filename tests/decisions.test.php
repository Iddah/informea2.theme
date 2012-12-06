<?php

require_once WP_PLUGIN_DIR . '/imea_ai/imea.php';
require_once WP_PLUGIN_DIR . '/informea/imea.php';

class imea_decisions_admin_test extends InforMEABaseTest {


    function test_create_decision() {
        $treaty = $this->create_treaty();
        $meeting = $this->create_meeting();
        $ob = new imea_decisions_admin();
        $d1 = array(
            'original_id' => 'original_id',
            'link' => 'link',
            'short_title' => 'short_title',
            'long_title' => 'long_title',
            'summary' => 'summary',
            'type' => 'Decision',
            'status' => 'Active',
            'number' => 'number',
            'id_treaty' => $treaty->id,
            'published' => '2001-12-03',
            'updated' => '1999-11-04',
            'id_meeting' => $meeting->id,
            'meeting_title' => 'meeting_title',
            'meeting_url' => 'meeting_url',
            'body' => 'body',
            'display_order' => '3',
            'is_indexed' => '1'
        );
        $o1 = $ob->create_decision($d1);
        $this->assertNotNull($o1);
        $this->assertEquals('original_id', $o1->original_id);
        $this->assertEquals('link', $o1->link);
        $this->assertEquals('short_title', $o1->short_title);
        $this->assertEquals('long_title', $o1->long_title);
        $this->assertEquals('summary', $o1->summary);
        $this->assertEquals('decision', $o1->type);
        $this->assertEquals('active', $o1->status);
        $this->assertEquals('number', $o1->number);
        $this->assertEquals('2001-12-03 00:00:00', $o1->published);
        $this->assertEquals('1999-11-04 00:00:00', $o1->updated);
        $this->assertEquals('meeting_title', $o1->meeting_title);
        $this->assertEquals('meeting_url', $o1->meeting_url);
        $this->assertEquals('body', $o1->body);
        $this->assertEquals('3', $o1->display_order);
        $this->assertEquals('1', $o1->is_indexed);
        $this->assertEquals('1', $o1->id_meeting);
        $this->assertEquals('1', $o1->id_treaty);
    }


    /**
     * @expectedException InforMEAException
     */
    function test_create_decision_no_short_title() {
        $ob = new imea_decisions_admin();
        $ob->create_decision(array());
    }


    /**
     * Test should fail if we specify id_treaty and no id_meeting
     * @expectedException InforMEAException
     */
    function test_create_decision_id_treaty_meeting() {
        $treaty = $this->create_treaty();
        $ob = new imea_decisions_admin();
        $ob->create_decision(array(
            'short_title' => 'test',
            'id_treaty' => $treaty->id
        ));
    }


    function test_set_attribute() {
        $decision = $this->create_decision();

        $ob = new imea_decisions_admin();
        $ob->set_attribute($decision->id, 'attr_test', 'XXX', 'TEST');

        global $wpdb;

        // Create
        $count = $wpdb->get_var('SELECT COUNT(*) FROM ai_decision_attributes');
        $this->assertEquals(1, $count);
        $row = $wpdb->get_row('SELECT * FROM ai_decision_attributes LIMIT 1');
        $this->assertEquals(1, $row->id);
        $this->assertEquals($decision->id, $row->id_decision);
        $this->assertEquals('attr_test', $row->id_attribute);
        $this->assertEquals('XXX', $row->value);
        $this->assertEquals('TEST', $row->attribute_name);

        // Edit
        $ob->set_attribute($decision->id, 'attr_test', 'ANOTHER', 'TEST2');
        $count = $wpdb->get_var('SELECT COUNT(*) FROM ai_decision_attributes');
        $this->assertEquals(1, $count);

        $row = $wpdb->get_row('SELECT * FROM ai_decision_attributes LIMIT 1');
        $this->assertEquals(2, $row->id);
        $this->assertEquals($decision->id, $row->id_decision);
        $this->assertEquals('attr_test', $row->id_attribute);
        $this->assertEquals('ANOTHER', $row->value);
        $this->assertEquals('TEST2', $row->attribute_name);

        // Remove
        $ob->set_attribute($decision->id, 'attr_test', NULL, 'TEST2');
        $count = $wpdb->get_var('SELECT COUNT(*) FROM ai_decision_attributes');
        $this->assertEquals(0, $count);
    }


    /**
     * Test should fail since we are passing NULL decision
     *
     * @expectedException InforMEAException
     */
    function test_set_attribute_null_decision() {
        $ob = new imea_decisions_admin();
        $ob->set_attribute(NULL, 'attr_test', 'XXX', 'TEST');
    }


    /**
     * Test should fail since we are passing NULL attribute ID
     *
     * @expectedException InforMEAException
     */
    function test_set_attribute_null_id_attribute() {
        $ob = new imea_decisions_admin();
        $ob->set_attribute(1, NULL, 'XXX', 'TEST');
    }


    /**
     * Test should fail since we are passing non-existing decision ID
     *
     * @expectedException InforMEAException
     */
    function test_set_attribute_invalid_id_decision() {
        $ob = new imea_decisions_admin();
        $ob->set_attribute(1, 'attr_test', 'XXX', 'TEST');
    }


    function test_set_country() {
        global $wpdb;

        $country = $this->create_country();
        $decision = $this->create_decision();

        // Set
        $ob = new imea_decisions_admin();
        $ob->set_country($decision->id, $country->id);

        $count = $wpdb->get_var('SELECT COUNT(*) FROM ai_decision_country');
        $this->assertEquals(1, $count);

        $row = $wpdb->get_row('SELECT * FROM ai_decision_country LIMIT 1');
        $this->assertEquals($decision->id, $row->id_decision);
        $this->assertEquals($country->id, $row->id_country);
     }


    /**
     * Test should fail since we are passing NULL attribute ID
     *
     * @expectedException InforMEAException
     */
    function test_set_country_null_country() {
        $ob = new imea_decisions_admin();
        $ob->set_country(1, NULL);
    }


    /**
     * Test should fail since we are passing NULL attribute ID
     *
     * @expectedException InforMEAException
     */
    function test_set_country_null_decision() {
        $ob = new imea_decisions_admin();
        $ob->set_country(NULL, NULL);
    }


    /**
     * Test should fail since we are passing non-existing decision ID
     *
     * @expectedException InforMEAException
     */
    function test_set_country_invalid_id_decision() {
        $ob = new imea_decisions_admin();
        $ob->set_country(3, 2);
    }


    function test_remove_countries() {
        global $wpdb;

        $country = $this->create_country();
        $decision = $this->create_decision();

        // Set
        $ob = new imea_decisions_admin();
        $ob->set_country($decision->id, $country->id);

        $count = $wpdb->get_var('SELECT COUNT(*) FROM ai_decision_country');
        $this->assertEquals(1, $count);

        $row = $wpdb->get_row('SELECT * FROM ai_decision_country LIMIT 1');
        $this->assertEquals($decision->id, $row->id_decision);
        $this->assertEquals($country->id, $row->id_country);

        $ob->remove_countries($decision->id);
        $count = $wpdb->get_var('SELECT COUNT(*) FROM ai_decision_country');
        $this->assertEquals(0, $count);
     }


    /**
     * Test should fail since we are passing non-existing decision ID
     *
     * @expectedException InforMEAException
     */
    function test_remove_countries_invalid_id_decision() {
        $ob = new imea_decisions_admin();
        $ob->remove_countries(NULL);
    }


    function test_set_tag() {
        global $wpdb;

        $decision = $this->create_decision();
        $term = $this->create_term();

        $ob = new imea_decisions_admin();
        $ob->set_tag($decision->id, $term->id);

        $count = $wpdb->get_var('SELECT COUNT(*) FROM ai_decision_vocabulary');
        $this->assertEquals(1, $count);

        $row = $wpdb->get_row('SELECT * FROM ai_decision_vocabulary LIMIT 1');
        $this->assertEquals($decision->id, $row->id_decision);
        $this->assertEquals($term->id, $row->id_concept);
     }


    /**
     * Test should fail since we are passing NULL term ID
     *
     * @expectedException InforMEAException
     */
    function test_set_tag_null_term() {
        $ob = new imea_decisions_admin();
        $ob->set_tag(1, NULL);
    }


    /**
     * Test should fail since we are passing NULL decision ID
     *
     * @expectedException InforMEAException
     */
    function test_set_tag_null_decision() {
        $ob = new imea_decisions_admin();
        $ob->set_tag(NULL, 1);
    }


    /**
     * Test should fail since we are passing non-existing ids
     *
     * @expectedException InforMEAException
     */
    function test_set_tag_invalid_ids() {
        $ob = new imea_decisions_admin();
        $ob->set_tag(3, 2);
    }


    function test_remove_tags() {
        global $wpdb;

        $decision = $this->create_decision();
        $term = $this->create_term();

        $ob = new imea_decisions_admin();
        $ob->set_tag($decision->id, $term->id);

        $count = $wpdb->get_var('SELECT COUNT(*) FROM ai_decision_vocabulary');
        $this->assertEquals(1, $count);

        $row = $wpdb->get_row('SELECT * FROM ai_decision_vocabulary LIMIT 1');
        $this->assertEquals($decision->id, $row->id_decision);
        $this->assertEquals($term->id, $row->id_concept);

        $ob->remove_tags($decision->id);
        $count = $wpdb->get_var('SELECT COUNT(*) FROM ai_decision_vocabulary');
        $this->assertEquals(0, $count);
     }


    /**
     * Test should fail since we are passing non-existing ids
     *
     * @expectedException InforMEAException
     */
    function test_remove_tags_null_decision() {
        $ob = new imea_decisions_admin();
        $ob->remove_tags(NULL);
     }

}