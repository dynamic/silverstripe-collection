<?php

class CollectionExtensionTest extends FunctionalTest
{
    /**
     * @var string
     */
    protected static $fixture_file = array(
        'core-tools/tests/CoreToolsTest.yml',
        'core-tools/tests/Fixtures.yml',
    );

    /**
     * @var bool
     */
    protected static $disable_themes = true;

    /**
     * @var bool
     */
    protected static $use_draft_site = false;

    /**
     * @var array
     */
    protected $extraDataObjects = array(
        'TestCollection',
        'TestCollection_Controller',
        'TestCollectionObject',
    );

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        ini_set('display_errors', 1);
        ini_set('log_errors', 1);
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
    }

    /**
     *
     */
    public function logOut()
    {
        $this->session()->clear('loggedInAs');
        $this->session()->clear('logInWithPermission');
    }

    /**
     *
     */
    public function testGetCollection()
    {
        $object = $this->objFromFixture('TestCollection', 'default');
        $controller = new TestPage_Controller($object);
        $this->assertInstanceOf('DataList', $controller->getCollection());

        $object = $controller->config()->managed_object;
        $this->assertInstanceOf($object, $controller->getCollection()->first());
    }

    /**
     *
     */
    public function testGetManagedObject()
    {
        $object = TestPage_Controller::create();
        $expected = 'TestCollectionObject';
        $this->assertEquals($expected, $object->getCollectionObject());
    }

    /**
     *
     */
    public function testGetPageSize()
    {
        $object = TestPage_Controller::create();
        $expected = 10;
        $this->assertEquals($expected, $object->getCollectionSize());
    }

    /**
     *
     */
    public function testPaginatedList()
    {
        $object = $this->objFromFixture('TestCollection', 'default');
        $controller = new TestPage_Controller($object);
        $this->assertInstanceOf('PaginatedList', $controller->PaginatedList());
    }

    /**
     *
     */
    public function testGroupedList()
    {
        $object = $this->objFromFixture('TestCollection', 'default');
        $controller = new TestPage_Controller($object);
        $this->assertInstanceOf('GroupedList', $controller->GroupedList());
    }

    /**
     *
     */
    public function testCollectionSearchForm()
    {
        $object = $this->objFromFixture('TestCollection', 'default');
        $controller = new TestPage_Controller($object);
        $this->assertInstanceOf('Form', $controller->CollectionSearchForm());
    }
}

/**
 * Class TestPage.
 */
class TestCollection extends Page implements TestOnly
{

}

class TestCollection_Controller extends Page_Controller implements TestOnly
{
    private static $managed_object = 'TestCollectionObject';

    private static $extensions = [CollectionExtension];
}

class TestCollectionObject extends Dataobject implements TestOnly
{

}