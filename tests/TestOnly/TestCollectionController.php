<?php

namespace Dynamic\Collection\Test\TestOnly;

use Dynamic\Collection\CollectionExtension;
use Dynamic\Collection\Test\TestOnly\TestCollectionObject;
use SilverStripe\Dev\TestOnly;

class TestCollectionController extends \PageController implements TestOnly
{
    /**
     * @var string
     */
    private static $managed_object = TestCollectionObject::class;

    /**
     * @var array
     */
    private static $extensions = [
        CollectionExtension::class
    ];
}