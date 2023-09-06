<?php
// Get the current URL
$currentURL = $_SERVER['REQUEST_URI'];

// Extract the part of the URL after 'pmk/'
$titleSegment = substr($currentURL, strpos($currentURL, 'pmk/') + 4);




// Remove the ".php" extension if present
$titleSegment = str_replace('.php', '', $titleSegment);
// Remove any query string if present
$titleSegment = preg_replace('/\?.*/', '', $titleSegment);

// If the title segment is "dashboard", set the page title to "Dashboard" only
if ($titleSegment === 'dashboard') {
    $pageTitle = 'Dashboard';
} else {
    // Replace underscores with spaces and capitalize the first letter of each word
    $pageTitle = ucwords(str_replace('_', ' ', $titleSegment));

    // If the URL contains a slash, separate the segments
    if (strpos($titleSegment, '/') !== false) {
        $pageTitle = str_replace('/', ' | ', $pageTitle);
    }
}
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <title><?php echo $pageTitle; ?></title>
    <!-- <?php include("./utils/script.php"); ?> -->
    <?php include("../utils/script.php"); ?>
</head>

<body>