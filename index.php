<?php
	$q = null; //query
	$p = 1; //page
	$data = null; //data
	require_once('class/flickr.class.php');

	if (isset($_GET['q'])) {
		if (isset($_GET['p'])) {
			$p = $_GET['p'];
		}

		$q = $_GET['q'];
		$fr = new FlickrPhotoSearch('7fed74fbcce722ef6dbbd40bd0ea007c');
		$data = $fr->searchPhotos($q, $p);
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Flickr</title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
	<header class="header">
		<div class="container">
			<div class="brand"><img src="assets/img/logo-flickr.png"></div>
			<form action="" class="search">
				<input type="text" name="q" placeholder="anything do you want..." value="<?php echo $q; ?>" class="form-field">
				<button class="btn btn-primary">OK</button>
			</form>
			<div class="cf"></div>
		</div>
	</header>

	<section class="body">
		<div class="container">
				<?php
					if (!empty($data)) {
						//photo gallery
						echo "<div class=\"img-gallery\">";
						foreach($data['photos']['photo'] as $photo) {
							$link = "http://farm{$photo['farm']}.static.flickr.com/{$photo['server']}/{$photo['id']}_{$photo['secret']}";
							$t = $link."_t.jpg";
							$o = $link.".jpg";

							echo "
								<div class=\"img\">
									<a href=\"{$o}\" target=\"_blank\"><img src=\"{$t}\"></a>
								</div>";
						}
						echo "</div>";

						//pagination
						$pg = $data['photos']['page'];
						$pgs = $data['photos']['pages'];

						//Query string to array
						$queryString = $_SERVER['QUERY_STRING'];
						parse_str($queryString, $params);
						if (!array_key_exists('p', $params)) {
							$params['p'] = $p;
						}

						if ($pgs > 1) {
							echo "<div class=\"paginator\">";
							if ($pg == 1) {
								$params['p'] += 1;
								$query = http_build_query($params);
								echo "<a href=\"?{$query}\">Next >></a>";
							} elseif ($pg == $pgs) {
								$params['p'] -= 1;
								$query = http_build_query($params);
								echo "<a href=\"?{$query}\"><< Prev</a>";
							} else {
								$params['p'] += 1;
								$queryN = http_build_query($params);
								$params['p'] -= 2;
								$queryP = http_build_query($params);
								echo "<a href=\"?{$queryP}\"><< Prev</a>&nbsp&nbsp|&nbsp&nbsp<a href=\"?{$queryN}\">Next >></a>";
							}
							echo "</div>";
						}

					} else {
						echo "<h1>Opss... It seems you have not searched yet :-)</h1>";
					}
				?>
				<div class="cf"></div>
		</div>
	</section>
</body>

</html>