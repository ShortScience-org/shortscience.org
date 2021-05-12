<?php
// https://stackoverflow.com/questions/21133/simplest-way-to-profile-a-php-script
// Call this at each point of interest, passing a descriptive string
function prof_flag($str){
    global $prof_timing, $prof_names;
    $prof_timing[] = microtime(true);
    $prof_names[] = $str;
}

// Call this when you're done and want to see the results
function prof_print(){
    global $prof_timing, $prof_names;
    global $SPEED_PROFILE;
    if (!$SPEED_PROFILE){
        return;
    }
    $size = count($prof_timing);
    for($i=0;$i<$size - 1; $i++)
    {
        echo "<b>{$prof_names[$i]}</b><br>";
        echo sprintf("&nbsp;&nbsp;&nbsp;%f<br>", $prof_timing[$i+1]-$prof_timing[$i]);
    }
    echo "<b>{$prof_names[$size-1]}</b><br>";
}
?>