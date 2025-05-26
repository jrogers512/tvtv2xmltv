<?php
require_once("config.php");

// Get Program Details
$programId = $_SERVER['QUERY_STRING'];

// Check to see if .json file for program already exists in the data folder
$programDir = $dataFolder."/program/".substr($programId, 0, 8);
$jsonFile = $programDir."/".$programId.".json";
if ( file_exists($jsonFile) )
{
    $json = file_get_contents( $jsonFile );
    $program_data = json_decode( $json, true );
}
else
{
    $program_url = "https://www.tvtv.us/api/v1/programs/".$programId;
    $json = file_get_contents( $program_url );
    $program_data = json_decode( $json, true );

    if (strlen($json))
    {
        if (!file_exists($programDir))
            mkdir($programDir, 0777, true);
        file_put_contents($jsonFile, $json);
    }
}
$image = $program_data["seriesEpisode"]["image"];
if (!strlen($image))
    $image = $program_data["image"];

header('Content-Type: image/jpeg');
echo file_get_contents("https://www.tvtv.us".$image);

