<?php

namespace CMIS\Session;

use CMIS\Entities\Folder;
use CMIS\Http\Client;
use CMIS\Http\Request;
use CMIS\Utils\Arr;
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

        return (new Folder($this->session))
            ->setObjectId(Arr::get($responseData, "properties.cmis:objectId.value"))
            ->setName(Arr::get($responseData, "properties.cmis:name.value"))
            ->setCreationDate(Arr::get($responseData, "properties.cmis:creationDate.value"))
            ->setCreatedBy(Arr::get($responseData, "properties.cmis:createdBy.value"))
            ->setParentId(Arr::get($responseData, "properties.cmis:parentId.value"))
            ->setPath(Arr::get($responseData, "properties.cmis:path.value"));
    }
}