<?php

namespace Dynamic\Collection\Test\TestOnly;

use SilverStripe\Dev\TestOnly;

class TestCollection extends \Page implements TestOnly
{
    /**
     * @var string
     */
    private static $table_name = 'Collection_TestCollection';
}
