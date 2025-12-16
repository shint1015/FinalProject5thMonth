<?php
$data = file_get_contents(__DIR__, "/../data/shows.json");
$shows = json_decode($data, true);
$found = null;

foreach($shows as $show){
    if($show["id"]=== $id){
        $found = $show;
        break;
    }
}

?>