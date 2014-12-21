<?php

/*
 * Builder to build Acl from an XML file
 *
 * @copyright Ashley Kitson, UK, 2014
 * @license GPL3.0+
 */

namespace chippyash\Zend\Acl\Xml;

use chippyash\Zend\Acl\Xml\AbstractAclItemBuilder;

/**
 * Build zend-permissions-acl resources from an XML definition
 */
class ResourceBuilder extends AbstractAclItemBuilder
{

    /**
     * @override
     * @return boolean
     */
    public function buildItem()
    {
        $this->addResources($this->xquery->query('/acl/resources/resource'));

        return true;
    }

    /**
     * Add resources to ACL
     *
     * @param \DOMNodeList $resources
     */
    protected function addResources(\DOMNodeList $resources)
    {
        foreach ($resources as $resource) {
            $parent = null;
            if ($resource->hasAttribute('parent')) {
                $parents = explode(',', $resource->getAttribute('parent'));
            }

            $resourceType = 'GenericResource';
            if ($resource->hasAttribute('type')) {
                $resourceType = $resource->getAttribute('type');
            }

            $resourceName = $resource->nodeValue;

            if ($resourceType !== 'GenericResource') {
                $this->acl->addResource(new $resourceType($resourceName), $parent);
            } else {
                $this->acl->addResource($resourceName, $parent);
            }
        }
    }
}
