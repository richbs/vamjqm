<?php 
/*
God I can't believe I'm writing a dodgy piece of PHP
*/

$lat = $_GET['lat'];
$lon = $_GET['lon'];
$place = $_GET['place'];
$images = $_GET['images'];

if ($lat && $lon ) {
	
	$places =  array();
	$counts = array();
	
	$apiurl = 'http://www.vam.ac.uk/api/json/place/?latitude=' . $lat .'&longitude='  . $lon . '&orderby=distance&order=asc&radius=1000&limit=25';
    
		$json = file_get_contents($apiurl);
		$data = json_decode($json);

		foreach ($data->records as $r )	{
				$places[$r->pk] = $r->fields->name;
				$counts[$r->pk] = $r->fields->museumobject_count;
		}
			
	print '<div data-role="page">';
	print '<div data-role="header"><h1>Places near you</h1></div>';
	print '<div data-role="content"><ul data-role="listview" data-theme="g">';
	foreach($places as $id=>$p) {
		print '<li><a href="#results" id="'.$id.'">' . $p . '</a><small class="counter">' . $counts[$id] .'</small></li>';
	}
	print "</ul></div></div>";
	
} elseif ($place && $images ) {
    
    $apiurl = 'http://www.vam.ac.uk/api/json/museumobject/?place=' .urlencode($place) .'&limit=15&images=1';
	$json = file_get_contents($apiurl);
	$data = json_decode($json);
    if ( count($data->records ) > 0 ) {
		for ($i=0; $i < count($data->records); $i++ )	{
		    $ii = $i +1;
		    $iii = $ii +1;
		    if ( $ii ==  count($data->records) ) {
		        $iii = '';
		    }
		    $r = $data->records[$i];
			$imdir = substr( $r->fields->primary_image_id , 0, 6);
			$thurl = 'http://media.vam.ac.uk/media/thira/collection_images/' . $imdir . '/'  .$r->fields->primary_image_id . '_jpg_w.jpg';
			$colurl = 'http://m.vam.ac.uk/collections/item/' . $r->fields->object_number;
           echo '<div class="lg" id="img'.$ii.'">';
		   echo '<div class="toolbar"><h1>' . $r->fields->object .'</h1><a class="back" href="#">Back</a></div>';
			echo '<a class="swipeme" id="i'.$ii.'" href="#img'.$iii.'">';
			echo '<img class="thumb" width="320" height="320" src="'.$thurl.'" alt="'. $r->fields->object.'" /></a>';
			echo '<p class="info">' . $r->fields->title. ' ' . $r->fields->object .' (' . $r->fields->artist  .')</p>';
			print "</div>";
	    }
	} else{
	    echo '<ul class="edgetoedge places"><li>Sorry, no images for this place. Please tap "Back" above.</li></ul>';
	    
	}	

} elseif ($place) {
	
		$apiurl = 'http://www.vam.ac.uk/api/json/museumobject/?place=' .urlencode($place) .'&limit=15&images=1';

		$json = file_get_contents($apiurl);
		$data = json_decode($json);
		
		
        if ( count($data->records ) > 0 ) {
	    	print "<ul rel='".$place."' class=\"places edgetoedge\">";

			for ($i=0; $i < count($data->records); $i++ )	{
			    $ii = $i +1;

			    $r = $data->records[$i];			
    			$imdir = substr( $r->fields->primary_image_id , 0, 6);
    			$thurl = 'http://media.vam.ac.uk/media/thira/collection_images/' . $imdir . '/'  .$r->fields->primary_image_id . '_jpg_s.jpg';
    			$imurl = 'http://media.vam.ac.uk/media/thira/collection_images/' . $imdir . '/'  .$r->fields->primary_image_id . '_jpg_l.jpg';
    			$colurl = 'http://m.vam.ac.uk/collections/item/' . $r->fields->object_number;
               echo '<li class="forward">'; 
				echo '<a class="slide" href="#img'.$ii.'">';
				echo '<img class="thumb" width="70" height="70" src="'.$thurl.'" alt="'. $r->fields->object.'" /><span>'    ;
    			echo $r->fields->object . '</span></a>';
    			print "</li>";
		    }
			print "</ul>";
		} else{
		    echo '<ul class="edgetoedge places"><li>Sorry, no images for this place. Please tap "Back" above.</li></ul>';
		}
	}