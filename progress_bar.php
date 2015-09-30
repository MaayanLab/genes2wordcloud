<?php
// A function that will create the initial setup
// for the progress bar: You can modify this to
// your liking for visual purposes:
function create_progress_bars($i) {
  // First create our basic CSS that will control
  // the look of this bar:

echo "
<div class='text".$i."'>Script Progress</div>
<div id='barbox_a".$i."'></div>
<div class='bar".$i." blank".$i."'></div>
<div class='per".$i."'>0%</div>";
  // Ensure that this gets to the screen
  // immediately:
  flush();
}

// A function that you can pass a percentage as
// a whole number and it will generate the
// appropriate new div's to overlay the
// current ones:

function update_progress($n, $percent, $text) {
  // First let's recreate the percent with
  // the new one:
  echo "<div class='per".$n."'>{$percent}
    %</div>\n";
  
  echo "<div class='text".$n."'>{$text}
    </div>\n";

  // Now, output a new 'bar', forcing its width
  // to 3 times the percent, since we have
  // defined the percent bar to be at
  // 300 pixels wide.
  echo "<div class='bar".$n."' style='width: ",
    $percent * 3, "px'></div>\n";

  // Now, again, force this to be
  // immediately displayed:
  flush();
}
?>
