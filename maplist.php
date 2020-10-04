<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<title>DeathRun - Map List</title>
		
		<link rel="stylesheet prefetch" href="https://fonts.googleapis.com/css?family=Open+Sans:400,700,800" />
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		
		<style type="text/css">
			th:nth-child(1)
			{
				padding-left: 20px;
			}
			th:nth-child(2)
			{
				padding-left: 40px;
				padding-right: 400px;
			}
			th:nth-child(3)
			{
				padding-right: 40px;
			}
			th:nth-child(4)
			{
				padding-right: 20px;
			}
			td:nth-child(1)
			{
				padding-left: 20px;
			}
			td:nth-child(2)
			{
				padding-left: 40px;
			}
			td:nth-child(3)
			{
				padding-right: 40px;
			}
			td:nth-child(4)
			{
				padding-right: 20px;
			}
		</style>
	</head>
	<body>
		<br />
		
		<table align="center" cellpadding="0" cellspacing="0" border="0">
			<thead>
				<tr>
					<th align="left"><img src="images/misc/map.png" /> Map</th>
					<th align="left"><img src="images/misc/player.png" /> Player</th>
					<th align="center"><img src="images/misc/clock.png" /> Time</th>
					<th align="right"><img src="images/misc/calendar.png" /> Date</th>
				</tr>
			</thead>
			<tbody><?php
				include("include/config.php");
				
				$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysqli_error());
				
				mysqli_select_db($link, DB_NAME) or die('Could not select database');
				
				$query =
				"
					SELECT
						`MAPNAME`
					FROM
						`DEATHRUN_STATS`
					GROUP BY
						`MAPNAME`
					ORDER BY
						`MAPNAME`
					DESC
                ";
				
				if ($result = mysqli_query($link, $query))
				{
					if (mysqli_num_rows($result))
					{
						function climbtimeToString($time)
						{
							$time = explode(".", $time);
							
							$seconds = $time[0];
							$miliseconds = substr($time[1], 0, 2);
							
#							$hours = $seconds / 3600;
							$minutes = $seconds / 60;
							$seconds = $seconds % 60;
							
							return sprintf("%02d:%02d<font id=\"miliseconds\">.%02d</font>", $minutes, $seconds, $miliseconds);
						}
						
						while ($row = mysqli_fetch_assoc($result))
						{
							$mapname = $row["MAPNAME"];
							
                            $query2 =
							"SELECT
								`AUTHID`,
								`NICKNAME`,
								`COUNTRY_SHORT`,
								`TIME`,
								DATE(`DATE`) AS `DATE`
							FROM
								`DEATHRUN_STATS`
							WHERE
								`MAPNAME` = '".$mapname."'
							ORDER BY
								`TIME`
							LIMIT 1";
							
							if ($result2 = mysqli_query($link, $query2))
							{
								if (mysqli_num_rows($result2))
								{
									while ($row2 = mysqli_fetch_assoc($result2))
									{
									echo "
					<tr>";
									echo "
						<td align=\"left\"><b><a href=\"".WEB_FILES_LINK."/maptop.php?mapname=".$mapname."\">".$mapname."</a></b></td>";
									echo "
						<td align=\"left\"><img src=\"".WEB_FILES_LINK."/images/flags/".$row2["COUNTRY_SHORT"].".gif\"> <b><a href=\"".WEB_FILES_LINK."/player.php?authid=".$row2["AUTHID"]."\">".htmlspecialchars($row2["NICKNAME"])."</a></b></td>";
									echo "
						<td align=\"center\">".climbtimeToString($row2["TIME"])."</td>";
									echo "
						<td align=\"right\">".$row2["DATE"]."</td>";
									echo "
					</tr>";
									}
								}
								mysqli_free_result($result2);
							}
						}
					}
					
					mysqli_free_result($result);
				}
				mysqli_close($link);
			?>

			</tbody>
		</table>
	</body>
</html>