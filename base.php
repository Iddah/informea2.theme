<?php
if (!class_exists('InforMEAException')) {
    class InforMEAException extends Exception {

        public function __construct($message, $code = 0, Exception $previous = null) {
            parent::__construct($message, $code, $previous);
        }
    }
}

if (!class_exists('imea_page_base_page')) {
    class imea_page_base_page {

        /** Parameters accepted by the page - Used by the paginator */
        protected $req_parameters = array();
        /** Paginator that provides the results */
        protected $paginator = NULL;

        /** Variables used inside administration area */
        public $actioned = FALSE;
        public $action = FALSE;
        public $success = FALSE;
        public $errors = array();
        public $insert_id = NULL;

        /** End */


        /**
         * Construct new object
         * @param arr_parameters HTTP GET parameters specific to this page. They are used to reconstruct the URL for pagination links.
         */
        function __construct($arr_parameters = array()) {
            $this->req_parameters = $arr_parameters;
        }


        /**
         * Generate the page title (h1 content)
         *
         * @return string String with page title
         */
        public function get_page_title() {
            return 'Title not implemented';
        }

        static public function get_page_description($post, $prefix = '<p>', $suffix = '</p>') {
            $ret = '';
            if(!empty($post->ID)) {
                $meta = get_post_meta($post->ID);
                if(!empty($meta['description'][0])) {
                    $ret = $prefix . $meta['description'][0] . $suffix;
                }
            }
            return $ret;
        }

        static function get_action() {
            return get_request_variable('action');
        }


        /**
         * Retrieve request POST parameter
         * @param name of the parameter
         * @return parameter value or empty string if not set
         */
        function get_value($name, $strip_slashes = FALSE) {
            $ret = isset($_POST[$name]) ? $_POST[$name] : NULL;
            if ($strip_slashes) {
                $ret = stripslashes($ret);
            }
            return $ret;
        }

        /**
         * Echo the request parameter
         * @param name of the parameter
         * @return Nothing
         */
        function get_value_e($name, $strip_slashes = FALSE) {
            echo $this->get_value($name, $strip_slashes);
        }


        /**
         * Retrieve the data paginator object.
         * @see paginated_query class
         * @return paginated_query object
         */
        function get_paginator() {
            return $this->paginator;
        }

        /**
         * This controller method specifies if we are on index page of the section or not.
         * Override on extending classes.
         * @return True if index page.
         */
        function is_index() {
            return true;
        }

        /**
         */
        function check_404() {
        }


        /**
         * Generate 404 - Not found. This must be called *before* sending any content
         *
         * @global object $wp_query Global WordPress query object
         */
        function go_404() {
            global $wp_query;

            header('HTTP/1.0 404 Not Found - Archive Empty');
            $wp_query->set_404();
            require TEMPLATEPATH . DIRECTORY_SEPARATOR . '404.php';
            exit;
        }

        /**
         * Retrieve GET parameter or NULL
         */
        function req_get($name, $default = NULL) {
            $ret = $default;
            if (isset($_GET[$name]) && $_GET[$name] != '') {
                $ret = $_GET[$name];
            }
            return $ret;
        }

        /**
         * Retrieve POST parameter or NULL
         */
        function req_post($name, $default = NULL) {
            $ret = $default;
            if (isset($_POST[$name]) && $_POST[$name] != '') {
                $ret = $_POST[$name];
            }
            return $ret;
        }

        /**
         * This function gets the meetings from all MEA nodes. Two upcoming meetings from each MEA recorded in the events table
         * @returnList of ai_event objects
         */
        function get_meetings($id_treaty = NULL) {
            global $wpdb;
            $ret = array();
            if (!empty($id_treaty)) {
                $ret = $wpdb->get_results("SELECT a.*, b.short_title AS treaty_short_title, b.logo_medium FROM ai_event a
                    INNER JOIN ai_treaty b ON a.id_treaty = b.id WHERE a.id_treaty = $id_treaty AND a.start >= CURRENT_DATE() ORDER BY a.start ASC LIMIT 5");
            } else {
                $treaties = $wpdb->get_col('SELECT DISTINCT(id_treaty) FROM ai_event');
                foreach ($treaties as $id_treaty) {
                    $meetings = $wpdb->get_results("SELECT a.*, b.short_title AS treaty_short_title, b.logo_medium FROM ai_event a
                          INNER JOIN ai_treaty b ON a.id_treaty = b.id
                          WHERE a.id_treaty = $id_treaty AND a.start >= CURRENT_DATE() ORDER BY a.start ASC LIMIT 2");
                    $ret = array_merge($ret, $meetings);
                }
            }
            return $ret;
        }

        function get_meetings_for_ids($ids) {
            global $wpdb;
            $ret = array();
            $ret = $wpdb->get_results("SELECT a.*, b.short_title AS treaty_short_title, b.logo_medium FROM ai_event a INNER JOIN ai_treaty b ON a.id_treaty = b.id WHERE a.id_treaty IN ($ids) AND a.start >= CURRENT_DATE() ORDER BY a.start ASC LIMIT 5");
            return $ret;
        }


        static function get_popular_terms($id_treaty = NULL, $limit = 10) {
            global $wpdb;
            $sql = "SELECT * FROM voc_concept ORDER BY popularity DESC LIMIT $limit";
            if ($id_treaty !== NULL) {
                $sql = "SELECT * FROM voc_concept WHERE id IN (
                            SELECT distinct(id_concept) FROM ai_treaty_vocabulary WHERE id_treaty = $id_treaty
                            UNION
                                SELECT distinct(id_concept) FROM ai_treaty_article_paragraph_vocabulary a
                                    INNER JOIN ai_treaty_article b ON a.id_treaty_article_paragraph = b.id
                                    WHERE b.id_treaty = $id_treaty
                            UNION
                                SELECT distinct(id_concept) FROM ai_treaty_article_vocabulary a
                                    INNER JOIN ai_treaty_article b ON a.id_treaty_article = b.id
                                    WHERE b.id_treaty = $id_treaty
                        ) ORDER BY popularity DESC LIMIT " . $limit * 4 . "";
            }
            $terms = $wpdb->get_results($sql);
            $terms = array_slice($terms, 0, $limit);
            return $terms;
        }

        function get_popular_terms_for_ids($ids, $limit = 10) {
            global $wpdb;
            $sql = "SELECT * FROM voc_concept ORDER BY popularity DESC LIMIT $limit";
            if ($ids !== NULL) {
                $sql = "SELECT * FROM voc_concept WHERE id IN (
                            SELECT distinct(id_concept) FROM ai_treaty_vocabulary WHERE id_treaty in($ids)
                            UNION
                                SELECT distinct(id_concept) FROM ai_treaty_article_paragraph_vocabulary a
                                    INNER JOIN ai_treaty_article b ON a.id_treaty_article_paragraph = b.id
                                    WHERE b.id_treaty in($ids)
                            UNION
                                SELECT distinct(id_concept) FROM ai_treaty_article_vocabulary a
                                    INNER JOIN ai_treaty_article b ON a.id_treaty_article = b.id
                                    WHERE b.id_treaty in($ids)
                        ) ORDER BY popularity DESC LIMIT " . $limit * 4 . "";
            }
            $terms = $wpdb->get_results($sql);
            $terms = array_slice($terms, 0, $limit);
            return $terms;
        }

        /**
         * Compute the popularity based on a 1 - 10 scale
         * @return array Terms with popularity fixed
         */
        static function compute_popularity($terms) {
            if (!empty($terms)) {
                $greatest = 0;
                foreach ($terms as $term) {
                    if ($term->popularity > $greatest) {
                        $greatest = $term->popularity;
                    }
                }
                if ($greatest == 0) {
                    $greatest = 1;
                }
                foreach ($terms as &$term) {
                    $term->popularity = ceil($term->popularity * 10 / $greatest);
                    if ($term->popularity == 0) {
                        $term->popularity = 1;
                    }
                }
            }
            return $terms;
        }


        function array_unique_terms($terms) {
            $ret = array();
            $added = array();
            foreach ($terms as $term) {
                if (!in_array($term->id, $added)) {
                    $ret[] = $term;
                    $added[] = $term->id;
                }
            }
            return $ret;
        }


        function get_decision_documents($id_decision) {
            global $wpdb;
            $ret = array();
            $sql = "SELECT * FROM ai_document WHERE id_decision = $id_decision";
            $docs = $wpdb->get_results($sql);
            foreach ($docs as $doc) {
                $ob = new StdClass();
                $ob->id = $doc->id;
                $url = get_bloginfo('template_directory') . '/images/';
                if ($doc->mime == 'pdf') {
                    $url .= 'pdf.png';
                } else {
                    if ($doc->mime == 'doc') {
                        $url .= 'doc.png';
                    } else {
                        if ($doc->mime == 'xls') {
                            $url .= 'xls.png';
                        } else {
                            $url .= 'file.png';
                        }
                    }
                }
                $ob->icon_url = $url;
                $ob->url = $doc->url;
                $ob->language = $doc->language;

                // File size
                $file = $_SERVER['DOCUMENT_ROOT'] . '/' . $doc->path;
                $file_size = 'TODO KB';
                $ob->file_size = $file_size;

                $ret[] = $ob;
            }

            return $ret;
        }

        function get_decision_tags($id_decision) {
            global $wpdb;
            $sql = $wpdb->prepare('SELECT a.* FROM voc_concept a
                    INNER JOIN ai_decision_vocabulary b ON a.id = b.id_concept
                    WHERE id_decision = %d
                UNION
                SELECT a.* FROM voc_concept a
                    INNER JOIN ai_decision_paragraph_vocabulary b ON a.id = b.id_concept
                    INNER JOIN ai_decision_paragraph c ON b.id_decision_paragraph = c.id
                    WHERE c.id_decision = %d', $id_decision, $id_decision);
            return $wpdb->get_results($sql);
        }

        function get_month_meetings($month, $year) {
            global $wpdb;
            $sql = "SELECT a.*, c.id AS id_treaty, c.logo_medium, c.short_title AS treaty_short_title
                    FROM ai_event a
                    INNER JOIN ai_treaty c ON a.id_treaty = c.id
                WHERE a.end >= STR_TO_DATE('$month/1/$year', '%m/%d/%Y')
                    AND a.start <= STR_TO_DATE('$month/31/$year', '%m/%d/%Y')";
            return $wpdb->get_results($sql);
        }

        function get_contact_for_id($id) {
            global $wpdb;
            $ret = array();
            $sql = "SELECT * FROM view_people_treaty WHERE id = {$id}";
            return $wpdb->get_row($sql);
        }


        protected function check_db_error() {
            global $wpdb;
            if (strlen($wpdb->last_error) > 0) {
                $trace = '';
                try {
                    throw new Exception('dummy');
                } catch (Exception $e) {
                    $btrace = debug_backtrace();
                    $trace = $btrace[0];
                    $trace = print_r($trace, true);
                }
                throw new InforMEAException('<div class="sql-error">SQL statement failed: <pre>' . $wpdb->last_error . '(' . $wpdb->last_query . ')</pre><br /><pre>' . $trace . '</pre></div>');
            }
        }

        /** !!!!!!!!!!!!!!!!!!!!!! ADMINISTRATION AREA SPECIFIC !!!!!!!!!!!!!!!!!!!!!! */

        /**
         * Do the security check for forms and echo unauthorized message if not correct
         * @param $nonce_field Nonce field, see Wordpress nonce definition, http://codex.wordpress.org/WordPress_Nonces
         * @return TRUE if security is OK
         */
        function security_check($nonce_field) {
            if (!check_admin_referer($nonce_field)) {
                echo('<p>You are not authorized to access this page</p>');
                return FALSE;
            }
            return TRUE;
        }


        /**
         * Insert a record inside ai_activity_log table.
         * @param string $operation Possible values for operation (insert, update or delete)
         * @param string $section Section affected by the user, for example: vocabulary, treaty, decision etc. Do not invent new sections if some already exist. Look first.
         * @param string $username User that created the action (WordPress username)
         * @param string $description Description of the operation, for example: "Added tags a, b, c to article 'Article 2', paragraph 4". Send something meaningful and readable.
         * @param string $link Link to online version of the affected entity (if available).
         * @return boolean TRUE if successs, FALSE otherwise
         */
        function add_activity_log($operation, $section, $description, $username = NULL, $link = NULL) {
            global $wpdb;
            global $current_user;

            if ($username !== NULL) {
                $user = $username;
            } else {
                $user = $current_user->user_login;
            }
            $wpdb->insert('ai_activity_log', array(
                    'operation' => $operation,
                    'section' => $section,
                    'username' => $user,
                    'description' => $description,
                    'url' => $link
                )
            );
        }


        /**
         * Assign a document to a decision. Uses copy(), NOT move_uploaded_file. Extend.
         *
         * @todo Extend this to use movbe_uploaded_file if $file comes from HTTP POST.
         *
         * @param string $file Path to the file on disk
         * @param array $array Array with document properties (columns).
         * Supported keys:
         * - original_id
         * - mime (doc, xls, pdf, odt, rtf). Guessed if not specified. Exception is thrown if it cannot be recognized
         * - url
         * - id_decision
         * - path - (Required) Path relative to Wordpress 'uploads' directory.
         * - language (ISO code or language in english)
         * - size - file size - Guessed from existing file
         * - is_indexed - 0/1
         * - filename - Guessed from existing file
         *
         * Internal fields - Set by this method
         * - rec_created
         * - rec_author
         *
         * Internal fields - Set to NULL by this method
         * - rec_updated
         * - rec_updated_author
         * @param string $uploads_dir - Wordpress uploads dir (full path is $uploads_dir + $array[path])
         *
         * @return object document object or FALSE if error occurred
         *
         */
        function add_document($file, $array, $uploads_dir) {
            global $wpdb;
            global $current_user;

            if (empty($array['path'])) {
                throw new InforMEAException(sprintf('Path not specified (%s)', print_r($array, TRUE)));
            }
            if (empty($file) || !is_file($file) || !is_readable($file)) {
                throw new InforMEAException(sprintf('Source file is invalid (%s)', $file));
            }
            if (empty($uploads_dir) || !is_dir($uploads_dir) || !is_writable($uploads_dir)) {
                throw new InforMEAException(sprintf('Target directory is invalid (%s)', $uploads_dir));
            }

            $destination = $uploads_dir . DIRECTORY_SEPARATOR . $array['path'];
            try {
                copy($file, $destination);
            } catch (Exception $e) {
                throw new InforMEAException(sprintf('Cannot copy file %s to %s (%s)', $file, $destination, $e->getMessage()));
            }

            // Guess optional values if not set
            if (empty($array['mime'])) {
                $array['mime'] = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            }
            if (empty($array['mime']) || !in_array($array['mime'], array('doc', 'xls', 'pdf', 'odt', 'rtf', 'htm', 'html', 'txt'))) {
                throw new InforMEAException(sprintf('Invalid MIME type for file (%s)', $file));
            }

            if (empty($array['size'])) {
                $array['size'] = filesize($file);
            }
            if (empty($array['filename'])) {
                $tmp = pathinfo($file, PATHINFO_BASENAME);
                $array['filename'] = $tmp;
            }

            $this->success = false;
            $rec_created = date('Y-m-d H:i:s', strtotime('now'));
            $user = $current_user->user_login;
            $data = array_merge(array('rec_created' => $rec_created, 'rec_author' => $user), $array);

            $wpdb->insert('ai_document', $data);
            $this->check_db_error();
            $id_document = $wpdb->insert_id;
            $document = $wpdb->get_row($wpdb->prepare('SELECT * FROM ai_document WHERE id=%d', $id_document));
            $this->success = $document !== NULL;
            return $document;
        }
    }
}


if (!class_exists('UNDataWebsiteParser')) {

    /**
     * Parse content from UN Data Website results page
     * i.e. http://data.un.org/CountryProfile.aspx?crName=Romania
     */
    class UNDataWebsiteParser {
        private $url = null;
        private $curl_timeout = 10;

        private $id_country = null;
        private $country_name = null;

        private $html = null;
        private $doc = null;

        private $WWW_URL = 'http://data.un.org/CountryProfile.aspx?crName=';
        private $WWW_IMG_URL = 'http://data.un.org/';

        private $img = null;
        private $environment = null;
        private $parsed = false;

        /**
         * @param string $id_country - Country internal ID
         * @param string $country_name - Country name
         */
        public function __construct($id_country, $country_name) {
            $this->id_country = $id_country;
            $this->country_name = $country_name;

            $this->url = $this->WWW_URL . $country_name;
            // Check cache
            $this->check_cache($id_country, $country_name);
        }

        protected function check_cache() {
            global $wpdb;
            // Purge old records from cache
            $wpdb->query('DELETE FROM ai_cache WHERE `created` < DATE_SUB(NOW(), INTERVAL 14 DAY)');
            // Look for our data
            $img = $wpdb->get_row($wpdb->prepare('SELECT * FROM ai_cache WHERE id=%s AND domain=%s', $this->id_country, 'un-img-country'));
            if ($img != null) {
                $this->img = $img->value;
            }

            $environment = $wpdb->get_row($wpdb->prepare('SELECT * FROM ai_cache WHERE id=%s AND domain=%s', $this->id_country, 'un-img-environment'));
            if ($environment != null) {
                $this->environment = $environment->value;
            }
        }

        protected function get_remote_dom() {
            //echo "Retrieving the HTML content from {$this->url}\n";
            if ($this->parsed) {
                return $this->doc;
            }
            $ch = curl_init();
            $this->parsed = true;
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->curl_timeout);
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15 InforMEA"));
            curl_setopt($ch, CURLOPT_NOBODY, 0);
            $this->html = curl_exec($ch);
            curl_close($ch);

            //echo "    * Parsing the HTML content\n";
            if (!empty($this->html)) {
                $d = new DOMDocument();
                $d->strictErrorChecking = false;
                $d->recover = true;
                libxml_use_internal_errors(true);
                $d->loadHTML($this->html);
                libxml_use_internal_errors(false);
                $this->doc = $d;
                return $d;
            }
            return null;
        }


        /**
         * Returns the country map image url.
         * @return string URL
         */
        public function get_map_image() {
            if (!empty($this->img)) {
                return $this->img;
            }
            $ret = null;
            $doc = $this->get_remote_dom();
            if ($doc) {
                $cn = $this->doc->getElementById('ctl00_main_MapSection');
                if ($cn) {
                    $imgs = $cn->getElementsByTagName('img');
                    if ($imgs->length > 0) {
                        $ret = $imgs->item(0);
                        $ret = $this->WWW_IMG_URL . $ret->getAttribute('src');
                        $this->img = $ret;

                        // Populate the cache
                        global $wpdb;
                        $wpdb->insert('ai_cache', array('id' => $this->id_country, 'domain' => 'un-img-country', 'value' => $ret));
                    }
                }
            }
            return $ret;
        }

        /**
         * Returns the Environment section from country profile
         * @return string HTML
         */
        public function get_environmental_data() {
            if (!empty($this->environment)) {
                return $this->environment;
            }
            $ret = null;
            $doc = $this->get_remote_dom();
            if ($doc) {
                $cn = $this->doc->getElementById('Environment');
                if ($cn) {
                    $table = $cn->nextSibling->nextSibling;
                    if ($table && $table->nodeName == 'table') {
                        $newdoc = new DOMDocument();
                        $cloned = $table->cloneNode(true);
                        $newdoc->appendChild($newdoc->importNode($cloned, true));
                        $ret = $newdoc->saveHTML();
                        $this->environment = $ret;

                        // Populate the cache
                        global $wpdb;
                        $wpdb->insert('ai_cache', array('id' => $this->id_country, 'domain' => 'un-img-environment', 'value' => $ret));
                    }
                }
            }
            return $ret;
        }

        /**
         * Returns the raw HTML as was loaded from Ecolex website
         */
        public function get_raw_html() {
            $this->parse_html();
            return $this->html;
        }
    }
}
?>
