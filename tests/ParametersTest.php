<?php

/*
 * This file is part of JSON-API.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tobscure\Tests\JsonApi;

use Tobscure\JsonApi\Parameters;
use Yoast\PHPUnitPolyfills\Polyfills\ExpectException;

/**
 * This is the parameters test class.
 *
 * @author Toby Zerner <toby.zerner@gmail.com>
 */
class ParametersTest extends AbstractTestCase
{
    use ExpectException;

    public function testGetIncludeReturnsArrayOfIncludes()
    {
        $parameters = new Parameters(['include' => 'posts,images']);

        $this->assertEquals(['posts', 'images'], $parameters->getInclude(['posts', 'images']));
    }

    public function testGetIncludeReturnsEmptyArray()
    {
        $parameters = new Parameters(['include' => '']);

        $this->assertEquals([], $parameters->getInclude(['posts', 'images']));
    }

    public function testGetIncludeWithUnallowedField()
    {
        $this->expectException(\Tobscure\JsonApi\Exception\InvalidParameterException::class);
        $this->expectExceptionCode(1);
        $parameters = new Parameters(['include' => 'posts,images']);

        $parameters->getInclude(['posts']);
    }

    public function testGetSortReturnsArrayOfFieldToSortDirection()
    {
        $parameters = new Parameters(['sort' => 'firstname']);

        $this->assertEquals(['firstname' => 'asc'], $parameters->getSort(['firstname']));
    }

    public function testGetSortSupportsMultipleSortedFieldsSeparatedByComma()
    {
        $parameters = new Parameters(['sort' => 'firstname,-lastname']);

        $this->assertEquals(['firstname' => 'asc', 'lastname' => 'desc'], $parameters->getSort(['firstname', 'lastname']));
    }

    public function testGetSortDefaultsToEmptyArray()
    {
        $parameters = new Parameters([]);

        $this->assertEmpty($parameters->getSort());
    }

    public function testGetSortWithUnallowedField()
    {
        $this->expectException(\Tobscure\JsonApi\Exception\InvalidParameterException::class);
        $this->expectExceptionCode(3);
        $parameters = new Parameters(['sort' => 'firstname,lastname']);

        $parameters->getSort(['firstname']);
    }

    public function testGetOffsetParsesThePageOffset()
    {
        $parameters = new Parameters(['page' => ['offset' => 10]]);

        $this->assertEquals(10, $parameters->getOffset());
    }

    public function testGetOffsetIsAtLeastZero()
    {
        $this->expectException(\Tobscure\JsonApi\Exception\InvalidParameterException::class);
        $this->expectExceptionCode(2);
        $parameters = new Parameters(['page' => ['offset' => -5]]);

        $parameters->getOffset();
    }

    public function testGetOffsetParsesThePageNumber()
    {
        $parameters = new Parameters(['page' => ['number' => 2]]);

        $this->assertEquals(20, $parameters->getOffset(20));
    }

    public function testGetLimitParsesThePageLimit()
    {
        $parameters = new Parameters(['page' => ['limit' => 100]]);

        $this->assertEquals(100, $parameters->getLimit());
    }

    public function testGetLimitReturnsNullWhenNotSet()
    {
        $parameters = new Parameters(['page' => ['offset' => 50]]);

        $this->assertNull($parameters->getLimit());
    }

    public function testGetFieldsReturnsAllFields()
    {
        $parameters = new Parameters(['fields' => ['posts' => 'title,content', 'users' => 'name']]);

        $this->assertEquals(['posts' => ['title', 'content'], 'users' => ['name']], $parameters->getFields());
    }

    public function testGetFieldsReturnsEmptyArray()
    {
        $parameters = new Parameters([]);

        $this->assertEquals([], $parameters->getFields());

        $parameters = new Parameters(['fields' => 'string']);

        $this->assertEquals([], $parameters->getFields());
    }
}
