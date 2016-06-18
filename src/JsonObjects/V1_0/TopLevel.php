<?php

namespace LaLu\JDR\JsonObjects\V1_0;

use LaLu\JDR\JsonObjects\Object;
use Illuminate\Pagination\AbstractPaginator;

class TopLevel extends Object
{
    /**
     * Constructor.
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);
        $this->set('jsonapi', (new Jsonapi(['version' => '1.0'])));
    }

    /**
     * Get jsonapi struct.
     *
     * @return string[]|false
     */
    public function getJsonStruct()
    {
        return ['data', 'errors', 'included', 'links', 'meta', 'jsonapi'];
    }

    /**
     * Set model as data.
     *
     * @param \LaLu\JDR\Models\V1_0\ResourceInterface|\LaLu\JDR\Models\V1_0\ResourceInterface[] $model
     *
     * @return $this
     */
    public function setModel($model)
    {
        $this->set('data', null);
        if (is_array($model)) {
            foreach ($model as $m) {
                $this->addModel($m);
            }
        } else {
            list($resource, $includes) = $this->parseModel($model);
            $this->set('data', $resource);
            if (!empty($includes)) {
                $this->set('includes', $includes);
            }
        }

        return $this;
    }

    /**
     * Add model as data.
     *
     * @param mixed $model
     *
     * @return $this
     */
    public function addModel($model)
    {
        list($resource, $includes) = $this->parseModel($model);
        $this->add('data', $resource);
        if (!empty($includes)) {
            $this->set('includes', $includes);
        }

        return $this;
    }

    public function setPagination($collections)
    {
        list($resources, $links, $meta) = $this->parsePagination($collections);
        $this->set('data', $resources);
        if (!empty($links)) {
            $this->set('links', $links);
        }
        if (!empty($meta)) {
            $this->set('meta', $meta);
        }

        return $this;
    }

    /**
     * Parse a model.
     *
     * @param \LaLu\JDR\Models\V1_0\ResourceInterface $model
     *
     * @return array
     */
    public function parseModel($model)
    {
        $includes = [];
        $resource = new Resource([
            'id' => $model->getResourceId(),
            'type' => $model->getResourceType(),
        ]);
        $attributes = $model->getResourceAttributes();
        if (!empty($attributes)) {
            $resource->set('attributes', $attributes);
        }
        $links = $model->getResourceLinks();
        if (!empty($links)) {
            $resource->set('links', $links);
        }
        $relationships = $model->getRelationships();
        if (!empty($relationships)) {
            foreach ($relationships as $key => $relationshipModel) {
                $relationshipResource = new Resource([
                    'id' => $relationshipModel->getResourceId(),
                    'type' => $relationshipModel->getResourceType(),
                ]);
                $relationshipAttributes = $relationshipModel->getResourceAttributes();
                if (!empty($relationshipAttributes)) {
                    $relationshipResource->set('attributes', $relationshipAttributes);
                }
                $relationshipLinks = $relationshipModel->getResourceLinks();
                if (!empty($relationshipLinks)) {
                    $relationshipResource->set('links', $relationshipLinks);
                }
                $resource->add('relationships', $relationshipResource->getParams(['id', 'type']), $key);
                $includes[] = $relationshipResource;
            }
        }

        return [$resource, $includes];
    }

    public function parsePagination(AbstractPaginator $collections)
    {
        $resources = [];
        foreach ($collections->items() as $item) {
            $resource = new Resource([
                'id' => $item->getResourceId(),
                'type' => $item->getResourceType(),
            ]);
            $itemAttributes = $item->getResourceAttributes();
            if (!empty($itemAttributes)) {
                $resource->set('attributes', $itemAttributes);
            }
            $itemLinks = $item->getResourceLinks();
            if (!empty($itemLinks)) {
                $resource->set('links', $itemLinks);
            }
            $resources[] = $resource;
        }

        $linksArr = [
            'self' => $collections->url($collections->currentPage()),
            'first' => $collections->url(1),
            'prev' => $collections->previousPageUrl(),
            'next' => $collections->nextPageUrl(),
            'last' => method_exists($collections, 'lastPage') ? $collections->url($collections->lastPage()) : null,
        ];
        $currentQuery = parse_url(url()->full(), PHP_URL_QUERY);
        if (!empty($currentQuery)) {
            parse_str($currentQuery, $queryArr);
            foreach ($linksArr as $key => $value) {
                if (!empty($value)) {
                    $urlQuery = parse_url($value, PHP_URL_QUERY);
                    if (!empty($urlQuery)) {
                        parse_str($urlQuery, $urlArr);
                        foreach ($urlArr as $name => $val) {
                            if (isset($queryArr[$name]) && is_array($queryArr[$name]) && is_array($val)) {
                                foreach ($val as $k => $v) {
                                    $queryArr[$name][$k] = $v;
                                }
                            } else {
                                $queryArr[$name] = $val;
                            }
                        }
                        $linksArr[$key] = url()->current().'?'.http_build_query($queryArr);
                    }
                }
            }
        }
        $links = new Links($linksArr);

        $metaArr = [
            'count' => $collections->count(),
            'perPage' => $collections->perPage(),
            'currentPage' => $collections->currentPage(),
        ];
        if (method_exists($collections, 'total')) {
            $metaArr['totalObjects'] = $collections->total();
        }
        if (method_exists($collections, 'lastPage')) {
            $metaArr['totalPages'] = $collections->lastPage();
        }
        $meta = new Meta($metaArr);

        return [$resources, $links, $meta];
    }
}
