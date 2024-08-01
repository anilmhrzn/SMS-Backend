<?php

namespace App\Service;


class PhotoProcessor
{


    public function processPhoto(string $photo, $getParameter): array
    {
        $parts = explode(',', $photo);

        if (count($parts) !== 2) {
            throw new \Exception('Invalid base64 string format of the image');
        }

        list($typePart, $base64String) = $parts;
        $base64String = str_replace(' ', '+', $base64String); // For URL-safe Base64

        $imageData = base64_decode($base64String);
        if ($imageData === false) {
            throw new \Exception('Base64 decode failed of the image');
        }

        $imageType = substr($typePart, strpos($typePart, '/') + 1, strpos($typePart, ';') - strpos($typePart, '/') - 1);
        if (!$imageType) {
            throw new \Exception('Invalid base64 string format of the image');
        }

        $newFilename = uniqid() . '.' . $imageType;


        $filePath = $getParameter . '/' . $newFilename;

        return ['filename'=>$newFilename,'filePath'=>$filePath,'imageData'=>$imageData];
    }
    public function moveFile( $filePath,$imageData)
    {

        if (file_put_contents($filePath, $imageData) === false) {
            throw new \Exception('File could not be written for the image');
        }
    }

}