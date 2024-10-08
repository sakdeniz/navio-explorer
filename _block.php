<?=title("Block Details")?>
<div class="p-4 w-full">
	<h4 class="text-lg font-bold text-white dark:text-white">Block Details</h4>
	<?
	try
	{
		if (!is_numeric($_GET["hash"]))
		{
			$sql="SELECT * FROM blocks WHERE hash=:hash AND network_id=:network_id LIMIT 1";
			$q=$GLOBALS['dbh']->prepare($sql);
			$q->bindParam(':hash',$_GET["hash"] , PDO::PARAM_STR);
			$q->bindParam(':network_id',$GLOBALS["network_id"], PDO::PARAM_STR);
		}
		else
		{
			$sql="SELECT * FROM blocks WHERE block_id=:block_id AND network_id=:network_id LIMIT 1";
			$q=$GLOBALS['dbh']->prepare($sql);
			$q->bindParam(':block_id',$_GET["hash"] , PDO::PARAM_STR);
			$q->bindParam(':network_id',$GLOBALS["network_id"], PDO::PARAM_STR);
		}
		$q->execute();
		if ($q->rowCount()>0)
		{
			$row=$q->fetch(PDO::FETCH_ASSOC);
			$data=json_decode($row["data"],true);
			?>
			<h5 class="text-white dark:text-white">Block Hash : <?=$row["hash"]?></h5>
			<h6 class="text-white dark:text-white">Block Height : <?=$row["block_id"]?></h6>
			<div class="relative overflow-x-auto mt-5">
				<table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400">
					<thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-zinc-900 dark:text-white">
						<tr>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Key
							</th>
							<th scope="col" class="px-6 py-3 text-gray-900 dark:text-white">
								Value
							</th>
						</tr>
					</thead>
					<tbody>
						<?
						foreach ($data as $k=>$v)
						{
							?>
							<tr class="bg-white text-gray-900 border-b dark:bg-zinc-800 dark:border-zinc-900 dark:text-white">
								<td class="px-6 py-4 text-gray-900 dark:text-white">
									<?=$k?>
								</td>
								<td class="px-6 py-4 text-gray-900 dark:text-white">
									<?
									if (is_array($v))
									{
										foreach ($v as $k2=>$v2)
										{
											if ($k=="tx")
											{
												echo "<a class='text-blue-600 dark:text-blue-400' href='tx/".$v2."'>" . $v2 . "</a><br/>";
											}
											else
											{
												echo $v2 . "<br/>";
											}
										}
									}
									else
									{
										?>
										<?=$v?>
										<?
									}
									?>
								</td>
							</tr>
							<?
						}
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