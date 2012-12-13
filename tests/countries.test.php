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
}