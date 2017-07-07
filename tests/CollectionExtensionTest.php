<?php

namespace Dynamic\Collection\Test;

use Dynamic\Collection\Test\TestOnly\TestCollection;
use Dynamic\Collection\Test\TestOnly\TestCollectionController;
use Dynamic\Collection\Test\TestOnly\TestCollectionObject;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Forms\Form;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\GroupedList;
use SilverStripe\ORM\PaginatedList;

class CollectionExtensionTest extends FunctionalTest
{
    /**
     * @var string
     */
    protected static $fixture_file = array(
        'fixtures.yml',
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
    protected static $extra_dataobjects = array(
        TestCollection::class,
        TestCollectionController::class,
        TestCollectionObject::class,
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
        $object = $this->objFromFixture(TestCollection::class, 'default');
        $controller = new TestCollectionController($object);
        $this->assertInstanceOf(DataList::class, $controller->getCollection());

        $object = $controller->config()->managed_object;
        $this->assertInstanceOf($object, $controller->getCollection()->first());
    }

    /**
     *
     */
    public function testGetManagedObject()
    {
        $object = TestCollectionController::create();
        $expected = TestCollectionObject::class;
        $this->assertEquals($expected, $object->getCollectionObject());
    }

    /**
     *
     */
    public function testGetPageSize()
    {
        $object = TestCollectionController::create();
        $expected = 10;
        $this->assertEquals($expected, $object->getCollectionSize());
    }

    /**
     *
     */
    public function testPaginatedList()
    {
        $object = $this->objFromFixture(TestCollection::class, 'default');
        $controller = new TestCollectionController($object);
        $this->assertInstanceOf(PaginatedList::class, $controller->PaginatedList());
    }

    /**
     *
     */
    public function testGroupedList()
    {
        $object = $this->objFromFixture(TestCollection::class, 'default');
        $controller = new TestCollectionController($object);
        $this->assertInstanceOf(GroupedList::class, $controller->GroupedList());
    }

    /**
     *
     */
    public function testCollectionSearchForm()
    {
        $object = $this->objFromFixture(TestCollection::class, 'default');
        $controller = new TestCollectionController($object);
        $this->assertInstanceOf(Form::class, $controller->CollectionSearchForm());
    }
}