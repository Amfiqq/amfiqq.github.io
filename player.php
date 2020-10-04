<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<title>DeathRun - Player Stats</title>
		
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
				padding-right: 20px;
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
				padding-right: 20px;
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
					<th align="center">#</th>
					<th align="center"><img src="images/misc/clock.png" /> Time</th>
					<th align="right"><img src="images/misc/calendar.png" /> Date</th>
				</tr>
			</thead>
			<tbody><?php
				include("include/config.php");
				
				$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysqli_error());
				
				mysqli_select_db($link, DB_NAME) or die('Could not select database');
				
				if (isset($_GET["authid"]))
					$authid = mysqli_real_escape_string($link, $_GET["authid"]);
				else
					$authid = "STEAM_0:0:64595454";
				
				$query_inf =
				"SELECT
					`NICKNAME`,
					`COUNTRY_FULL`,
					`COUNTRY_SHORT`
				FROM
					`DEATHRUN_STATS`
				WHERE
					`AUTHID` = '".$authid."'";
				
				if ($result_inf = mysqli_query($link, $query_inf))
				{
					if (mysqli_num_rows($result_inf))
					{
						$data = mysqli_fetch_assoc($result_inf);
						
						echo "<center><div class=\"header\"><img src=\"http://localhost/smth/images/flags/".$data["COUNTRY_SHORT"].".gif\" /> <b style=\"color: #00a7fa;\">".$data["NICKNAME"]."</b> (".$data["COUNTRY_FULL"].")</div></center><br />";
					}
				}
				
				$query =
				"SELECT
					*
				FROM
					`DEATHRUN_STATS`
				WHERE
					`AUTHID` = '".$authid."'
				GROUP BY
					`MAPNAME`
				ORDER BY
					`MAPNAME`";
				
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
							
							$date = $row["DATE"];
							$date = date("d-m-Y");
							
							echo "
				<tr>";
							echo "
					<td align=\"left\"><b><a href=\"".WEB_FILES_LINK."/maptop.php?mapname=".$mapname."\">".$mapname."</a></b></td>";
							
							$query2 =
							"SELECT
								*
							FROM
								`DEATHRUN_STATS`
							WHERE
								`MAPNAME` = '$mapname'
							ORDER BY
								`TIME`";
							
							$result2 = mysqli_query($link, $query2);
							
							$i = 1;
							
							while ($row2 = mysqli_fetch_assoc($result2))
							{
							if ($authid == $row2["AUTHID"])
							break;
							
							$i++;
							}
							
							mysqli_free_result($result2);
							
							echo "
					<td align=\"center\">".$i."</td>";
							echo "
					<td align=\"center\">".climbtimeToString($row["TIME"])."</td>";
							echo "
					<td align=\"right\">".$date."</td>";
							echo "
				</tr>";
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