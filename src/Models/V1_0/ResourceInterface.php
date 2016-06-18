<?php

namespace LaLu\JDR\Models\V1_0;

interface ResourceInterface
{
    /**
     * Get resource id
     *
     * @return string|int
     */
    public function getResourceId();

    /**
     * Get resource type
     *
     * @return string
     */
    public function getResourceType();

    /**
     * Get resource attributes
     *
     * @return array
     */
    public function getResourceAttributes();

    /**
     * Get resource links
     *
     * @return array
     */
    public function getResourceLinks();

    /**
     * Get searchable fields
     *
     * @return string[]
     */
    public function getSearchable();

    /**
     * Get relationship models
     *
     * @return ResourceInterface[]
     */
    public function getRelationships();
}
