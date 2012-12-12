<?php

require_once WP_PLUGIN_DIR . '/imea_ai/imea.php';
require_once WP_PLUGIN_DIR . '/informea/imea.php';

class imea_countries_base_test extends InforMEABaseTest {

    public static $uploads_dir;
    public static $uploads_subdir;
    public static $tmpdir;
    public static $file1;
    public static $file2;
    public static $file1_uploaded;

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();

        $old = error_reporting();
        error_reporting(0);

        self::$tmpdir = '/tmp'; # @todo make platform independent
        self::$uploads_dir = self::$tmpdir . DIRECTORY_SEPARATOR . 'tmp_imea_ai_upload';
        mkdir(self::$uploads_dir);
        self::$uploads_subdir = self::$tmpdir . DIRECTORY_SEPARATOR . 'tmp_imea_ai_upload' . DIRECTORY_SEPARATOR . 'subdir';
        mkdir(self::$uploads_subdir);

        self::$file1 = self::$tmpdir . DIRECTORY_SEPARATOR . 'tmp_imea_ai_doc.rtf';
        file_put_contents(self::$file1, 'TEST - DELETE ME');
        self::$file2 = self::$tmpdir . DIRECTORY_SEPARATOR . 'tmp_imea_ai_doc';
        file_put_contents(self::$file2, 'TEST - DELETE ME');
        self::$file1_uploaded = self::$uploads_subdir . DIRECTORY_SEPARATOR . 'tmp_imea_ai_doc.rtf';

        error_reporting($old);
    }


    public static function tearDownAfterClass() {
        parent::tearDownAfterClass();

        $old = error_reporting();
        error_reporting(0);

        unlink(self::$file1);
        unlink(self::$file2);
        unlink(self::$file1_uploaded);
        rmdir(self::$uploads_subdir);
        rmdir(self::$uploads_dir);

        error_reporting($old);
    }


    function test_add_document() {
        $decision = $this->create_decision();
        $array = array(
            'original_id' => 'original_id',
            'mime' => 'xls',
            'url' => 'http://example.com',
            'id_decision' => $decision->id,
            'path' => 'subdir' . DIRECTORY_SEPARATOR . 'tmp_imea_ai_doc.rtf',
            'language' => 'en',
            'size' => 1234,
            'is_indexed' => 1,
            'filename' => 'tmp_imea_ai_doc.rtf'
        );

        $ob = new imea_page_base_page();
        $doc = $ob->add_document(self::$file1, $array, self::$uploads_dir);

        $this->assertNotNull($doc);
        $this->assertEquals(1, $doc->id);
        $this->assertEquals('original_id', $doc->original_id);
        $this->assertEquals('xls', $doc->mime);
        $this->assertEquals('http://example.com', $doc->url);
        $this->assertEquals(1, $doc->id_decision);
        $this->assertEquals('subdir' . DIRECTORY_SEPARATOR . 'tmp_imea_ai_doc.rtf', $doc->path);
        $this->assertEquals('en', $doc->language);
        $this->assertEquals(1234, $doc->size);
        $this->assertEquals(1, $doc->is_indexed);
        $this->assertEquals('tmp_imea_ai_doc.rtf', $doc->filename);
    }


    /**
     * Will fail because path is NULL
     *
     * @expectedException InforMEAException
     */
    function test_add_document_no_path() {
        $array = array('path' => NULL);

        $ob = new imea_page_base_page();
        $ob->add_document(self::$file1, $array, self::$uploads_dir);
    }

    /**
     * Will fail because file is NULL
     *
     * @expectedException InforMEAException
     */
    function test_add_document_no_file() {
        $array = array('path' => 'valid path');

        $ob = new imea_page_base_page();
        $ob->add_document(NULL, $array, self::$uploads_dir);
    }

    /**
     * Will fail because uploads_dir is invalid
     *
     * @expectedException InforMEAException
     */
    function test_add_document_no_uploads_dir() {
        $array = array('path' => 'valid path');

        $ob = new imea_page_base_page();
        $ob->add_document(self::$file1, $array, NULL);
    }

    /**
     * Will fail because file is NULL
     *
     * @expectedException InforMEAException
     */
    function test_add_document_invalid_destination() {
        $array = array('path' => '/test/invalid/path');

        $ob = new imea_page_base_page();
        $ob->add_document(self::$file1, $array, self::$uploads_dir);
    }


    /**
     * Will fail because file is NULL
     *
     * @expectedException InforMEAException
     */
    function test_add_document_invalid_mime() {
        $array = array(
            'path' => 'subdir' . DIRECTORY_SEPARATOR . 'tmp_imea_ai_doc.rtf'
        );

        $ob = new imea_page_base_page();
        $ob->add_document(self::$file2, $array, self::$uploads_dir);
    }

    function test_add_document_size() {
        $array = array(
            'path' => 'subdir' . DIRECTORY_SEPARATOR . 'tmp_imea_ai_doc.rtf'
        );

        $ob = new imea_page_base_page();
        $doc = $ob->add_document(self::$file1, $array, self::$uploads_dir);
        $this->assertNotNull($doc);
        $this->assertEquals(16, $doc->size);
    }


    function test_add_document_filename() {
        $array = array(
            'path' => 'subdir' . DIRECTORY_SEPARATOR . 'tmp_imea_ai_doc.rtf'
        );

        $ob = new imea_page_base_page();
        $doc = $ob->add_document(self::$file1, $array, self::$uploads_dir);
        $this->assertNotNull($doc);
        $this->assertEquals('tmp_imea_ai_doc.rtf', $doc->filename);
    }
}