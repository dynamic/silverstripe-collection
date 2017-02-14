<?php

class CollectionExtension extends Extension
{
    /**
     * @var array
     */
    private static $allowed_actions = array(
        'CollectionSearchForm',
    );

    /**
     * @var DataList|ArrayList
     */
    private $collection;

    /**
     * @return ArrayList|DataList
     */
    public function getCollection()
    {
        if (!$this->collection) {
            $this->setCollection($this->owner->request);
        }
        return $this->collection;
    }

    /**
     * @param SS_HTTPRequest|null $request
     * @return $this
     */
    public function setCollection(SS_HTTPRequest $request = null)
    {
        if ($request === null) {
            $request = $this->owner->request;
        }
        $searchCriteria = $request->requestVars();

        // allow $searchCriteria to be updated via extension
        $this->owner->extend('updateCollectionFilters', $searchCriteria);

        $object = $this->getCollectionObject();

        $context = (method_exists($object, 'getCustomSearchContext'))
            ? singleton($object)->getCustomSearchContext()
            : singleton($object)->getDefaultSearchContext();

        $sort = ($request->getVar('Sort'))
            ? (string) $request->getVar('Sort')
            : singleton($object)->stat('default_sort');

        $collection = $context->getResults($searchCriteria)->sort($sort);

        // allow $collection to be updated via extension
        $this->owner->extend('updateCollectionItems', $collection, $searchCriteria);

        $this->collection = $collection;
        return $this;
    }

    /**
     * @return string
     */
    public function getCollectionObject()
    {
        if ($object = $this->owner->config()->managed_object) {
            $object = (string) $object;
        } else {
            $object = 'Page';
        }

        $this->owner->extend('updateCollectionObject', $object);

        return $object;
    }

    /**
     * @return int
     */
    public function getCollectionSize()
    {
        if ($object = $this->owner->config()->page_size) {
            return (int) $object;
        }

        return 10;
    }

    /**
     * @param SS_HTTPRequest|null $request
     * @return PaginatedList
     */
    public function PaginatedList(SS_HTTPRequest $request = null)
    {
        if ($request === null) {
            $request = $this->owner->request;
        }
        $start = ($request->getVar('start')) ? (int) $request->getVar('start') : 0;

        $records = PaginatedList::create($this->getCollection(), $this->owner->request);
        $records->setPageStart($start);
        $records->setPageLength($this->getCollectionSize());

        // allow $records to be updated via extension
        $this->owner->extend('updatePaginatedList', $records);

        return $records;
    }

    /**
     * @return GroupedList
     */
    public function GroupedList()
    {
        $records = GroupedList::create($this->getCollection());

        // allow $records to be updated via extension
        $this->owner->extend('updateGroupedList', $records);

        return $records;
    }

    /**
     * @return Form
     */
    public function CollectionSearchForm()
    {
        $object = $this->getCollectionObject();
        $request = ($this->owner->request) ? $this->owner->request : $this->owner->parentController->getRequest();
        $sort = ($request->getVar('Sort')) ? (string) $request->getVar('Sort') : singleton($object)->stat('default_sort');

        $context = (method_exists($object, 'getCustomSearchContext')) ? singleton($object)->getCustomSearchContext() : singleton($object)->getDefaultSearchContext();
        $fields = $context->getSearchFields();

        // add sort field if managed object specs getSortOptions()
        if (method_exists($object, 'getSortOptions')) {
            $sortOptions = singleton($object)->getSortOptions();
            if (singleton($object)->stat('default_sort')) {
                $defaultSort = array(str_replace('"', '', singleton($object)->stat('default_sort')) => 'Default');
                $sortOptions = array_merge($defaultSort, $sortOptions);
            }
            $fields->add(
                DropdownField::create('Sort', 'Sort by:', $sortOptions, $sort)
            );
        }

        // allow $fields to be updated via extension
        $this->owner->extend('updateCollectionFields', $fields);

        $actions = new FieldList(
            new FormAction($this->owner->Link(), 'Search')
        );

        if (class_exists('BootstrapForm')) {
            $form = BootstrapForm::create(
                $this->owner,
                'CollectionSearchForm',
                $fields,
                $actions
            );
        } else {
            $form = Form::create(
                $this->owner,
                'CollectionSearchForm',
                $fields,
                $actions
            );
        }
        $form
            ->setFormMethod('get')
            ->disableSecurityToken()
            ->loadDataFrom($request->getVars())
            ->setFormAction($this->owner->Link())
        ;

        // allow $form to be extended via extension
        $this->owner->extend('updateCollectionForm', $form);

        return $form;
    }
}
