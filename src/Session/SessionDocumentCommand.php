<?php

namespace CMIS\Session;

use CMIS\Entities\Document;
use CMIS\Entities\Folder;
use CMIS\Http\Client;
use CMIS\Http\Request;
use CMIS\Utils\Arr;
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

        return (new Document($this->session))
            ->setObjectId(Arr::get($responseData, "properties.cmis:objectId.value"))
            ->setName(Arr::get($responseData, "properties.cmis:name.value"))
            ->setCreationDate(Arr::get($responseData, "properties.cmis:creationDate.value"))
            ->setCreatedBy(Arr::get($responseData, "properties.cmis:createdBy.value"));
    }
}