<?php

require_once WP_PLUGIN_DIR . '/imea_ai/imea.php';
require_once WP_PLUGIN_DIR . '/informea/imea.php';

class imea_countries_admin_test extends InforMEABaseTest {
    function test_get_country_by_name() {
        $this->create_country();

        $ob = new imea_countries_page();
        $c = $ob->get_country_by_name('Romania');
        $this->assertNotNull($c);
        $this->assertEquals(1, $c->id);
        $this->assertEquals('ROU', $c->code);
        $this->assertEquals('RO', $c->code2l);
    }


    function test_get_country_by_iso() {
        $this->create_country();

        $ob = new imea_countries_page();
        $c = $ob->get_country_by_iso('Ro');
        $this->assertNotNull($c);
        $this->assertEquals(1, $c->id);
        $this->assertEquals('ROU', $c->code);
        $this->assertEquals('RO', $c->code2l);

        $c = $ob->get_country_by_iso('RoU');
        $this->assertNotNull($c);
        $this->assertEquals(1, $c->id);
        $this->assertEquals('ROU', $c->code);
        $this->assertEquals('RO', $c->code2l);
    }


    function test_get_country_by_iso2() {
        $this->create_country();

        $ob = new imea_countries_page();
        $c = $ob->get_country_by_iso2('Ro');
        $this->assertNotNull($c);
        $this->assertEquals(1, $c->id);
        $this->assertEquals('ROU', $c->code);
        $this->assertEquals('RO', $c->code2l);

        $ob = new imea_countries_page();
        $c = $ob->get_country_by_iso2('X0');
        $this->assertNull($c);
    }


    function test_get_country_by_iso3() {
        $this->create_country();

        $ob = new imea_countries_page();
        $c = $ob->get_country_by_iso3('RoU');
        $this->assertNotNull($c);
        $this->assertEquals(1, $c->id);
        $this->assertEquals('ROU', $c->code);
        $this->assertEquals('RO', $c->code2l);

        $c = $ob->get_country_by_iso3('XX0');
        $this->assertNull($c);
    }

    function test_get_treaty_membership() {
        global $wpdb;

        $t = $this->create_treaty();
        $c = $this->create_country();

        $wpdb->insert('ai_treaty_country', array(
            'id_country' => $c->id,
            'id_treaty' => $t->id,
            'date' => '1999-12-23',
            'status' => 'entryIntoForce',
            'legal_instrument_name' => 'legal_instrument_name',
            'legal_instrument_type' => 'protocol',
            'parent_legal_instrument' => 'parent_legal_instrument',
            'declarations' => 'declarations',
            'notes' => 'notes'
        ));

        $ob = new imea_countries_page();
        $r = $ob->get_treaty_membership($c->id);
        $this->assertNotNull($r);
        $this->assertEquals(1, count($r));

        $p = $r[0];
        $this->assertEquals($t->id, $p->id_treaty);
        $this->assertEquals($c->id, $p->id_country);
        $this->assertEquals('1999-12-23', $p->date);
        $this->assertEquals('entryIntoForce', $p->status);
        $this->assertEquals('legal_instrument_name', $p->legal_instrument_name);
        $this->assertEquals('protocol', $p->legal_instrument_type);
        $this->assertEquals('parent_legal_instrument', $p->parent_legal_instrument);
        $this->assertEquals('declarations', $p->declarations);
        $this->assertEquals('notes', $p->notes);
    }


    function test_count_treaty_membership() {
        global $wpdb;

        $t = $this->create_treaty();
        $c = $this->create_country();

        $wpdb->insert('ai_treaty_country', array(
            'id_country' => $c->id,
            'id_treaty' => $t->id,
            'date' => '1999-12-23',
            'status' => 'entryIntoForce',
            'legal_instrument_name' => 'legal_instrument_name',
            'legal_instrument_type' => 'protocol',
            'parent_legal_instrument' => 'parent_legal_instrument',
            'declarations' => 'declarations',
            'notes' => 'notes'
        ));

        $ob = new imea_countries_page();
        $i = $ob->count_treaty_membership($c->id);
        $this->assertEquals(1, $i);

        $i = $ob->count_treaty_membership(999);
        $this->assertEquals(0, $i);
    }


    function test_get_focal_points_by_treaty() {
        global $wpdb;
        // Create treaty
        $t1 = $this->create_treaty();

        // Create country
        $c1 = $this->create_country();

        // Create focal points
        $wpdb->insert('ai_people', array(
            'id_country' => $c1->id,
            'first_name' => 'zorba',
            'last_name' => 'the greek'
        ));
        $p1 = $wpdb->get_row('SELECT * FROM ai_people WHERE id=1 LIMIT 1');
        $this->assertNotNull($p1);

        $wpdb->insert('ai_people', array(
            'id_country' => $c1->id,
            'first_name' => 'asterix',
            'last_name' => 'the gaul'
        ));
        $p2 = $wpdb->get_row('SELECT * FROM ai_people WHERE id=2 LIMIT 1');
        $this->assertNotNull($p2);

        // Assign focal points to treaties
        $wpdb->insert('ai_people_treaty', array(
            'id_people' => $p1->id,
            'id_treaty' => $t1->id
        ));
        $wpdb->insert('ai_people_treaty', array(
            'id_people' => $p2->id,
            'id_treaty' => $t1->id
        ));

        $ob = new imea_countries_page();
        $rows = $ob->get_focal_points_by_treaty($c1->id);
        $this->assertNotNull($rows);
        $this->assertEquals(1, count($rows));
        $row1 = current($rows);
        $this->assertEquals($t1->id, $row1->id);
        $this->assertEquals(2, count($row1->focal_points));
        $fp1 = $row1->focal_points[0];
        $fp2 = $row1->focal_points[1];
        // Reverse order
        $this->assertEquals($p1->id, $fp2->id);
        $this->assertEquals($p2->id, $fp1->id);

        $rows = $ob->get_focal_points_by_treaty(999);
        $this->assertNotNull($rows);
        $this->assertEquals(0, count($rows));
    }


    function test_count_focal_points() {
        global $wpdb;
        // Create treaty
        $t1 = $this->create_treaty();

        // Create country
        $c1 = $this->create_country();

        // Create focal points
        $wpdb->insert('ai_people', array(
            'id_country' => $c1->id,
            'first_name' => 'zorba',
            'last_name' => 'the greek'
        ));
        $p1 = $wpdb->get_row('SELECT * FROM ai_people WHERE id=1 LIMIT 1');
        $this->assertNotNull($p1);

        $wpdb->insert('ai_people', array(
            'id_country' => $c1->id,
            'first_name' => 'asterix',
            'last_name' => 'the gaul'
        ));
        $p2 = $wpdb->get_row('SELECT * FROM ai_people WHERE id=2 LIMIT 1');
        $this->assertNotNull($p2);

        // Assign focal points to treaties
        $wpdb->insert('ai_people_treaty', array(
            'id_people' => $p1->id,
            'id_treaty' => $t1->id
        ));
        $wpdb->insert('ai_people_treaty', array(
            'id_people' => $p2->id,
            'id_treaty' => $t1->id
        ));

        $ob = new imea_countries_page();
        $c = $ob->count_focal_points($c1->id);
        $this->assertEquals(2, $c);

        $c = $ob->count_focal_points(999);
        $this->assertNotNull($c);
        $this->assertEquals(0, $c);
    }


    function test_get_national_plans() {
        global $wpdb;

        $t1 = $this->create_treaty();
        $c1 = $this->create_country();
        $m1 = $this->create_meeting();

        $wpdb->insert('ai_country_plan', array(
            'original_id' => '1',
            'id_country' => $c1->id,
            'id_treaty' => $t1->id,
            'submission' => '1992-12-23 00:00:00',
            'title' => 'plan1',
            'id_event' => $m1->id
        ));
        $p1 = $wpdb->get_row('SELECT * FROM ai_country_plan WHERE id=1 LIMIT 1');
        $this->assertNotNull($p1);

        $wpdb->insert('ai_country_plan', array(
            'original_id' => '2',
            'id_country' => $c1->id,
            'id_treaty' => $t1->id,
            'submission' => '1999-12-23 00:00:00',
            'title' => 'plan2',
            'id_event' => $m1->id
        ));
        $p2 = $wpdb->get_row('SELECT * FROM ai_country_plan WHERE id=2 LIMIT 1');
        $this->assertNotNull($p2);

        $ob = new imea_countries_page();
        $rows = $ob->get_national_plans($c1->id);
        $this->assertNotNull($rows);
        $this->assertEquals(1, count($rows));

        $rt1 = current($rows);
        $this->assertNotNull($rt1);
        $this->assertEquals($t1->id, $rt1->id);
        $this->assertEquals(2, count($rt1->national_plans));

        $rp1 = current($rt1->national_plans);
        $this->assertEquals($p2->id, $rp1->id);
        $this->assertEquals($m1->id, $rp1->id_event);
        $this->assertEquals($m1->title, $rp1->meeting_title);

        $rp2 = next($rt1->national_plans);
        $this->assertEquals($p1->id, $rp2->id);
        $this->assertEquals($m1->title, $rp2->meeting_title);
    }


    function test_count_national_plans() {
        global $wpdb;

        $t1 = $this->create_treaty();
        $c1 = $this->create_country();

        $wpdb->insert('ai_country_plan', array(
            'original_id' => '1',
            'id_country' => $c1->id,
            'id_treaty' => $t1->id,
            'title' => 'plan1'
        ));
        $p1 = $wpdb->get_row('SELECT * FROM ai_country_plan WHERE id=1 LIMIT 1');
        $this->assertNotNull($p1);

        $wpdb->insert('ai_country_plan', array(
            'original_id' => '2',
            'id_country' => $c1->id,
            'id_treaty' => $t1->id,
            'title' => 'plan2'
        ));
        $p2 = $wpdb->get_row('SELECT * FROM ai_country_plan WHERE id=2 LIMIT 1');
        $this->assertNotNull($p2);

        $ob = new imea_countries_page();
        $c = $ob->count_national_plans($c1->id);
        $this->assertEquals(2, $c);

        $c = $ob->count_national_plans(999);
        $this->assertEquals(0, $c);

        $c = $ob->count_national_plans();
        $this->assertEquals(0, $c);
    }


    function test_get_national_reports() {
        global $wpdb;

        $t1 = $this->create_treaty();
        $c1 = $this->create_country();
        $m1 = $this->create_meeting();

        $wpdb->insert('ai_country_report', array(
            'original_id' => '1',
            'id_country' => $c1->id,
            'id_treaty' => $t1->id,
            'submission' => '1992-12-23 00:00:00',
            'title' => 'report1',
            'id_event' => $m1->id
        ));
        $p1 = $wpdb->get_row('SELECT * FROM ai_country_report WHERE id=1 LIMIT 1');
        $this->assertNotNull($p1);

        $wpdb->insert('ai_country_report', array(
            'original_id' => '2',
            'id_country' => $c1->id,
            'id_treaty' => $t1->id,
            'submission' => '1999-12-23 00:00:00',
            'title' => 'report2',
            'id_event' => $m1->id
        ));
        $p2 = $wpdb->get_row('SELECT * FROM ai_country_report WHERE id=2 LIMIT 1');
        $this->assertNotNull($p2);

        $ob = new imea_countries_page();
        $rows = $ob->get_national_reports($c1->id);
        $this->assertNotNull($rows);
        $this->assertEquals(1, count($rows));

        $rt1 = current($rows);
        $this->assertNotNull($rt1);
        $this->assertEquals($t1->id, $rt1->id);
        $this->assertEquals(2, count($rt1->national_reports));

        $rp1 = current($rt1->national_reports);
        $this->assertEquals($p2->id, $rp1->id);
        $this->assertEquals($m1->id, $rp1->id_event);
        $this->assertEquals($m1->title, $rp1->meeting_title);

        $rp2 = next($rt1->national_reports);
        $this->assertEquals($p1->id, $rp2->id);
        $this->assertEquals($m1->title, $rp2->meeting_title);
    }


    function test_count_national_reports() {
        global $wpdb;

        $t1 = $this->create_treaty();
        $c1 = $this->create_country();

        $wpdb->insert('ai_country_report', array(
            'original_id' => '1',
            'id_country' => $c1->id,
            'id_treaty' => $t1->id,
            'title' => 'report1'
        ));
        $p1 = $wpdb->get_row('SELECT * FROM ai_country_report WHERE id=1 LIMIT 1');
        $this->assertNotNull($p1);

        $wpdb->insert('ai_country_report', array(
            'original_id' => '2',
            'id_country' => $c1->id,
            'id_treaty' => $t1->id,
            'title' => 'report2'
        ));
        $p2 = $wpdb->get_row('SELECT * FROM ai_country_report WHERE id=2 LIMIT 1');
        $this->assertNotNull($p2);

        $ob = new imea_countries_page();
        $c = $ob->count_national_reports($c1->id);
        $this->assertEquals(2, $c);

        $c = $ob->count_national_reports(999);
        $this->assertEquals(0, $c);

        $c = $ob->count_national_reports();
        $this->assertEquals(0, $c);
    }


    function test_get_whc_sites() {
        global $wpdb;

        $org = $this->create_organization();
        $wpdb->insert('ai_treaty', array(
            'id_organization' => $org->id,
            'short_title' => 'WHC',
            'primary' => 1,
            'enabled' => 1,
            'order' => 1,
            'regional' => 1,
            'odata_name' => 'whc',
            'is_indexed' => 1,
            'region' => 'test'
        ));
        $t1 = $wpdb->get_row('SELECT * FROM ai_treaty LIMIT 1');
        $this->assertNotNull($t1);
        $c1 = $this->create_country();

        $wpdb->insert('ai_country_site', array(
            'original_id' => '1',
            'id_country' => $c1->id,
            'id_treaty' => $t1->id,
            'name' => 'site2'
        ));
        $wpdb->insert('ai_country_site', array(
            'original_id' => '2',
            'id_country' => $c1->id,
            'id_treaty' => $t1->id,
            'name' => 'site1'
        ));

        $ob = new imea_countries_page();
        $rows = $ob->get_whc_sites($c1->id);
        $this->assertNotNull($rows);
        $this->assertEquals(2, count($rows));
        $s1 = current($rows);
        $this->assertNotNull($s1);
        $this->assertEquals(2, $s1->id);
        $this->assertEquals('Romania', $s1->country_name);

        $s2 = next($rows);
        $this->assertNotNull($s2);
        $this->assertEquals(1, $s2->id);
        $this->assertEquals('Romania', $s1->country_name);
    }


    function test_count_whc_sites() {
        global $wpdb;

        $org = $this->create_organization();
        $wpdb->insert('ai_treaty', array(
            'id_organization' => $org->id,
            'short_title' => 'WHC',
            'primary' => 1,
            'enabled' => 1,
            'order' => 1,
            'regional' => 1,
            'odata_name' => 'whc',
            'is_indexed' => 1,
            'region' => 'test'
        ));
        $t1 = $wpdb->get_row('SELECT * FROM ai_treaty LIMIT 1');
        $this->assertNotNull($t1);
        $c1 = $this->create_country();

        $wpdb->insert('ai_country_site', array(
            'original_id' => '1',
            'id_country' => $c1->id,
            'id_treaty' => $t1->id,
            'name' => 'site2'
        ));
        $wpdb->insert('ai_country_site', array(
            'original_id' => '2',
            'id_country' => $c1->id,
            'id_treaty' => $t1->id,
            'name' => 'site1'
        ));

        $ob = new imea_countries_page();
        $this->assertEquals(2, $ob->count_whc_sites($c1->id));
    }


    function test_getramsar_sites() {
        global $wpdb;

        $org = $this->create_organization();
        $wpdb->insert('ai_treaty', array(
            'id_organization' => $org->id,
            'short_title' => 'Ramsar',
            'primary' => 1,
            'enabled' => 1,
            'order' => 1,
            'regional' => 1,
            'odata_name' => 'ramsar',
            'is_indexed' => 1,
            'region' => 'test'
        ));
        $t1 = $wpdb->get_row('SELECT * FROM ai_treaty LIMIT 1');
        $this->assertNotNull($t1);
        $c1 = $this->create_country();

        $wpdb->insert('ai_country_site', array(
            'original_id' => '1',
            'id_country' => $c1->id,
            'id_treaty' => $t1->id,
            'name' => 'site2'
        ));
        $wpdb->insert('ai_country_site', array(
            'original_id' => '2',
            'id_country' => $c1->id,
            'id_treaty' => $t1->id,
            'name' => 'site1'
        ));

        $ob = new imea_countries_page();
        $rows = $ob->get_ramsar_sites($c1->id);
        $this->assertNotNull($rows);
        $this->assertEquals(2, count($rows));
        $s1 = current($rows);
        $this->assertNotNull($s1);
        $this->assertEquals(2, $s1->id);
        $this->assertEquals(2, $s1->id);
        $this->assertEquals('Romania', $s1->country_name);

        $s2 = next($rows);
        $this->assertNotNull($s2);
        $this->assertEquals(1, $s2->id);
        $this->assertEquals('Romania', $s2->country_name);
    }


    function test_count_ramsar_sites() {
        global $wpdb;

        $org = $this->create_organization();
        $wpdb->insert('ai_treaty', array(
            'id_organization' => $org->id,
            'short_title' => 'Ramsar',
            'primary' => 1,
            'enabled' => 1,
            'order' => 1,
            'regional' => 1,
            'odata_name' => 'ramsar',
            'is_indexed' => 1,
            'region' => 'test'
        ));
        $t1 = $wpdb->get_row('SELECT * FROM ai_treaty LIMIT 1');
        $this->assertNotNull($t1);
        $c1 = $this->create_country();

        $wpdb->insert('ai_country_site', array(
            'original_id' => '1',
            'id_country' => $c1->id,
            'id_treaty' => $t1->id,
            'name' => 'site2'
        ));
        $wpdb->insert('ai_country_site', array(
            'original_id' => '2',
            'id_country' => $c1->id,
            'id_treaty' => $t1->id,
            'name' => 'site1'
        ));

        $ob = new imea_countries_page();
        $this->assertEquals(2, $ob->count_ramsar_sites($c1->id));
    }
}
