<?php

namespace LaLu\JDR\Models\V1_0;

interface ResourceInterface
{
    public function getResourceId();

    public function getResourceType();

    public function getResourceAttributes();

    public function getResourceLinks();

    public function getSearchable();

    /**
     * Get relationship models
     *
     * @return ResourceInterface[]
     */
    public function getRelationships();
}
