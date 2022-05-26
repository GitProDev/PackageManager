<!DOCTYPE html>
<html>
<head>
	<title>Problem С</title>
</head>
<body>
	<form action="/monkeydigital/problemc.php" method="post">
	  <div class="mb-3 mt-3">
	    <label for="text" class="form-label">Enter packages:</label>
	    <textarea class="form-control" rows="5" id="data" name="data"></textarea>
	  </div>
	  <button type="submit" class="btn btn-primary">Submit</button>
	</form>
	<?php
		if(isset($_POST['data'])){
			$arr_all_lines = explode("\r\n", trim($_POST['data']));

			$arr_data_sets = [];
			$x = 0;
			$y = 0;
			foreach ($arr_all_lines as $line) {
				if(is_numeric(trim($line))){
					$x++;
					$y = 0;
					if($x >= 10)
						die('Max 10 test cases allowed!');
				}
				$arr_data_sets[$x][$y] = $line;
				$y++;
			}

			foreach ($arr_data_sets as $arr_lines) {
				main($arr_lines);
			}
		}
		function main($arr_lines){
			$n = 0;
	 		if(is_numeric(trim($arr_lines[0]))){
	 			$n = trim($arr_lines[0]);
	 			if(!($n >= 1 AND $n <= 1000))
	 				die('number must be >= 1 and <= 1000');	
	 		}
	 		else
	 			die('number expected at first line');

	 		//check if can be ordered
			for ($i = 1; $i <= $n; $i++){
			 	$arr_line = explode(' ', $arr_lines[$i]);
			 	$arr_deps = [];
			 	for($x = 1; $x < count($arr_line); $x++){
					array_push($arr_deps, $arr_line[$x]);
				}

			 	for($x = 1; $x <= $n; $x++){
			 		if($i == $x)
			 			continue;
					$arr_line2 = explode(' ', $arr_lines[$x]);	

					$arr_deps2 = [];
				 	for($y = 1; $y < count($arr_line2); $y++){
						array_push($arr_deps2, $arr_line2[$y]);
					}
					if(in_array($arr_line[0], $arr_deps2) AND in_array($arr_line2[0], $arr_deps)){
						die('cannot be ordered');
					}
			 	}
			}
			
			for ($i = 1; $i <= $n; $i++) {
				$arr_line = explode(' ', $arr_lines[$i]); //Get line in form of array
				if(count($arr_line) > 20){
					die('Package can have at most 20 dependancies');
				}
				if(preg_match('/[^a-z_\-0-9+.]/i', $arr_line[0]) OR strlen($arr_line[0]) > 40){
					die('Invalid package name: ' . $arr_line[0]);
				}
				if(count($arr_line) > 1){
					$pkg_name = $arr_line[0];
					$arr_deps = [];
					for($ii = 1; $ii < count($arr_line); $ii++){
						if(preg_match('/[^a-z_\-0-9+.]/i', $arr_line[$ii]) OR strlen($arr_line[$ii]) > 40)
							die('Invalid package name: ' . $arr_line[$ii]);
						array_push($arr_deps, $arr_line[$ii]);
					}
					foreach ($arr_deps as $dep_name) {
						if(!is_dep_in_right_place($pkg_name, $dep_name, $arr_lines, $n)){
							check_dep($dep_name, $arr_lines, $n);
						}
					}
				}
			}

			sort_packages($arr_lines, $n);
			foreach ($arr_lines as $arr_line) {
				if(is_numeric($arr_line)) continue;
				$arr_line = explode(' ', $arr_line);
				echo '<br>' . $arr_line[0];
				//echo $arr_line . '<br>';
			}
			echo '<br>------------------------------------------<br>';
		}

		function sort_packages(&$arr_lines, $n){ //bubble sort, happens at the end of whole process
			for($j=1; $j < sizeof($arr_lines); $j++){
				for($i=0; $i < sizeof($arr_lines) - $j; $i++){
					if($arr_lines[$i] > $arr_lines[$i + 1]){
						$tmp_arr_lines = $arr_lines;

						$tmp = $arr_lines[$i];
						$arr_lines[$i] = $arr_lines[$i + 1];
						$arr_lines[$i + 1] = $tmp;

						//Check if all dependancies are left above after
						$arr_pcks = explode(' ', $arr_lines[$i]);

						$replaced_pck = explode(' ', $arr_lines[$i + 1]);
						if(sizeof($arr_pcks) > 1){
							for($x = 1; $x < sizeof($arr_pcks); $x++){
								if($arr_pcks[$x] == $replaced_pck[0]){
									$arr_lines = $tmp_arr_lines;
									//echo 'CHECK POINT';
									break;
								}
							}
						}
					}
				}
			}
		}

		function check_dep($pkg_name, &$arr_lines, $n){
			for ($x = 1; $x <= $n; $x++) {
				$arr_line = explode(' ', $arr_lines[$x]);
				if($arr_line[0] == $pkg_name){
					if(count($arr_line) > 1){
						$arr_deps = [];
						for($y = 1; $y < count($arr_line); $y++){
							array_push($arr_deps, $arr_line[$y]);
						}
						foreach ($arr_deps as $dep_name) {
							if(!is_dep_in_right_place($pkg_name, $dep_name, $arr_lines, $n)){
								check_dep($dep_name, $arr_lines, $n);
							}
						}
					}
				}
			}
		}

		function is_dep_in_right_place($pkg_name, $dep_name, &$arr_lines, $n){
			$pkg_name_pos = 0;
			$dep_name_pos = 0;
			//detect positions of $dep_name and $pkg_name in $arr_lines
			for ($i = 1; $i <= $n; $i++) {
				$arr_line = explode(' ', $arr_lines[$i]); //Get line in form of array
				if($arr_line[0] == $pkg_name)
					$pkg_name_pos = $i;
				if($arr_line[0] == $dep_name)
					$dep_name_pos = $i;
			}
			
			//compare positins 
			if($pkg_name_pos < $dep_name_pos){
				$arr_lines = move_dep_above($pkg_name_pos, $dep_name_pos, $arr_lines);
				return false;
			}
			else
				return true;
		}
		function move_dep_above($pkg_name_pos, $dep_name_pos, $arr_lines){
			$new_arr_lines = [];
			for ($i = 0; $i < count($arr_lines); $i++) {
				if($i == $pkg_name_pos){
					$new_arr_lines[$pkg_name_pos] = $arr_lines[$dep_name_pos];
					$new_arr_lines[$i + 1] = $arr_lines[$pkg_name_pos];

				}
				elseif(!in_array($arr_lines[$i], $new_arr_lines)){
					array_push($new_arr_lines, $arr_lines[$i]);
				}
			 }

			return $new_arr_lines;
		}
	?>
</body>
</html>