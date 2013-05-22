<?php

if (!class_exists('imea_cache')) {

    /**
     * Cache manager built around ai_cache table.
     *
     * @author Cristian Romanescu <cristian.romanescu _at_ eaudeweb.ro>
     */
    class imea_cache {

        public static $EXPIRE_DEFAULT = '14 DAY';
        protected $expire = NULL;

        protected $db = NULL;
        protected $cache = array();


        /**
         * Construct new cache object
         *
         * @param $db Database layer, based on EzSQL/WordPress. Default global $wpdb from WordPress
         * @param $expire string Interval, in MySQL specific INTERVAL format. Default 14 days
         * Examples: '14 DAY', '1 MONTH', 'CAST(6/4 AS DECIMAL(3,1)) HOUR_MINUTE)' etc.
         */
        public function __construct($db = NULL, $expire = NULL) {
            if ($db) {
                $this->db = $db;
            } else {
                global $wpdb;
                $this->db = $wpdb;
            }
            $this->expire = empty($expire) ? self::$EXPIRE_DEFAULT : $expire;
        }


        /**
         * Check the cache and invalidate old content.
         * @see http://dev.mysql.com/doc/refman/5.5/en/date-and-time-functions.html
         */
        protected function invalidate_cache() {
            global $wpdb;

            // Purge old records from cache
            $this->db->query("DELETE FROM `ai_cache` WHERE `created` < DATE_SUB(NOW(), INTERVAL {$this->expire})");
        }


        /**
         * Reload cache from DB. Is used to lazy-load the cache on first access
         */
        protected function refresh() {
            $this->invalidate_cache();
            $rows = $this->db->get_results('SELECT * FROM `ai_cache`');
            $cache = array();
            foreach ($rows as $row) {
                $id = $row->id;
                $domain = $row->domain;
                if (!isset($cache[$domain])) {
                    $cache[$domain] = array();
                }
                if (!isset($cache[$domain][$id])) {
                    $cache[$domain][$id] = $row;
                }
            }
            $this->cache = $cache;
        }


        /**
         * Put a value into the cache.
         *
         * @param string $id Identifier
         * @param string $domain Identifier
         * @param string $value Actual value (may be string encoded JSON, numbers etc). If set to NULL, value is removed from cache. For JSON, encoding is handled inside
         * @param string $type (Optional) Type of data holding by value. Default string. Can be: string, double, integer, json
         */
        public function put($id, $domain, $value, $type = 'string') {
            if (empty($value)) {
                unset($this->cache[$domain][$id]);
                $this->db->query($this->db->prepare('DELETE FROM `ai_cache` WHERE id=%s AND domain=%s', $id, $domain));
                return;
            }

            $v = $value;
            if (strtolower($type) == 'json') {
                $v = json_encode($value);
            }

            $this->db->query(
                $this->db->prepare(
                    'REPLACE INTO `ai_cache` VALUES (%s, %s, %s, %s, NOW())', $id, $domain, $type, $v)
            );
            $ob = new stdClass();
            $ob->id = $id;
            $ob->domain = $domain;
            $ob->type = $type;
            $ob->value = $value;
            if (!isset($this->cache[$domain])) {
                $this->cache[$domain] = array();
            }
            $this->cache[$domain][$id] = $ob;
        }


        /**
         * Retrieve value from the cache.
         *
         * @param string $id Identifier
         * @param string $domain Identifier
         * @param boolean $use_sql If TRUE, internal is bypassed and does a query in cache table
         * @return mixed Actual cached value, decoded if type is json
         */
        public function get($id, $domain, $use_sql = FALSE) {
            if (empty($id) || empty($domain)) {

                return NULL;
            }
            $value = NULL;
            if (!$use_sql) {
                if (empty($this->cache)) {
                    $this->refresh();
                }
                if (isset($this->cache[$domain][$id])) {
                    $value = $this->cache[$domain][$id];
                }
            } else {
                $value = $this->db->get_row($this->db->prepare('SELECT * FROM `ai_cache` WHERE id=%s AND domain=%s LIMIT 1', $id, $domain));
            }
            $ret = null;
            if (!empty($value)) {
                switch (strtolower($value->type)) {
                    case 'json':
                        $ret = json_decode($value->value, TRUE);
                        break;
                    default:
                        $ret = $value->value;
                }
            }
            return $ret;
        }
    }
}

if (!class_exists('imea_request_cache')) {
    class imea_request_cache {

        public static function get_treaty_article($id) {
            static $treaty_article_cache = array();
            if(empty($treaty_article_cache)) {
                global $wpdb;
                $rows = $wpdb->get_results('SELECT * FROM ai_treaty_article');
                foreach($rows as $row) {
                    $treaty_article_cache[$row->id] = $row;
                }
            }
            if(isset($treaty_article_cache[$id])) {
                return $treaty_article_cache[$id];
            }
            return NULL;
        }


        public static function get_treaty($id) {
            static $treaty_cache = array();
            if(empty($treaty_cache)) {
                global $wpdb;
                $rows = $wpdb->get_results('SELECT * FROM ai_treaty');
                foreach($rows as $row) {
                    $treaty_cache[$row->id] = $row;
                }
            }
            if(isset($treaty_cache[$id])) {
                return $treaty_cache[$id];
            }
            return NULL;
        }
    }
}