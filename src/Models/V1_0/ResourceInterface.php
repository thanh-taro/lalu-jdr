<?php

namespace LaLu\JDR\Models\V1_0;

interface ResourceInterface
{
    public function getJsonapiId();

    public function getJsonapiType();

    public function getJsonapiAttributes();

    public function getJsonapiLinks();
}
