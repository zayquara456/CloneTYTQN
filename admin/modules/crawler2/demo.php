    <body>
    <div id="progress" style="width:500px;border:1px solid #ccc;"></div>
    <!-- Progress information -->
    <div id="information" style="width"></div>
    <?php
     
    if(isset($_REQUEST['sub']))
    {
    // Total processes
        $total = 10;
     
        // Loop through process
        for($i=1; $i<=$total; $i++){
            // Calculate the percentation
            $percent = intval($i/$total * 100)."%";
     
            // Javascript for updating the progress bar and information
            echo '<script language="javascript">
            document.getElementById("progress").innerHTML="<div style="width:'.$percent.';background-color:#ddd;">&nbsp;</div>";
            document.getElementById("information").innerHTML="'.$i.' row(s) processed.";
            </script>';
     
            // This is for the buffer achieve the minimum size in order to flush data
            echo str_repeat(' ',1024*64);
     
            // Send output to browser immediately
            flush();
     
            // Sleep one second so we can see the delay
            sleep(1);
        }
     
    // Tell user that the process is completed
    echo '<script language="javascript">document.getElementById("information").innerHTML="Process completed"</script>';
    }
    ?>
    <form>
    <input type="submit" name="sub" value="Go" />
    </form>
    </body>
           
    switch($_SERVER['REQUEST_METHOD']) {
        case 'GET': $the_request = &$_GET; break;
        case 'POST': $the_request = &$_POST; break;
        default:
    }
?>