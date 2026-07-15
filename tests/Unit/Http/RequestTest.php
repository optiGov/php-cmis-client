<?php

namespace CMIS\Tests\Unit\Http;

use CMIS\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    // -------------------------------------------------------------------------
    // URL helpers
    // -------------------------------------------------------------------------

    public function testGetUrlWithoutParametersReturnsBareUrl(): void
    {
        $request = new Request('http://example.com/cmis/browser');
        $this->assertSame('http://example.com/cmis/browser', $request->getUrl());
    }

    public function testGetUrlWithParametersAppendsQueryString(): void
    {
        $request = new Request('http://example.com/cmis/browser');
        $request->addUrlParameter('objectId', 'abc-123')
                ->addUrlParameter('cmisselector', 'properties');

        $this->assertStringContainsString('objectId=abc-123', $request->getUrl());
        $this->assertStringContainsString('cmisselector=properties', $request->getUrl());
        $this->assertStringStartsWith('http://example.com/cmis/browser?', $request->getUrl());
    }

    // -------------------------------------------------------------------------
    // Post fields
    // -------------------------------------------------------------------------

    public function testAddPostFieldAppearsInMergedFields(): void
    {
        $request = new Request('http://example.com');
        $request->addPostField('cmisAction', 'createDocument');

        $fields = $request->getMergedPostFields();

        $this->assertSame('createDocument', $fields['cmisAction']);
    }

    // -------------------------------------------------------------------------
    // _charset_ indicator
    // -------------------------------------------------------------------------

    public function testCharsetDefaultsToUtf8(): void
    {
        $request = new Request('http://example.com');

        $fields = $request->getMergedPostFields();

        $this->assertSame('UTF-8', $fields['_charset_']);
    }

    public function testCharsetIsNotOverriddenWhenCallerSetsIt(): void
    {
        $request = new Request('http://example.com');
        $request->addPostField('_charset_', 'ISO-8859-1');

        $fields = $request->getMergedPostFields();

        $this->assertSame('ISO-8859-1', $fields['_charset_']);
    }

    // -------------------------------------------------------------------------
    // Scalar properties
    // -------------------------------------------------------------------------

    public function testScalarPropertyIsIndexedCorrectly(): void
    {
        $request = new Request('http://example.com');
        $request->addProperty('cmis:name', 'My Document');

        $fields = $request->getMergedPostFields();

        $this->assertSame('cmis:name', $fields['propertyId[0]']);
        $this->assertSame('My Document', $fields['propertyValue[0]']);
    }

    public function testMultipleScalarPropertiesIncrementIndex(): void
    {
        $request = new Request('http://example.com');
        $request->addProperty('cmis:name', 'My Document')
                ->addProperty('cmis:objectTypeId', 'DONL');

        $fields = $request->getMergedPostFields();

        $this->assertSame('cmis:name', $fields['propertyId[0]']);
        $this->assertSame('My Document', $fields['propertyValue[0]']);
        $this->assertSame('cmis:objectTypeId', $fields['propertyId[1]']);
        $this->assertSame('DONL', $fields['propertyValue[1]']);
    }

    // -------------------------------------------------------------------------
    // Array (multi-value) properties
    // -------------------------------------------------------------------------

    public function testArrayPropertyIsIndexedWithSubIndices(): void
    {
        $request = new Request('http://example.com');
        $request->addProperty('repo:tags', ['urgent', 'public', 'reviewed']);

        $fields = $request->getMergedPostFields();

        $this->assertSame('repo:tags', $fields['propertyId[0]']);
        $this->assertSame('urgent', $fields['propertyValue[0][0]']);
        $this->assertSame('public', $fields['propertyValue[0][1]']);
        $this->assertSame('reviewed', $fields['propertyValue[0][2]']);
        // No flat propertyValue[0] key should be present
        $this->assertArrayNotHasKey('propertyValue[0]', $fields);
    }

    public function testArrayPropertyWithSingleValueUsesSubIndex(): void
    {
        $request = new Request('http://example.com');
        $request->addProperty('repo:tags', ['only-value']);

        $fields = $request->getMergedPostFields();

        $this->assertSame('repo:tags', $fields['propertyId[0]']);
        $this->assertSame('only-value', $fields['propertyValue[0][0]']);
        $this->assertArrayNotHasKey('propertyValue[0]', $fields);
    }

    public function testMixedScalarAndArrayPropertiesAreIndexedTogether(): void
    {
        $request = new Request('http://example.com');
        $request->addProperty('cmis:name', 'Test')          // scalar at index 0
                ->addProperty('repo:tags', ['a', 'b'])       // array  at index 1
                ->addProperty('cmis:objectTypeId', 'DONL');  // scalar at index 2

        $fields = $request->getMergedPostFields();

        // scalar at 0
        $this->assertSame('cmis:name', $fields['propertyId[0]']);
        $this->assertSame('Test', $fields['propertyValue[0]']);

        // array at 1
        $this->assertSame('repo:tags', $fields['propertyId[1]']);
        $this->assertSame('a', $fields['propertyValue[1][0]']);
        $this->assertSame('b', $fields['propertyValue[1][1]']);

        // scalar at 2
        $this->assertSame('cmis:objectTypeId', $fields['propertyId[2]']);
        $this->assertSame('DONL', $fields['propertyValue[2]']);
    }

    public function testPostFieldsAndPropertiesAreMergedTogether(): void
    {
        $request = new Request('http://example.com');
        $request->addPostField('cmisAction', 'createDocument')
                ->addPostField('objectId', 'parent-folder-id')
                ->addProperty('cmis:name', 'doc.txt');

        $fields = $request->getMergedPostFields();

        $this->assertSame('createDocument', $fields['cmisAction']);
        $this->assertSame('parent-folder-id', $fields['objectId']);
        $this->assertSame('cmis:name', $fields['propertyId[0]']);
        $this->assertSame('doc.txt', $fields['propertyValue[0]']);
    }

    // -------------------------------------------------------------------------
    // Files
    // -------------------------------------------------------------------------

    public function testHasFilesReturnsFalseByDefault(): void
    {
        $request = new Request('http://example.com');
        $this->assertFalse($request->hasFiles());
    }

    public function testHasFilesReturnsTrueAfterAddFile(): void
    {
        $request = new Request('http://example.com');
        $request->addFile('file', 'file content', 'document.txt');
        $this->assertTrue($request->hasFiles());
    }

    public function testGetFilesReturnsAddedFileData(): void
    {
        $request = new Request('http://example.com');
        $request->addFile('file', 'file content', 'document.txt');

        $files = $request->getFiles();

        $this->assertArrayHasKey('file', $files);
        $this->assertSame('file content', $files['file']['content']);
        $this->assertSame('document.txt', $files['file']['filename']);
    }
}
