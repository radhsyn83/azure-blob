<?php

require_once 'vendor/autoload.php';

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=radhsyn83;AccountKey=pK6o1K7te4zu7CqHo+2Zh48nzkoajMkLlIsCphoFBgGhWo6TBufXHeu/wE3gSW2COzfQut0LmmqjcD5T+VlqSg==;EndpointSuffix=core.windows.net";
$containerName = "radhsyn83-container";
// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);

$listBlobsOptions = new ListBlobsOptions();
$listBlobsOptions->setPrefix("");
$res = $blobClient->listBlobs($containerName, $listBlobsOptions);


if (isset($_POST["getBlob"])) {
    $data = [];
    foreach ($res->getBlobs() as $r) {
        array_push($data, ["name" => $r->getName(), "url" => $r->getUrl()]);
    }
    echo json_encode($data);
}

if (isset($_POST["getAnalyze"])) {
    echo json_encode("halo");
}

if (isset($_POST["uploadImage"])) {
    $fileToUpload = strtolower($_FILES["imageUpload"]["name"]);
    $content = fopen($_FILES["imageUpload"]["tmp_name"], "r");
    $blobClient->createBlockBlob($containerName, $fileToUpload, $content);
    echo json_encode("sukses");
}