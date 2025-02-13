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

use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;
use Yoast\PHPUnitPolyfills\Polyfills\ExpectException;

class AbstractSerializerTest extends AbstractTestCase
{
    use ExpectException;

    public function testGetTypeReturnsTheType()
    {
        $serializer = new PostSerializer1;

        $this->assertEquals('posts', $serializer->getType(null));
    }

    public function testGetAttributesReturnsTheAttributes()
    {
        $serializer = new PostSerializer1;
        $post = (object) ['foo' => 'bar'];

        $this->assertEquals(['foo' => 'bar'], $serializer->getAttributes($post));
    }

    public function testGetRelationshipReturnsRelationshipFromMethod()
    {
        $serializer = new PostSerializer1;

        $relationship = $serializer->getRelationship(null, 'comments');

        $this->assertTrue($relationship instanceof Relationship);
    }

    public function testGetRelationshipReturnsRelationshipFromMethodUnderscored()
    {
        $serializer = new PostSerializer1;

        $relationship = $serializer->getRelationship(null, 'parent_post');

        $this->assertTrue($relationship instanceof Relationship);
    }

    public function testGetRelationshipReturnsRelationshipFromMethodKebabCase()
    {
        $serializer = new PostSerializer1;

        $relationship = $serializer->getRelationship(null, 'parent-post');

        $this->assertTrue($relationship instanceof Relationship);
    }

    public function testGetRelationshipValidatesRelationship()
    {
        $this->expectException(\LogicException::class);
        $serializer = new PostSerializer1;

        $serializer->getRelationship(null, 'invalid');
    }
}

class PostSerializer1 extends AbstractSerializer
{
    protected $type = 'posts';

    public function getAttributes($post, array $fields = null)
    {
        return ['foo' => $post->foo];
    }

    public function comments($post)
    {
        $element = new Collection([], new self);

        return new Relationship($element);
    }

    public function parentPost($post)
    {
        $element = new Resource([], new self);

        return new Relationship($element);
    }

    public function invalid($post)
    {
        return 'invalid';
    }
}
