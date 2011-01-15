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
		print '<li><a href="api.php?place='.$id.'" id="'.$id.'">' . $p . '</a><small class="counter">' . $counts[$id] .'</small></li>';
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
           echo '<div data-role="page" id="img'.$ii.'">';
		   echo '<div data-role="header"><h1>' . $r->fields->object .'</h1><a class="back" href="#">Back</a></div>';
		echo '<div data-role="content">';
			echo '<a class="swipeme" id="i'.$ii.'" href="#img'.$iii.'">';
			echo '<img class="thumb" width="320" height="320" src="'.$thurl.'" alt="'. $r->fields->object.'" /></a>';
			echo '<p class="info">' . $r->fields->title. ' ' . $r->fields->object .' (' . $r->fields->artist  .')</p>';
			print "</div></div>";
	    }
	} else{
	    echo '<div data-role="page">';
		print '<div data-role="header"><h1>Sorry…</h1></div>';
		print '<div data-role="content"><p>No images here.</p></div></div>';
	}	

} elseif ($place) {
	
		$apiurl = 'http://www.vam.ac.uk/api/json/museumobject/?place=' .urlencode($place) .'&limit=15&images=1';

		$json = file_get_contents($apiurl);
		$data = json_decode($json);
		echo '<div data-role="page">';
		print '<div data-role="header"><h1>Sorry…</h1></div>';
		print '<div data-role="content"><p>No images here.</p></div></div>';		
		
        if ( count($data->records ) > 0 ) {
			print '<div data-role="page">';
			print '<div data-role="header"><h1>Found Objects</h1></div>';
			print '<div data-role="content"><ul data-role="listview" data-theme="g" rel="'.$place.'">';

			for ($i=0; $i < count($data->records); $i++ )	{
			    $ii = $i +1;

			    $r = $data->records[$i];			
    			$imdir = substr( $r->fields->primary_image_id , 0, 6);
    			$thurl = 'http://media.vam.ac.uk/media/thira/collection_images/' . $imdir . '/'  .$r->fields->primary_image_id . '_jpg_s.jpg';
    			$imurl = 'http://media.vam.ac.uk/media/thira/collection_images/' . $imdir . '/'  .$r->fields->primary_image_id . '_jpg_l.jpg';
    			$colurl = 'http://m.vam.ac.uk/collections/item/' . $r->fields->object_number;
               echo '<li class="forward">'; 
				echo '<a href="#img'.$ii.'">';
				echo '<img class="thumb" width="70" height="70" src="'.$thurl.'" alt="'. $r->fields->object.'" /><span>'    ;
    			echo $r->fields->object . '</span></a>';
    			print "</li>";
		    }
			print "</ul></div></div>";

		} else{
		    echo '<div data-role="page">';
			print '<div data-role="header"><h1>Sorry…</h1></div>';
			print '<div data-role="content"><p>No images here.</p></div></div>';
		}
	}