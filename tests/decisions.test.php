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
}