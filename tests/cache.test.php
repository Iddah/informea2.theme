<?php

require_once WP_PLUGIN_DIR . '/imea_ai/imea.php';
require_once WP_PLUGIN_DIR . '/informea/imea.php';

class imea_cache_testimpl extends imea_cache {

    public function __construct($db = NULL, $expire = NULL) {
        parent::__construct($db, $expire);
    }

    public function get_cache() {
        return $this->cache;
    }

    public function get_expire() {
        return $this->expire;
    }

    public function get_db() {
        return $this->db;
    }

    public function invalidate_cache() {
        parent::invalidate_cache();
    }


    public function refresh() {
        parent::refresh();
    }
}

class imea_cache_test extends InforMEABaseTest {

    function test_construct() {
        global $wpdb;

        $ob = new imea_cache();
        $this->assertNotNull($ob);

        $ob1 = new imea_cache_testimpl();
        $this->assertEquals(imea_cache::$EXPIRE_DEFAULT, $ob1->get_expire());
        $this->assertNotNull($ob1->get_db());

        $this->assertEquals($wpdb, $ob1->get_db());
        $this->assertNotNull($ob1->get_cache());

        $this->assertEquals(array(), $ob1->get_cache());

        $ob1 = new imea_cache_testimpl($wpdb, '15 DAY');
        $this->assertEquals('15 DAY', $ob1->get_expire());
        $this->assertNotNull($ob1->get_db());
        $this->assertEquals($wpdb, $ob1->get_db());

        $this->assertNotNull($ob1->get_cache());
        $this->assertEquals(array(), $ob1->get_cache());
    }


    function test_invalidate_cache() {
        global $wpdb;

        $old = date('Y-m-j H:', strtotime('-1 year'));
        $wpdb->insert('ai_cache',
            array('id' => 'id0', 'domain' => 'd0', 'value' => 'v0', 'created' => $old)
        );

        $old = date('Y-m-j H:', strtotime('-16 day'));
        $wpdb->insert('ai_cache',
            array('id' => 'id1', 'domain' => 'd1', 'value' => 'v1', 'created' => $old)
        );

        $new = date('Y-m-j H:', strtotime('-2 day'));
        $wpdb->insert('ai_cache',
            array('id' => 'id2', 'domain' => 'd2', 'value' => 'v2', 'created' => $new)
        );

        $new = date('Y-m-j H:', strtotime('-1 day'));
        $wpdb->insert('ai_cache',
            array('id' => 'id3', 'domain' => 'd3', 'value' => 'v3', 'created' => $new)
        );

        $rows = $wpdb->get_results('SELECT * FROM ai_cache');
        $this->assertEquals(4, count($rows));

        $ob = new imea_cache_testimpl($wpdb, '15 DAY');
        $ob->invalidate_cache();

        $rows = $wpdb->get_results('SELECT * FROM ai_cache');
        $this->assertEquals(2, count($rows));

        $r2 = current($rows);
        $this->assertEquals('id2', $r2->id);
        $this->assertEquals('d2', $r2->domain);
        $this->assertEquals('v2', $r2->value);

        $r3 = next($rows);
        $this->assertEquals('id3', $r3->id);
        $this->assertEquals('d3', $r3->domain);
        $this->assertEquals('v3', $r3->value);
    }


    function test_refresh() {
        global $wpdb;

        $wpdb->insert('ai_cache', array('id' => 'id1', 'domain' => 'd1', 'value' => 'vA'));
        $wpdb->insert('ai_cache', array('id' => 'id2', 'domain' => 'd1', 'value' => 'vB'));

        $wpdb->insert('ai_cache', array('id' => 'id1', 'domain' => 'd2', 'value' => 'vC'));
        $wpdb->insert('ai_cache', array('id' => 'id2', 'domain' => 'd2', 'value' => 'vD'));
        $wpdb->insert('ai_cache', array('id' => 'id3', 'domain' => 'd2', 'value' => 'vE'));

        $ob = new imea_cache_testimpl();
        $ob->refresh();
        $cache = $ob->get_cache();

        $this->assertEquals(2, count($cache));
        $this->assertTrue(isset($cache['d1']));
        $this->assertTrue(isset($cache['d2']));

        $d1 = $cache['d1'];
        $this->assertEquals(2, count($d1));
        $k1 = $d1['id1'];
        $this->assertEquals('vA', $k1->value);
        $k2 = $d1['id2'];
        $this->assertEquals('vB', $k2->value);

        $d2 = $cache['d2'];
        $this->assertEquals(2, count($d1));
        $k1 = $d2['id1'];
        $this->assertEquals('vC', $k1->value);
        $k2 = $d2['id2'];
        $this->assertEquals('vD', $k2->value);
        $k3 = $d2['id3'];
        $this->assertEquals('vE', $k3->value);
    }


    function test_get() {
        global $wpdb;

        $wpdb->insert('ai_cache', array('id' => 'id1', 'domain' => 'd1', 'value' => 'vA'));
        $wpdb->insert('ai_cache', array('id' => 'id2', 'domain' => 'd1', 'type' => 'jSoN', 'value' => '[{"key1" : "val1" }]'));
        $ob = new imea_cache_testimpl();

        $k = $ob->get('id1', 'd1');
        $this->assertEquals('vA', $k);

        $k = $ob->get('id2', 'd1');
        $this->assertNotNull($k);

        $comp = new StdClass();
        $comp->key1 = 'val1';
        $arr = array($comp);
        $this->assertEquals($arr, $k);

        // Direct SQL
        $ob = new imea_cache_testimpl();
        $k = $ob->get('id1', 'd1', TRUE);
        $this->assertEquals('vA', $k);
    }


    function test_put() {
        global $wpdb;

        $ob = new imea_cache_testimpl();
        $ob->put('id1', 'd1', 'v1');
        $row = $wpdb->get_row("SELECT * FROM ai_cache WHERE id='id1' AND domain='d1'");
        $this->assertNotNull($row);
        $this->assertEquals('v1', $row->value);

        $ob->put('id1', 'd1', 'v2');
        // Make sure we have 1 row, updated
        $i = $wpdb->get_var("SELECT COUNT(*) FROM ai_cache");
        $this->assertEquals(1, $i);
        $row = $wpdb->get_row("SELECT * FROM ai_cache WHERE id='id1' AND domain='d1'");
        $this->assertNotNull($row);
        $this->assertEquals('v2', $row->value);

        $v1 = $ob->get('id1', 'd1');
        $this->assertNotNull($v1);

        $ob->put('id2', 'd2', '{"a":"b"}', 'json');
        // check internal cache is synced
        $cache = $ob->get_cache();
        $v2a = $cache['d2']['id2'];
        $this->assertNotNull($v2a);
        $this->assertEquals('id2', $v2a->id);
        $this->assertEquals('d2', $v2a->domain);
        $this->assertEquals('json', $v2a->type);
        $this->assertEquals('{"a":"b"}', $v2a->value);

        // check db is synced
        $v2b = $wpdb->get_row("SELECT * FROM ai_cache WHERE id='id2' AND domain='d2'");
        $this->assertEquals('json', $v2b->type);
        $this->assertEquals('{"a":"b"}', $v2b->value);

        // check get works properly
        $value = $ob->get('id2', 'd2');
        $this->assertNotNull($value);
        $this->assertEquals('b', $value->a);

        // test unset
        $this->assertFalse(empty($cache['d2']['id2']));
        $ob->put('id2', 'd2', NULL);
        $cache = $ob->get_cache();
        $this->assertTrue(empty($cache['d2']['id2']));

        $v2 = $wpdb->get_row("SELECT * FROM ai_cache WHERE id='id2' AND domain='d2'");
        $this->assertNull($v2);

        $this->assertNull($ob->get('id2', 'd2', TRUE));
    }

    function test_put_json_array() {
        $value = array();
        $ob1 = new stdClass();
        $ob1->prop1 = 'one';
        $value[] = $ob1;
        $ob2 = new stdClass();
        $ob2->prop2 = 'two';
        $value[] = $ob2;

        $c = new imea_cache();
        $c->put('a', 'b', $value, 'json');

        $c = new imea_cache();
        $subject = $c->get('a', 'b');

        $this->assertEquals($value, $subject);
    }
}