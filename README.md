# dynamic/silverstripe-collection

[![Build Status](https://travis-ci.org/dynamic/silverstripe-collection.svg?branch=1.0)](https://travis-ci.org/dynamic/silverstripe-collection)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dynamic/silverstripe-collection/badges/quality-score.png?b=1.0)](https://scrutinizer-ci.com/g/dynamic/silverstripe-collection/?branch=1.0)
[![Code Coverage](https://scrutinizer-ci.com/g/dynamic/silverstripe-collection/badges/coverage.png?b=1.0)](https://scrutinizer-ci.com/g/dynamic/silverstripe-collection/?branch=1.0)
[![Build Status](https://scrutinizer-ci.com/g/dynamic/silverstripe-collection/badges/build.png?b=1.0)](https://scrutinizer-ci.com/g/dynamic/silverstripe-collection/build-status/1.0)
[![codecov](https://codecov.io/gh/dynamic/silverstripe-collection/branch/1.0/graph/badge.svg)](https://codecov.io/gh/dynamic/silverstripe-collection)

Display a filterable collection of pages or dataobjects on a page.

## Requirements

- SilverStripe ^3.2

## Installation

`composer require dynamic/silverstripe-collection`

## Configuration

In your config.yml:

```
ExamplePage_Controller:
	managed_object: ExampleObject
	page_size: 10
	extensions:
		- CollectionExtension
```

## Managed Page/DataObject

Collection will create a search form based on the managed object's `$searchable_fields`. 

```
priavte static $searchable_fields = [
	'Title' => [
		'title' => 'Name',
	],
	'Category.ID' => [
		'title' => 'Category',
	],
];
```

For advanced setups, you can also create `getCustomSearchContext()` on your managed object.

To include a sorting dropdown field, create a `getSortOptions()` method on your managed object:

```
public function getSortOptions()
    {
        return array(
            'Created' => 'Date',
            'Title' => 'Name A-Z',
            'Title DESC' => 'Name Z-A',
        );
    }
```

## Templates

`$CollectionSearchForm` will display the search form.

You have mutliple options to loop through the results in your template:

* `$Collection` will display a list of all results
* `$PaginatedList` will paginate the results
* `$GroupedList.GroupedBy(CategoryTitle)` will display results grouped by the variable you pass

## Documentation

See the [docs/en](docs/en/index.md) folder.
