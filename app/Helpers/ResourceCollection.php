<?php

namespace App\Helpers;

/**
 * Class ResourceCollection
 * @package App\Helpers
 */
class ResourceCollection extends \Illuminate\Http\Resources\Json\ResourceCollection
{
    public function __construct($resource)
    {
        $this->pagination = [
            'totalItems' => (int) $resource->total(),
            'returnedItems' => $resource->count(),
            'perPage' => (int) $resource->perPage(),
            'currentPage' => (int) $resource->currentPage(),
            'totalPages' => $resource->lastPage()
        ];

        $resource = $resource->getCollection();

        parent::__construct($resource);
    }

    /**
     * @param $collection
     * @param $column
     * @param int $desc
     * @return mixed
     */
    public function sortedCollection($collection, $column, $desc = 0) {
        return $collection->sortByDesc($column, $desc)->values();
    }
}
