<?php
// Create the image with width=150 and height=40
$IMGVER_IMAGE = imagecreate(150,40);

// Allocate two colors (Black & White)
// This uses the RGB names of the colors
$IMGVER_COLOR_BLACK = imagecolorallocate ($IMGVER_IMAGE, 0, 0, 0);
$IMGVER_COLOR_WHITE = imagecolorallocate ($IMGVER_IMAGE, 255, 255, 255);

// Flood Fill our image with black
imagefill($IMGVER_IMAGE, 0, 0, $IMGVER_COLOR_BLACK);

// This handles our session. We get the random text that
// was stored in our session var on the first page.
session_start();
$IMGVER_RandomText = $HTTP_SESSION_VARS["IMGVER_RndText"];
 
// Since our Text had 6 chars (we defined this not to be longer)
// we now write the 6 random chars in our picture
// For those who don´t know: You can access the third character
//in a string easily by typing $myString[2];
imagechar($IMGVER_IMAGE, 5, 20, 13, $IMGVER_RandomText[0] ,$IMGVER_COLOR_WHITE);
imagechar($IMGVER_IMAGE, 5, 40, 13, $IMGVER_RandomText[1] ,$IMGVER_COLOR_WHITE);
imagechar($IMGVER_IMAGE, 5, 60, 13, $IMGVER_RandomText[2] ,$IMGVER_COLOR_WHITE);
imagechar($IMGVER_IMAGE, 5, 80, 13, $IMGVER_RandomText[3] ,$IMGVER_COLOR_WHITE);
imagechar($IMGVER_IMAGE, 5, 100, 13, $IMGVER_RandomText[4] ,$IMGVER_COLOR_WHITE);
imagechar($IMGVER_IMAGE, 5, 120, 13, $IMGVER_RandomText[5] ,$IMGVER_COLOR_WHITE);

//Now we send the picture to the Browser
header("Content-type: image/jpeg");
imagejpeg($IMGVER_IMAGE);
?>
