<?php
require_once 'vendor/autoload.php';
require_once "./random_string.php";


use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('radhsyn83').";AccountKey=".getenv('pK6o1K7te4zu7CqHo+2Zh48nzkoajMkLlIsCphoFBgGhWo6TBufXHeu/wE3gSW2COzfQut0LmmqjcD5T+VlqSg==');

// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);

$fileToUpload = "HelloWorld.txt";

echo "Fathurs";