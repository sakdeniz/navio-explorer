<?=title("Peers")?>
<div class="p-4 w-full">
	<h4 class="text-lg font-bold text-white dark:text-white">Active Peer Details</h4>
	<?
	require 'vendor/autoload.php';
	require_once 'vendor/autoload.php';
	use MaxMind\Db\Reader;
	$CityDatabaseFile = 'GeoLite2-City.mmdb';
	$ASNDatabaseFile = 'GeoLite2-ASN.mmdb';
	$CityReader = new Reader($CityDatabaseFile);
	$ASNReader = new Reader($ASNDatabaseFile);
	try
	{
		$url="https://api.github.com/repos/nav-io/navio-core/commits?per_page=1";
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0;");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$content = curl_exec($ch);
		curl_close($ch);
		$json=json_decode($content);
		$latest_commit_hash=$json[0]->sha;
		$latest_commit_hash_short=substr($json[0]->sha,0,12);
		$latest_commit_date=$json[0]->commit->author->date;
		$d=DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $latest_commit_date, new DateTimeZone('Europe/Istanbul'));
		$latest_commit_date=date_format($d, 'd-m-Y H:i:s');
		?>
		<div class="text-white dark:text-white">
			Latest commit hash : <?=$latest_commit_hash;?> (<?=$latest_commit_hash_short?>) (<?=$latest_commit_date?> UTC)
		</div>
		<?
		$sql="SELECT * FROM peers WHERE network_id=:network_id LIMIT 1";
		$q=$GLOBALS['dbh']->prepare($sql);
		$q->bindParam(':network_id',$GLOBALS["network_id"], PDO::PARAM_INT);
		$q->execute();
		if ($q->rowCount()>0)
		{
			$row=$q->fetch(PDO::FETCH_ASSOC);
			$data=json_decode($row["data"],true);
			?>
			<div class="relative overflow-x-auto mt-5">
				<table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400">
					<thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-zinc-900 dark:text-white">
						<tr>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Region
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Country / Provider
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Address
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Version
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Sub Version
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Starting Height
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Synced Blocks
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Synced Headers
							</th>
						</tr>
					</thead>
					<tbody>
						<?
						foreach ($data[0] as $k=>$v)
						{
							$ip=gethostbyname(explode(":",$v["addr"])[0]);
							$city=$CityReader->get($ip);
							$asn=$ASNReader->get($ip);
							/*$url="https://api.ipapi.is/?q=".$ip;
							$ch=curl_init();
							curl_setopt($ch, CURLOPT_URL, $url);
							curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0;");
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							$ip_details = curl_exec($ch);
							$ip_details=json_decode($ip_details);*/
							//echo $url;
							//var_dump($ip_details);
							//echo "<hr>";
							curl_close($ch);
							if (strpos($v["subver"],$latest_commit_hash_short ) !== false)
							{
								$class="text-green-500 dark:text-green-500";
							}
							else
							{
								$class="text-gray-900 dark:text-white";
							}
							?>
							<tr class="bg-white text-gray-900 border-b dark:bg-zinc-800 dark:border-zinc-900 dark:text-white">
								<td>
									<?=$city["continent"]["names"]["en"];?> -
									<?=$city["continent"]["code"];?>
									<!--
									<p><?=$ip_details->location->continent?> - <?=$ip_details->location->state?> / <?=$ip_details->location->country?></p>
									<p><?=$ip_details->asn->org?></p>
									!-->
								</td>
								<td>
									<?=$city["city"]["names"]["en"]?> - 
									<?=$city["country"]["names"]["en"]?> (<?=$city["country"]["iso_code"]?>)
									<p><?=$asn["autonomous_system_organization"]?></p>
									<!--
									<p><?=$ip_details->location->continent?> - <?=$ip_details->location->state?> / <?=$ip_details->location->country?></p>
									<p><?=$ip_details->asn->org?></p>
									!-->
								</td>
								<td class="px-6 py-4 text-gray-900 dark:text-white">
									<?=$v["addr"]?>
								</td>
								<td class="px-6 py-4 text-gray-900 dark:text-white">
									<?=$v["version"]?>
								</td>
								<td class="px-6 py-4 <?=$class?>">
									<?=$v["subver"]?>
								</td>
								<td class="px-6 py-4 text-gray-900 dark:text-white">
									<?=$v["startingheight"]?>
								</td>
								<td class="px-6 py-4 text-gray-900 dark:text-white">
									<?=$v["synced_blocks"]?>
								</td>
								<td class="px-6 py-4 text-gray-900 dark:text-white">
									<?=$v["synced_headers"]?>
								</td>
							</tr>
							<?
						}
						$CityReader->close();
						$ASNReader->close();
						?>
					</tbody>
				</table>
			</div>
			<?
		}
	}
	catch (PDOException $e)
	{
		echo $e->getMessage();
	}
	?>
</div>