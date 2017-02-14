# dynamic/silverstripe-collection

## Advanced Options

CollectionExtension has various extension points where you can customize all aspects of the extension.

### Overwrite the managed object

Allows you to choose the managed object via CMS:

```
public function updateCollectionObject(&$object)
    {
        if ($class = $this->owner->data()->ManagedClass) {
            $object = (string) $class;
        }
    }
```

### Additional filters

Only show those records that are children of the Collection page:

```
public function updateCollectionFilters(&$searchCriteria)
{
	$searchCriteria['ParentID'] = $this->owner->ID;
}
```

### Manipulate results:

Further refine the resulting data:

```
public function updateCollectionItems(&$collection, &$searchCriteria)
{
    // exclude product docs that don't belong to this site segment
    $collection = $collection->filterByCallback(function ($item, $list) {
        return ($item->Type === $this->owner->Type);
    });
}
```

### Update CollectionSearchForm

Add or edit fields or actions of the form:

```
public function updateCollectionForm(&$form)
    {
        $action = $form->Actions()->first();
        $action->setTitle('Find Articles &rsaquo;');

    }
```

### Update PaginatedList

Refine the paginated results:

```
public function updatePaginatedList(&$records)
{
	$records = $records->filer(['Created:GreaterThan' => '2015/01/01']); 
}
```

### Update GroupedList

Refine the grouped results:

```
public function updateGroupedList(&$records)
{
	$records = $records->filer(['Created:GreaterThan' => '2015/01/01']); 
}
```
