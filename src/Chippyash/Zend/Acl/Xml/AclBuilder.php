<?php

/*
 * Builder to build Acl from an XML file
 *
 * @copyright Ashley Kitson, UK, 2014
 * @license GPL3.0+
 */

namespace Chippyash\Zend\Acl\Xml;

use Zend\Permissions\Acl\Acl;
use Chippyash\Type\String\StringType;
use Chippyash\BuilderPattern\AbstractBuilder;
use Chippyash\Zend\Acl\Xml\Exceptions\AclBuilderException;

/**
 * Build a zend-permissions-acl from an XML definition
 */
class AclBuilder extends AbstractBuilder
{
    const ERR_NO_XML = 'ACL definition XML does not exist';
    const ERR_INVALID_ACL = 'ACL definition is invalid: %s';
    const NS = 'http://schema.zf4.biz/schema/zendacl';
    
    /**
     * Constructor
     *
     * @param StringType $xmlFile
     * @param Acl $acl
     */
    public function __construct(StringType $xmlFile, Acl $acl)
    {
        parent::__construct();

        $xsdFile = __DIR__ . '/xsd/zendacl.xsd';
        $this->xml = $this->loadDefinition($xmlFile(), $xsdFile);
        $this->acl = $acl;
        $this->processImports(dirname($xmlFile()));
        $this->roles = new RoleBuilder($this->xml, $this->acl);
        $this->resources = new ResourceBuilder($this->xml, $this->acl);
        $this->rules = new RuleBuilder($this->xml, $this->acl);
    }

    /**
     * Check for imports in the document and process them.
     * Converts imported xml into ACL and passes back to this builder.
     * 
     * This is recursive so the end result is that we do a depth first leaf node
     * construction of the ACL
     * 
     * @param string $basePath Path to directory of parent file
     */
    protected function processImports($basePath)
    {
        $xpath = new \DOMXPath($this->xml);
        $xpath->registerNamespace('acl', self::NS);
        $importNodes = $xpath->query('//acl:acl/acl:imports/acl:import');
        if ($importNodes->length == 0) {
            return;
        }
        
        foreach ($importNodes as $iNode) {
            $importName = $iNode->nodeValue;
            if (dirname($importName) == '.') {
                //assume it is in same directory as the parent file
                $importName = "{$basePath}/{$importName}";
            } elseif (strstr($importName, '..') == 0) {
                //it is relative to parent file directory
                $file = basename($importName);
                $dir = realpath($basePath . '/' . dirname($importName));
                $importName = "{$dir}/{$file}";
            }
            $builder = new self(new StringType($importName), $this->acl);
            if ($builder->build()) {
                $result = $builder->getResult();
                $this->acl = $result['acl'];
            }
        }
    }
    
    /**
     * Load XML definition from file
     *
     * @param string $xmlFile
     * @param string $xsdFile
     *
     * @return \DOMDocument
     *
     * @throws AclBuilderBuilderException
     */
    protected function loadDefinition($xmlFile, $xsdFile)
    {
        $isString = false;
        if (strstr($xmlFile, '<?xml') !== false) {
            $isString = true;
        } elseif (!file_exists($xmlFile)) {
            throw new AclBuilderException(self::ERR_NO_XML);
        }

        $defDom = new \DOMDocument();
        $defDom->validateOnParse = true;
        $options = LIBXML_NONET | (defined('LIBXML_COMPACT') ? LIBXML_COMPACT : 0);
        if ($isString) {
            $defDom->loadXML($xmlFile, $options);
        } else {
            $defDom->load($xmlFile, $options);
        }
        $prevSetting = \libxml_use_internal_errors(true);
        if (!$defDom->schemaValidate($xsdFile)) {
            $errors = \libxml_get_errors();
            $errMsg = '';
            foreach ($errors as $error) {
                $errMsg .= $this->libxml_display_error($error);
            }
            throw new AclBuilderException(sprintf(self::ERR_INVALID_ACL, $errMsg));
        }
        \libxml_use_internal_errors($prevSetting);

        return $defDom;
    }

    /**
     * @override
     */
    protected function setBuildItems()
    {
        $this->buildItems = [
            'xml' => null,
            'acl' => null,
            'roles' => null,
            'resources' => null,
            'rules' => null,
        ];
    }

    /**
     * @link http://php.net/manual/en/domdocument.schemavalidate.php
     * @param type $error
     * @return type
     */
    protected function libxml_display_error(\LibXMLError $error)
    {
        $return = "";
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "Warning $error->code: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "Error $error->code: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "Fatal Error $error->code: ";
                break;
        }
        $return .= trim($error->message);
        if ($error->file) {
            $return .= " in $error->file";
        }
        $return .= " on line $error->line ";

        return $return;
    }
}
