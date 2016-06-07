<?php

namespace LaLu\JDR\JsonObjects\V1_0;

use LaLu\JDR\JsonObjects\Object;
use LaLu\JDR\Models\V1_0\ResourceInterface;

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
            list($resource) = $this->parseModel($model);
            $this->set('data', $resource);
        }

        return $this;
    }

    /**
     * Add model as data.
     *
     * @param \LaLu\JDR\Models\V1_0\ResourceInterface $model
     *
     * @return $this
     */
    public function addModel(ResourceInterface $model)
    {
        list($resource) = $this->parseModel($model);
        $this->add('data', $resource);

        return $this;
    }

    /**
     * Parse a model.
     *
     * @param \LaLu\JDR\Models\V1_0\ResourceInterface $model
     *
     * @return array
     */
    public function parseModel(ResourceInterface $model)
    {
        $resource = new Resource([
            'id' => $model->getResourceId(),
            'type' => $model->getResourceType(),
            'attributes' => $model->getResourceAttributes(),
            'links' => $model->getResourceLinks(),
        ]);

        return [$resource];
    }
}
