<?php

namespace CMIS\Tests\Utils;

use CMIS\Entities\Folder;
use CMIS\Session\Session;
use CMIS\Session\SessionFactory;
use CMIS\Utils\Arr;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{

    /**
     * Tests ::get()
     */
    public function testGet()
    {
        $data = [
            "properties" => [
                "cmis:objectId" => [
                    "value" => "123"
                ],
                "cmis:name" => [
                    "value" => "My Folder"
                ],
                "cmis:creationDate" => [
                    "value" => 123456789
                ],
                "cmis:createdBy" => [
                    "value" => "John Doe"
                ],
                "cmis:parentId" => [
                    "value" => "456"
                ],
                "cmis:path" => [
                    "value" => "/path/to/folder"
                ]
            ]
        ];

        $this->assertEquals("123", Arr::get($data, "properties.cmis:objectId.value"));
        $this->assertEquals("My Folder", Arr::get($data, "properties.cmis:name.value"));
        $this->assertEquals(123456789, Arr::get($data, "properties.cmis:creationDate.value"));
        $this->assertEquals("John Doe", Arr::get($data, "properties.cmis:createdBy.value"));
        $this->assertEquals("456", Arr::get($data, "properties.cmis:parentId.value"));
        $this->assertEquals("/path/to/folder", Arr::get($data, "properties.cmis:path.value"));
        $this->assertNull(Arr::get($data, "properties.cmis:non-existent.value", null));
    }


}