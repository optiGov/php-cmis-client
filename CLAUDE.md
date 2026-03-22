# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a PHP CMIS (Content Management Interoperability Services) client library for communicating with CMIS-compliant content management systems. The library provides a simplified interface for creating documents, folders, and performing other CMIS operations using the Browser binding.

## Commands

### Testing
- Run tests: `composer test` or `./vendor/bin/phpunit tests`
- Run specific test: `./vendor/bin/phpunit tests/path/to/TestFile.php`
- Test configuration is in `phpunit.xml.dist`

### Dependencies
- Install dependencies: `composer install`
- Update dependencies: `composer update`

## Architecture

### Core Components

**Session Management (`src/Session/`)**
- `SessionFactory`: Creates CMIS sessions with authentication (basic auth or bearer token)
- `Session`: Main entry point for CMIS operations, manages HTTP client and repository connections
- `SessionOptions`: Configuration options for sessions (e.g., SSL verification)
- `SessionDocumentCommand` & `SessionFolderCommand`: Command objects for document and folder operations

**HTTP Layer (`src/Http/`)**
- `Client`: HTTP client wrapper around Guzzle with CMIS-specific authentication handling
- `Request`: Request builder that handles CMIS properties, post fields, URL parameters, and file uploads
- `RequestFactory`: Factory for creating Request instances

**Entities (`src/Entities/`)**
- `Document`: Represents CMIS documents with methods to retrieve content and properties
- `Folder`: Represents CMIS folders

### Authentication
The library supports two authentication methods:
- Basic authentication (username/password)
- Bearer token authentication

### Request Structure
CMIS requests use a specific format where:
- Properties are sent as `propertyId[n]` and `propertyValue[n]` pairs
- Files are handled as multipart form data
- URL parameters include `objectId` and `cmisselector` for data retrieval

### Key Patterns
- Fluent interface design throughout (method chaining)
- Factory pattern for creating sessions and requests
- Command pattern for document/folder operations
- Separation of HTTP concerns from business logic

## Development Notes

### Testing Configuration
- Test bootstrap in `tests/bootstrap.php` contains CMIS server credentials and constants
- Tests use PHPUnit 9.5+ 
- Test structure mirrors source structure under `tests/`

### PHP Requirements
- PHP 8.0+
- Guzzle HTTP library for HTTP client functionality
- cURL extension required

### CMIS Specifics
- Uses Browser binding (JSON over HTTP)
- Repository URL structure: `{base_url}/{repository_id}/root`
- Object type IDs are required for creating documents and folders
- CMIS selectors (`content`, `properties`) determine what data is returned