<?php

function heatmapcolor()
{
    $colarray=array();
    
    $max=255;
    for ($i=0; $i<200;$i++)
    {
        array_push($colarray,"rgb(".$max.",".$i*1.25.",0)");
        #print ("rgb(".$max.",".$i*1.25.",0)\n");
    }

    for ($i=0; $i<200;$i++)
    {
        $k=255-($i*1.25);
        #print ("rgb(".$k.","."255".",0)\n");
        array_push($colarray,"rgb(".$k.","."255".",0)"); 
    }

    for ($i=0; $i<200;$i++)
    {
        array_push($colarray,"rgb(0,255,".$i*1.25.")");
        #print ("rgb(0,255,".$i*1.25.")\n");
    }

    for ($i=0; $i<200;$i++)
    {
        array_push($colarray,"rgb(0,".",".$k.",0)"); 
        $k=255-($i*1.25);
        #print ("rgb(0,".",".$k.",0)\n");
    }

    for ($i=0; $i<200;$i++)
    {
        array_push($colarray,"rgb(".$i*1.25.",0,255)");
        #print ("rgb(".$i*1.25.",0,255)\n");
    }

    return $colarray;
}


#print_r(heatmapcolor())
?>
