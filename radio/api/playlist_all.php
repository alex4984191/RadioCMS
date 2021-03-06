<?php
	include_once('../Include.php');

	$playlist = Playlist::create();
    $request = Request::create();
	$playlistAll = PlaylistAll::create();

	$notice = $playlistAll->handler();
    $sort = $playlistAll->getSort();
	$start = $playlistAll->getStart();
	$limit = $playlistAll->getLimit();
	$search = $playlistAll->getSearch();
	$letter = $playlistAll->getLetter();

    //Get he playlist's path
	$url_start = $playlistAll->getUrlStart();

	if (!empty($notice['order'])) {
		foreach ($notice['order'] as $message) {
?>
			<p><?=$message?></p>
<?php
		}
	}
?>
	<a href="<?=$url_start?>?limit=<?=$limit?>">Все</a>
	<a href="<?=$url_start?>?letter=0-9&limit=<?=$limit?>">0 - 9</a>,
<?php
	foreach (range("A","Z") as $word) {
?>
		<a href="<?=$url_start?>?letter=<?=strtolower($word)?>&limit=<?=$limit?>"><?=$word?></a><?=$word!="Z"?',':''?>
<?php
	}
?>
	<form method="GET" action="">
		<p>
			<?php echo _('Search');?> <input type="text" name="search" size="20" value="<?=$playlistAll->getSearchString()?>">
			<input type="hidden" name="limit" size="20" value="<?=$limit;?>">
			<input type="submit" value="<?php echo _('Search');?>">
		</p>
	</form>
	<table border="0"  cellspacing="0" cellpadding="0" width="100%"  class="table1">
		<tr>
			<td width="250">
				<?php echo _('Title');?>
				<span>
					<a href="<?=$url_start?>?start=<?=$start?>&limit=<?=$limit?>&sort=title&letter=<?=$letter?>&search=<?=$search?>"><img src="/radio/images/up.png" border="0"></a>
					<a href="<?=$url_start?>?start=<?=$start?>&limit=<?=$limit?>&sort=!title&letter=<?=$letter?>&search=<?=$search?>"><img src="/radio/images/down.png" border="0"></a>
				</span>
			</td>
			<td width="210">
				<?php echo _('Artist');?>
				<span>
					<a href="<?=$url_start?>?start=<?=$start?>&limit=<?=$limit?>&sort=artist&letter=<?=$letter?>&search=<?=$search?>"><img src="/radio/images/up.png" border="0"></a>
					<a href="<?=$url_start?>?start=<?=$start?>&limit=<?=$limit?>&sort=!artist&letter=<?=$letter?>&search=<?=$search?>"><img src="/radio/images/down.png" border="0"></a>
				</span>
			</td>
			<td align=center>
				<?php echo _('Order');?>
			</td>
			<td align=center>
				<?php echo _('Time');?>
			</td>
		</tr>
		<form method="POST" action="">
<?php
	$order_i = 0;
	$i = 0;
	foreach ($playlistAll->getSongList() as $line) {
?>
		<tr <?=($i != 1) ? 'bgcolor=#F5F4F7':''?>>
			<td width="250">
				<?=$line['title']?>
			</td>
			<td width="210">
				<?=$line['artist']?>
<?php
		if (!empty($line['album']) and $line['album']!= " ") {
?>
				(<?=$line['album']?>)
<?php
		}
?>
			</td>
			<td align="center">
				<input type=image src="/radio/images/headphones.png" width="32" height="32" name="order_<?=$order_i?>">
				<input type="hidden" name="order_<?=$order_i ?>" value="<?=$line['idsong']?>">
<?php
			$order_i = $order_i+1;
?>
			</td>
			<td align="center">
				<?=$playlist->getDuration($line['duration'])?>
			</td>
		</tr>
<?php
     	if ($i == 1) {
     		$i = 0;
     	} else {
     		$i = $i+1;
     	}
	}
?>
		</form>
	</table>
	<p>
<?php
	$seychas = $start+$limit;
    $sort_string = ($request->hasGetVar('sort')) ? "&sort=$sort" : "";

	if ($limit <= $start) {
		$pokaz = $start-$limit;
?>
			<img border="0" src="/radio/images/prev.gif" width="9" height="10">
			<a href="<?=$url_start?>?start=<?=$pokaz?>&limit=<?=$limit?><?=$sort_string?>&letter=<?=$letter?>&search=<?=$search?>"><?php echo _('Back');?></a>
<?php
	}

	$vsego_pesen = $playlistAll->getVsegoPesen();

	if (($limit <= $start) and ($vsego_pesen > $seychas)) {
			echo _(" or ");
	}

	$pokaz = $start+$limit;
	if ($vsego_pesen > $seychas) {
?>
			<a href="<?=$url_start?>?start=<?=$pokaz?>&limit=<?=$limit?><?=$sort_string?>&letter=<?=$letter?>&search=<?=$search?>"><?php echo _('Next');?></a>
			<img border="0" src="/radio/images/next.gif" width="9" height="10">
<?php
	}

?>
	</p>
	<form method="GET" action="">
		<?php echo _('Show elements');?>
		<select size="1" name="limit">
			<option<?php if ($limit==5) echo " selected"; ?>>5</option>
			<option<?php if ($limit==10) echo " selected"; ?>>10</option>
			<option<?php if ($limit==25) echo " selected"; ?>>25</option>
			<option<?php if ($limit==50) echo " selected"; ?>>50</option>
		</select> песен
		<input type="hidden" name="letter" value="<?=$letter?>">
		<input type="hidden" name="search" value="<?=$search?>">
		<input type="hidden" name="sort" value="<?=$sort?>">
		<input type="hidden" name="start" value="<?=$start?>">
		<input type="submit" value="Ок">
	</form>