<?php

namespace Dynamic\Collection\Test\TestOnly;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;

class TestCollectionObject extends DataObject implements TestOnly
{
    /**
     * @var array
     */
    private static $db = [
        'Title' => 'Varchar(255)',
    ];

    /**
     * @return array
     */
    public function getSortOptions()
    {
        return array(
            'Created' => 'Date',
            'Title' => 'Name A-Z',
            'Title DESC' => 'Name Z-A',
        );
    }
}