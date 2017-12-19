<?php
class flickr
{
	var $api;
	function __construct($api) {
		$this->api = $api;
	}
	
	function flickr_photos_search($search='Vegita',$count_image=100,$size='t')
	{
		$params = array(
		'api_key'	=> $this->api,
		'method'	=> 'flickr.photos.search',
		'text'	=> $search,
		'format'	=> 'rest',
		'per_page' => $count_image,
		'page'		=> 1,);
		$xml = $this->create_url($params);
		if(@$rsp = simplexml_load_file($xml))
		{
			if (count($rsp)<>0)
			{
				foreach($rsp->photos->children() as $photo)
				{
					if ($photo->getName()=='photo')
					{
						$farm=$photo->attributes()->farm;
						$server=$photo->attributes()->server;
						$id=$photo->attributes()->id;
						$secret=$photo->attributes()->secret;
						if ($size=='Med')
						{
							$sz="";
						}
						else
						{
							$sz = "_".$size;
						}
						$gbr[]='<a href="https://farm'.$farm.'.staticflickr.com/'.$server.'/'.$id.'_'.$secret."".'.jpg'.'"><img src="https://farm'.$farm.'.staticflickr.com/'.$server.'/'.$id.'_'.$secret.$sz.'.jpg'.'" /></a> ';
					}
				}
			}
			else
			{
				die("No images found!");
			}
		}else
		{
			die("wrong parameter");
		}
		return $gbr;
	}
	function create_url($params)
	{
		$encoded_params = array();
		foreach ($params as $k => $v){

			$encoded_params[] = urlencode($k).'='.urlencode($v);
		}
		$url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);
		return $url;
	}
}
	echo '<form method="post">';
	echo '<label>Search : </label><input type"text" value="Vegita"  name="search" /><br/>';
	echo '<input type="submit" name="submit" value="search" /><br/><hr/>';
	echo '</form>';
	$result=100;
	$size='t';
if(isset($_POST['submit']))
{
	if(!isset($_POST['search']))
	{
		echo 'Please fill your search';
	}else
	{
		$search=$_POST['search'];
		$result=100;
		$size='t';
		if($size > 500)
		{
			$size = 500;
		}
		$flickr= new flickr('78dc2b13d9cc1127139a1aa12bf9fcc6');
		$gbr = $flickr->flickr_photos_search($search,$result,$size);
		foreach($gbr as $hasil)
		{
			echo $hasil.' ';
		}
	}
}
?>