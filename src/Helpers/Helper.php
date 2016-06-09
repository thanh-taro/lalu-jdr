<?php

namespace LaLu\JDR\Helpers;

use LaLu\JDR\JsonObjects\V1_0\Error;
use LaLu\JDR\JsonObjects\V1_0\Jsonapi;
use LaLu\JDR\JsonObjects\V1_0\Link;
use LaLu\JDR\JsonObjects\V1_0\Links;
use LaLu\JDR\JsonObjects\V1_0\Meta;
use LaLu\JDR\JsonObjects\V1_0\Resource;
use LaLu\JDR\JsonObjects\V1_0\Source;
use LaLu\JDR\JsonObjects\V1_0\TopLevel;

class Helper
{
    /**
     * Make json object.
     *
     * @param string $name
     * @param array  $params
     *
     * @return null|\LaLu\JDR\JsonObjects\Object
     */
    public static function makeJsonapiObject($version, $name, $params = [])
    {
        if ($version === '1.0') {
            if ($name === 'error') {
                return new Error($params);
            }
            if ($name === 'toplevel') {
                return new TopLevel($params);
            }
            if ($name === 'meta') {
                return new Meta($params);
            }
            if ($name === 'jsonapi') {
                return new Jsonapi($params);
            }
            if ($name === 'link') {
                return new Link($params);
            }
            if ($name === 'links') {
                return new Links($params);
            }
            if ($name === 'resource') {
                return new Resource($params);
            }
            if ($name === 'source') {
                return new Source($params);
            }
        }

        return;
    }

    /**
     * Lumen compatibility for trans().
     *
     * @param string $id
     * @param array  $parameters
     * @param string $domain
     * @param string $locale
     *
     * @return \Symfony\Component\Translation\TranslatorInterface|string
     */
    public static function trans($id = null, $parameters = [], $domain = 'messages', $locale = null)
    {
        if (function_exists('trans')) {
            return trans($id, $parameters, $domain, $locale);
        }
        if (is_null($id)) {
            return app('translator');
        }

        return app('translator')->trans($id, $parameters, $domain, $locale);
    }

    /**
     * Lumen compatibility for resource_path().
     *
     * @param string $path
     *
     * @return string
     */
    public static function resourcePath($path)
    {
        if (function_exists('resource_path')) {
            return resource_path($path);
        }

        return app()->basePath().DIRECTORY_SEPARATOR.'resources'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}
