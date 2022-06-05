<?php

namespace CMIS\Session;

use CMIS\Entities\Document;
use CMIS\Entities\Folder;
use CMIS\Http\Client;
use CMIS\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use CMIS\Http\RequestFactory;
use GuzzleHttp\Psr7\Response;

class SessionDocumentCommand extends SessionCommand
{

    /**
     * @return Document
     * @throws GuzzleException
     */
    public function execute(): Document
    {
        $response = $this->httpClient->post($this->request);
        $responseContent = $response->getBody()->getContents();
        $responseData = json_decode($responseContent, true);

        return (new Document())
            ->setObjectId($responseData["properties"]["cmis:objectId"]["value"])
            ->setName($responseData["properties"]["cmis:name"]["value"])
            ->setCreationDate($responseData["properties"]["cmis:creationDate"]["value"])
            ->setCreatedBy($responseData["properties"]["cmis:createdBy"]["value"]);
    }
}