<?php

if(!class_exists('EcolexParser')) {
/**
 * This class handles Ecolex content
 */
class EcolexParser {
	private $url = null;
	private $curl_timeout = 10;
	private $page_url = null;

	private $html = null;
	private $parsed = false;
	private $doc = null;

	public static $ECOLEX_ORG = 'http://ecolex.org';
	public static $WWW_ECOLEX_ORG = 'http://www.ecolex.org';

	/**
	 * @param string $url - URL from Ecolex to parse
	 * @param string $page_url - URL to modify links to point to (links from paginator and sorter)
	 */
	public function __construct($url = '', $page_url = '') {
		$this->url = $url;
		$this->page_url = $page_url;
        if(!empty($this->url)) {
            $this->security_check($url);
        }
	}

    public function security_check($url) {
        if(!(stripos($url, self::$WWW_ECOLEX_ORG) === 0
                || stripos($url, self::$ECOLEX_ORG) === 0)) {
            die('Possible injection attempt, aborting!');
        }
    }

	protected function get_remote_html() {
		//echo "Retrieving the HTML content from {$this->url}\n";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->curl_timeout);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15 InforMEA") );
		curl_setopt($ch, CURLOPT_NOBODY, 0);
		$this->html = curl_exec($ch);
		curl_close($ch);

		//@todo: check for errors or empty results before going further.
		// ""

		// Remove <meta name="keywords" as contains non UTF-8 characters
		$this->html = preg_replace('/\<meta name="keywords" content=".*">/', '', $this->html);

		// Replace & with &amp; (only when not followed by n (&nbsp;) or a (&amp;)
		$this->html = preg_replace('/&(![n,a])/', '&amp;', $this->html);

		// Remove the "clear" div
		$this->html = preg_replace('/<div class="clear"><\/div>/', '', $this->html);

		return $this->html;
	}

	protected function parse_html() {
		if(!$this->parsed) {
			$this->parsed = true;
			$this->get_remote_html();
			$d = new DOMDocument();
			$d->strictErrorChecking = false;
			$d->recover = true;
			//echo "    * Parsing the HTML content\n";

			libxml_use_internal_errors(true);
			$d->loadHTML($this->html);
			libxml_use_internal_errors(false);

			$this->doc = $d;
		}
	}

	protected function get_content_div() {
		$ret = null;
		$this->parse_html();
		$cn = $this->doc->getElementById('content');
		if($cn) {
			$newdoc = new DOMDocument();
			$cloned = $cn->cloneNode(true);
			$this->fix_url_informea($cloned);

			$newdoc->appendChild($newdoc->importNode($cloned, true));
			//echo "    * Extract the content div\n";
			$ret = $newdoc->saveHTML();
		}
		return $ret;
	}

	protected function fix_url_informea(&$node) {
		// Fix the links from sorter table
		$tables = $node->getElementsByTagName('table');
		if($tables->length > 0) {
			//echo "    * Found <table>, now fixing links inside\n";
			$table = $tables->item(0);
			foreach($table->getElementsByTagName('a') as $a) {
				$a->setAttribute('href', $this->page_url . '?next=' . urlencode(urlencode(self::$ECOLEX_ORG . $a->getAttribute('href'))));
			}
		}

		// Fix the links from pager paginator
		$spans = $node->getElementsByTagName('span');
		foreach($spans as $span) {
			if($span->getAttribute('class') == 'table-pager') {
				//echo "    * Found <span class='table-pager'>, now fixing links inside\n";
				foreach($span->getElementsByTagName('a') as $a) {
					$a->setAttribute('href', $this->page_url . '?next=' . urlencode(urlencode(self::$ECOLEX_ORG . $a->getAttribute('href'))));
				}
			}
		}

		// Fix the links from results to open in new tab/window
		$uls = $node->getElementsByTagName('ul');
		if($uls->length > 0) {
			//echo "    * Found <ul>, now fixing links inside\n";
			$ul = $uls->item(0);
			foreach($ul->getElementsByTagName('a') as $a) {
				$a->setAttribute('target', '_blank');
				$attr = $a->getAttribute('href');
				if(strpos($attr, 'http') === false) {
					$a->setAttribute('href', self::$ECOLEX_ORG . $a->getAttribute('href'));
				}
			}
		}
	}

	/**
	 * Returns the HTML after being processed. May return NULL if parsing fails
	 * @return string Content as HTML
	 */
	public function get_content() {
		$ret = $this->get_content_div();
		$ret = preg_replace('/\<div id="content"\>/', '<div id="ecolex-content">', $ret);
		return $ret;
	}

	/**
	 * Returns the raw HTML as was loaded from Ecolex website
	 */
	public function get_raw_html() {
		$this->parse_html();
		return $this->html;
	}



    public function _dev_import_decisions($path, &$console = '') {
        $f = @file_get_contents($path);
        if(empty($f)) {
            $console .= sprintf("Failed to load file '$path'\n", $path);
            return;
        }
        $data = json_decode($f);
        echo
        $console .= sprintf("Import %d court decisions from $path\n", count($data), $path);
        foreach($data as $idx => $item) {
            $prop = 'input-fields';
            $row = $item->$prop;
            $prop = "Type of document";
            var_dump($row);
            $console .= $idx . ' ';
        }
        $console .= "\nDone\n";
    }
}
}
