<?php
/*
 * Builder to build Acl from an XML file
 *
 * @copyright Ashley Kitson, UK, 2014
 * @license GPL3.0+
 */
namespace Chippyash\Zend\Acl\Xml;

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
        $this->addResources($this->dom->getElementsByTagName('resource'));

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
            $resourceType = 'GenericResource';
            if ($resource->hasAttribute('type')) {
                $resourceType = $resource->getAttribute('type');
            }

            $resourceName = $resource->nodeValue;

            if ($resourceType !== 'GenericResource') {
                $this->acl->addResource(new $resourceType($resourceName), $parent);
                continue;
            }

            $this->acl->addResource($resourceName, $parent);
        }
    }
}
