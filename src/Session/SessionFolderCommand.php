<?php

namespace CMIS\Session;

use CMIS\Entities\Folder;
use CMIS\Http\Client;
use CMIS\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use CMIS\Http\RequestFactory;
use GuzzleHttp\Psr7\Response;

class SessionFolderCommand extends SessionCommand
{

    /**
     * @return Folder
     * @throws GuzzleException
     */
    public function execute(): Folder
    {
        $response = $this->httpClient->post($this->request);
        $responseContent = $response->getBody()->getContents();
        $responseData = json_decode($responseContent, true);

        return (new Folder())
            ->setObjectId($responseData["properties"]["cmis:objectId"]["value"])
            ->setName($responseData["properties"]["cmis:name"]["value"])
            ->setCreationDate($responseData["properties"]["cmis:creationDate"]["value"])
            ->setCreatedBy($responseData["properties"]["cmis:createdBy"]["value"])
            ->setParentId($responseData["properties"]["cmis:parentId"]["value"])
            ->setPath($responseData["properties"]["cmis:path"]["value"]);
    }
}