<?php
require_once("config.php");

// Get Program Details
if (php_sapi_name() === 'cli') {
    // CLI mode: use first argument
    $programId = isset($argv[1]) ? $argv[1] : '';
} else {
    // Web mode: use QUERY_STRING
    $programId = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
}

if ($programId === '') {
    fwrite(STDERR, "No program ID provided.\n");
    exit(1);
}

// Include lib.php for helpers
require_once("lib.php");

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
    $json = fetch_url( $program_url );
    $program_data = json_decode( $json, true );

    if (strlen($json))
    {
        if (!file_exists($programDir))
            mkdir($programDir, 0777, true);
        file_put_contents($jsonFile, $json);
    }
}
$image = '';
if (is_array($program_data) && isset($program_data["seriesEpisode"]["image"])) {
    $image = $program_data["seriesEpisode"]["image"];
}
if (!strlen($image) && is_array($program_data) && isset($program_data["image"])) {
    $image = $program_data["image"];
}

header('Content-Type: image/jpeg');
echo fetch_url("https://www.tvtv.us".$image);
?>

