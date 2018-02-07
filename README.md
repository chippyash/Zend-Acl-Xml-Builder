# chippyash/zend-acl-xml-builder

## Quality Assurance

![PHP 5.5](https://img.shields.io/badge/PHP-5.5-blue.svg)
![PHP 5.6](https://img.shields.io/badge/PHP-5.6-blue.svg)
![PHP 7](https://img.shields.io/badge/PHP-7-blue.svg)
[![Build Status](https://travis-ci.org/chippyash/Zend-Acl-Xml-Builder.svg?branch=master)](https://travis-ci.org/chippyash/Zend-Acl-Xml-Builder)
[![Test Coverage](https://codeclimate.com/github/chippyash/Zend-Acl-Xml-Builder/badges/coverage.svg)](https://codeclimate.com/github/chippyash/Zend-Acl-Xml-Builder/coverage)
[![Code Climate](https://codeclimate.com/github/chippyash/Zend-Acl-Xml-Builder/badges/gpa.svg)](https://codeclimate.com/github/chippyash/Zend-Acl-Xml-Builder)

The above badges represent the current development branch.  As a rule, I don't push
 to GitHub unless tests, coverage and usability are acceptable.  This may not be
 true for short periods of time; on holiday, need code for some other downstream
 project etc.  If you need stable code, use a tagged version. Read 'Further Documentation'
 and 'Installation'.
 
[Test Contract](https://github.com/chippyash/Zend-Acl-Xml-Builder/blob/master/docs/Test-Contract.md) in the docs directory.

### End of life notice

In March 2018, developer support will be withdrawn from this library for PHP <5.6. Older
versions of PHP are now in such little use that the added effort of maintaining 
compatibility is not effort effective.  See [PHP Version Stats](https://seld.be/notes/php-versions-stats-2017-1-edition)
 for the numbers.
 
## What?

Provides the ability to specify a [Zend ACL](http://framework.zend.com/manual/current/en/modules/zend.permissions.acl.intro.html) 
using XML. The XML is validated by an XSD file which you can also use in your XML 
editor

The library is released under the [GNU GPL V3 or later license](http://www.gnu.org/copyleft/gpl.html)

## Why?

Zend/Permissions/Acl is a great and lightweight way of providing access control to your
applications, but it can be a [PITA](http://www.urbandictionary.com/define.php?term=pita) 
to configure using the native command set. As it happens, it is ideally placed, 
because of its structure, to be driven by an XML configuration.  

This also means that the ACL can be managed by some third party application or service.  
It is not beyond the wit of most to be able to write an XSL translation for 
instance, that takes a definition from LDAP and converts to this library format 
to be able then to control the ACL from your organisation's LDAP servers.

## When

The current library handles reading nested XML files (or content) and returning an ACL.
If you'd like new features, please suggest them in the issues tracker, or better
still, fork the lib and issue a pull request (but please don't forget the unit tests!)

## How

To understand how to use it, see the test files, in particular, take a look at
AclDirectorTest as a starting point and work down from there.

In essence you need to do two things

1. Provide an XML definition of the ACL
2. Tell the Director where the XML is

This library depends on the [Builder Pattern](https://github.com/chippyash/Builder-Pattern)
and the [Strong Type](https://github.com/chippyash/Strong-Type) libraries.

### Defining the XML

For the canonical truth, study the XSD file located in [src/chippyash/Zend/Acl/Xml/xsd](https://github.com/chippyash/Zend-Acl-Xml-Builder/blob/master/src/chippyash/Zend/Acl/Xml/xsd/zendacl.xsd)

There is also an example XML file used for testing located in [test/src/chippyash/Zend/Acl/Xml/fixtures](https://github.com/chippyash/Zend-Acl-Xml-Builder/blob/master/test/src/chippyash/Zend/Acl/Xml/fixtures/test.xml)

The XSD namespace is http://schema.zf4.biz/schema/zendacl. It is publicly available at the same url.

Your XML file should be defined as 

<pre>
&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;acl xmlns="http://schema.zf4.biz/schema/zendacl"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:schemaLocation="http://schema.zf4.biz/schema/zendacl http://schema.zf4.biz/schema/zendacl"
     &gt;
&lt;/acl&gt;
</pre>

NB. you can replace the second part of the xsi:schemaLocation attribute to point at
a local disk version of the XSD if you want to tinker with the XSD. e.g.

<code>xsi:schemaLocation="http://schema.zf4.biz/schema/zendacl ../../zendacl.xsd"</code>

Essentially, Zend-ACL defines the ACL in three parts:

* Roles
* Resources
* Rules

Whilst you can define Roles and Resources independently, Rules require that you
have already defined Roles and Resources to act on.  Rules also allow you to set
additional privileges and assertions.  

To provide an ACL you must specify all three parts.

#### Roles

- A role can have the following optional attributes:
    - type: string: default = "GenericRole". Name of your specialized role class. The default
uses the Zend GenericRole
    - parents: string: default = none. Comma separated list of names of parents for this role
- Content for the role element is the role name

#### Resources

- A resource can have the following optional attributes:
    - type: string: default = "GenericResource". Name of your specialized resource class. The default
uses the Zend GenericResource
    - parent: string: default = none. Name of parent for this resource. NB. unlike roles,
resources may only have a single parent.
- Content for the resource element is the resource name

#### Rules

- A rule has an obligatory attribute:
    - type: string: one of 'ALLOW' or 'DENY'
- A rule has the following optional arguments:
    - roles: string: default = "*". comma separated list of role names that the rule applies to
    - resources: string: default = "*". comma separated list of resource names that the rule applies to
    - assertion: string: default = none. Fully namespaced class providing the assertion.  
The class must exist and implement the Zend\Permissions\Acl\Assertion\AssertionInterface.
You can find an example in [test/src/chippyash/Zend/Acl/Xml/Stubs](https://github.com/chippyash/Zend-Acl-Xml-Builder/blob/master/test/src/chippyash/Zend/Acl/Xml/Stubs/TestAssertionStub.php)
- a rule can contain optional \<privilege\> elements. Each \<privilege\> element contains
the name of an arbitrary privilege.

#### Importing definitions

You can import other ACL definitions into your definition by using the 

<pre>
    &lt;imports&gt;
        &lt;import&gt;[path_to_file/|../*path_to_file/]file.xml&lt;import&gt;
    &lt;/imports&gt;
</pre>

If no path given, then expect file to be in same directory as parent file. If 
path begins .., then expect file to be in directory relative to parent file. If 
path supplied, then expect file to be in that directory.  Thus the following are
all valid:

<pre>
    myfile.xml
    ../../path/to/file.xml
    /path/to/file.xml
</pre>

#### NB

All definition items are processed in the order that they appear in the XML file.
Imports are processed first, by a L2R, depth first strategy.

### Build the ACL

<pre>
    use Chippyash\Zend\Acl\Xml\AclDirector;
    use Chippyash\Type\String\StringType;

    $location = new StringType('/location/of/my/acl.xml');
    $director = new AclDirector($location);
    $acl = $director->build();
</pre>

Alternatively, you can pass in the XML to act on as a string, rather than a file.
The string must of course conform to the zendacl.xsd (http://schema.zf4.biz/schema/zendacl)
schema and be valid XML.

<pre>
    use Chippyash\Zend\Acl\Xml\AclDirector;
    use Chippyash\Type\String\StringType;

    $content = new StringType($myAclXml);
    $director = new AclDirector($content);
    $acl = $director->build();
</pre>

### Changing the library

1.  fork it
2.  write the test
3.  amend it
4.  do a pull request

Found a bug you can't figure out?

1.  fork it
2.  write the test
3.  do a pull request

NB. Make sure you rebase to HEAD before your pull request

## Where?

The library is hosted at [Github](https://github.com/chippyash/Zend-Acl-Xml-Builder). It is
available at [Packagist.org](https://packagist.org/packages/chippyash/zend-acl-xml-builder)

### Installation

Install [Composer](https://getcomposer.org/)

#### For production

add

<pre>
    "chippyash/zend-acl-xml-builder": "~2"
</pre>

to your composer.json "requires" section

#### For development

Clone this repo, and then run Composer in local repo root to pull in dependencies

<pre>
    git clone git@github.com:chippyash/Zend-Acl-Xml-Builder.git ZendAclBuilder
    cd ZendAclBuilder
    composer install --dev
</pre>

To run the tests:

<pre>
    cd ZendAclBuilder
    vendor/bin/phpunit -c test/phpunit.xml test/
</pre>

## Other stuff

Check out [ZF4 Packages](http://zf4.biz/packages?utm_source=github&utm_medium=web&utm_campaign=blinks&utm_content=zendaclxmlbuilder) for more packages

## License

This software library is released under the [GNU GPL V3 or later license](http://www.gnu.org/copyleft/gpl.html)

This software library is Copyright (c) 2015-2016, Ashley Kitson, UK

A commercial license is available for this software library, please contact the author. 
It is normally free to deserving causes, but gets you around the limitation of the GPL
license, which does not allow unrestricted inclusion of this code in commercial works.

## History

V0...  pre releases

V1.0.0 First version

V1.1.0 New feature: Namespaced the XSD and placed on public server

V1.2.0 New features: 

- ACL definitions can import other definitions
- XML can be passed in as string as well as file

V1.2.1 Remove hard dependency on Zend-ACL version

V2.0.0 BC Break: change chippyash\Zend\Acl namespace to Chippyash\Zend\Acl

V2.0.1 moved from coveralls to codeclimate

V2.0.2 Add link to packages

V2.0.3 Verify PHP 7 compatibility

V2.0.4 Update dependencies

V2.0.5 update composer - forced by packagist composer.json format change




