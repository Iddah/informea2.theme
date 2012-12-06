<?php

require_once WP_PLUGIN_DIR . '/imea_ai/imea.php';
require_once WP_PLUGIN_DIR . '/informea/imea.php';

class imea_treaties_admin_test extends InforMEABaseTest {


    function get_organization_by_name() {
        $this->create_organization();

        $ob = new imea_treaties_page();
        $o = $ob->get_organization_by_name(" TEST \n");
        $this->assertNotNull($o);
        $this->assertEquals(1, $o->id);
        $this->assertEquals('TEST', $c->name);
    }
}