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
}