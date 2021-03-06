# Silverstripe Collection

Display a filterable collection of pages or dataobjects on a page.

[![Build Status](https://travis-ci.org/dynamic/silverstripe-collection.svg?branch=master)](https://travis-ci.org/dynamic/silverstripe-collection)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dynamic/silverstripe-collection/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/dynamic/silverstripe-collection/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/dynamic/silverstripe-collection/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/dynamic/silverstripe-collection/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/dynamic/silverstripe-collection/badges/build.png?b=master)](https://scrutinizer-ci.com/g/dynamic/silverstripe-collection/build-status/master)
[![codecov](https://codecov.io/gh/dynamic/silverstripe-collection/branch/master/graph/badge.svg)](https://codecov.io/gh/dynamic/silverstripe-collection)

[![Latest Stable Version](https://poser.pugx.org/dynamic/silverstripe-collection/v/stable)](https://packagist.org/packages/dynamic/silverstripe-collection)
[![Total Downloads](https://poser.pugx.org/dynamic/silverstripe-collection/downloads)](https://packagist.org/packages/dynamic/silverstripe-collection)
[![Latest Unstable Version](https://poser.pugx.org/dynamic/silverstripe-collection/v/unstable)](https://packagist.org/packages/dynamic/silverstripe-collection)
[![License](https://poser.pugx.org/dynamic/silverstripe-collection/license)](https://packagist.org/packages/dynamic/silverstripe-collection)

## Requirements

- SilverStripe 4.x

## Installation

`composer require dynamic/silverstripe-collection`

## Configuration

In your config.yml:

```
Your/Namespace/ExamplePageController:
  managed_object: ExampleObject
  page_size: 10
  extensions:
    - Dynamic\Collection\CollectionExtension
```

## Managed Page/DataObject

Collection will create a search form based on the managed object's `$searchable_fields`. 

```
private static $searchable_fields = [
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
