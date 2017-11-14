<?php

namespace Dynamic\Collection;

use League\Flysystem\Exception;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\GroupedList;
use SilverStripe\ORM\PaginatedList;

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
     * @var
     */
    private $collection_object;

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
     * @param HTTPRequest|null $request
     * @return $this
     */
    public function setCollection(HTTPRequest $request = null)
    {
        if ($request === null) {
            $request = $this->owner->request;
        }
        $searchCriteria = $request->requestVars();

        // allow $searchCriteria to be updated via extension
        $this->owner->extend('updateCollectionFilters', $searchCriteria);

        $object = $this->getCollectionObject();

        $context = (method_exists($object, 'getCustomSearchContext'))
            ? $object->getCustomSearchContext()
            : $object->getDefaultSearchContext();

        $sort = ($request->getVar('Sort'))
            ? (string) $request->getVar('Sort')
            : $object->stat('default_sort');

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
        if (!$this->collection_object) {
            $this->setCollectionObject();
        }
        return $this->collection_object;
    }

    /**
     * @return $this
     */
    public function setCollectionObject()
    {
        try {
            $collection_object = $this->owner->config()->get('managed_object');
            $this->collection_object = $collection_object::create();
        } catch (Exception $e) {
            trigger_error($e, E_USER_NOTICE);
        }

        return $this;
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
     * @param HTTPRequest|null $request
     * @return mixed
     */
    public function PaginatedList(HTTPRequest $request = null)
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
        $sort = ($request->getVar('Sort')) ? (string) $request->getVar('Sort') : $object->stat('default_sort');

        $context = ($object->hasMethod('getCustomSearchContext'))
            ? $object->getCustomSearchContext()
            : $object->getDefaultSearchContext();
        $fields = $context->getSearchFields();

        // add sort field if managed object specs getSortOptions()
        if ($object->hasMethod('getSortOptions')) {
            $sortOptions = $object->getSortOptions();
            if ($object->stat('default_sort')) {
                $defaultSort = array(str_replace('"', '', $object->stat('default_sort')) => 'Default');
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

        if (class_exists(BootstrapForm::class)) {
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

        // allow $form to be extended via extension
        $this->owner->extend('updateCollectionForm', $form);

        $form
            ->setFormMethod('get')
            ->disableSecurityToken()
            ->loadDataFrom($request->getVars())
            ->setFormAction($this->owner->Link())
        ;

        return $form;
    }
}
