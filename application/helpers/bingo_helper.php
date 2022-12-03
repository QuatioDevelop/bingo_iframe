<?php

/*function saveData($name, $value){
	global $room;
	$_SESSION[$name."_".$room] = $value;
}*/

if (!function_exists('getallheaders')) {
    function getallheaders() {
    $headers = [];
	foreach($_SERVER as $name => $value) {
	    if($name != 'HTTP_MOD_REWRITE' && (substr($name, 0, 5) == 'HTTP_' || $name == 'CONTENT_LENGTH' || $name == 'CONTENT_TYPE')) {
	        $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', str_replace('HTTP_', '', $name)))));
	        if($name == 'Content-Type') $name = 'Content-type';
	        $headers[$name] = $value;
	    }
	}
    return $headers;
    }
}

function set_exists() {
	//global $room;	
	//global $setid;
	//$setid = get_set_id();
	// echo "setid ". $setid." room ". $room;
	//return (file_exists("sets/set.".$setid.".json"));

	$CI =& get_instance();
	$setid = get_set_id();

	//Leer el último registro de la sala
	//$mysql  = new mysqli('localhost', 'admin-bingo', 'ulU!ad5DBq-.', 'bingo_virtual');
	//$sql_bingo = "SELECT id FROM bingos WHERE room='".$room."'". 
	//			" ORDER BY id DESC".
	//			" LIMIT 1";

	$result = $CI->bingo_model->setExits($setid);
	if (isset($result) > 0){
		
		return true;
	} else return false;
}


function get_set_id(){
	$CI =& get_instance();
	
	if($CI->config->item('setid') !== null)
	{
		return $CI->config->item('setid');
	}else
	{
		$result = $CI->bingo_model->getLastBingoId($CI->config->item('room'));//$mysql->query($sql_bingo) or die('<script type="text/javascript">window.location.href="config.php?ge=1";</script>'.$mysql->error);
		//print_r($result);
		// die('');
		if ($result) {
			$CI->config->set_item('setid',$result->id);
			//saveData("setid", $setid);
			return $CI->config->item('setid');
		}else
		{
			$CI->bingo_model->createBingo($CI->config->item('room'));
		}
	}
}
function get_set_name(){
	$CI =& get_instance();
	
	if($CI->config->item('setname') !== null)
	{
		return $CI->config->item('setname');
	}else
	{
		$result = $CI->bingo_model->getLastBingoId($CI->config->item('room'));//$mysql->query($sql_bingo) or die('<script type="text/javascript">window.location.href="config.php?ge=1";</script>'.$mysql->error);
		//print_r($result);
		// die('');
		if ($result) {
			$CI->config->set_item('setname',$result->name);
			//saveData("setid", $setid);
			return $CI->config->item('setname');
		}else
		{
			//$CI->bingo_model->createBingo($CI->config->item('room'));
			return get_set_id();
		}
	}
}

function get_number_cards(){
	$CI =& get_instance();
	$setid = get_set_id();
	
	//$mysql  = new mysqli('localhost', 'admin-bingo', 'ulU!ad5DBq-.', 'bingo_virtual');
	//$sql_bingo = "SELECT MAX(table_index) as table_index FROM user_table WHERE room ='".$setid."' ";
	$result = $CI->bingo_model->getNumCards($setid);
	//print_r($result); 
	if ($result !== null) {
		$count = $result->table_index;
	}else
	{
		$count = 0;
	}
	return $count;

}


/** card_number()
* This function returns the number of cards found in the card set
* user must verify that set exists before calling this function.
* This function will reload the set from the disk.  Use
* count($set) if you already opened and loaded the set to fasten
* the process
*/
/*function card_number() {
	$set=load_set();
	if (is_array($set))
		return count($set);
}
*/
/** generate_cards()
* This function generate the appropriate number of cards
* All cards are randomly generated, and have a Free square
* in the center.  The function saves the newly created set.
* This function will overwrite an existing set with the same
* setid.
*/
function generate_cards($numbercards,$freesquare) {
	$CI =& get_instance();
	//global $maxColumnNumber;
	$maxColumnNumber = $CI->config->item('maxColumnNumber');

	// $freesquare = 1; //Para generar siempre las cartas con un espacio en el medio
	for ($cardnumber = 0; $cardnumber <$numbercards; $cardnumber++) {

		// The next two variables are only used when freesquare == 2 (random location)
		$randcolumn = rand(0,4);
		$randrow = rand(0,4);

		for ($column = 0; $column<5; $column++) {
			for ($row = 0; $row<5; $row++) {
				if (($column==2) && ($row == 2) && ($freesquare==1)) {  //free
					$set[$cardnumber][$column][$row]["n"] = "f";  //free
					$set[$cardnumber][$column][$row]["c"] = true;  //free
				}
				else if (($column==$randcolumn) && ($row==$randrow) && ($freesquare==2)){
					$set[$cardnumber][$column][$row]["n"] = "f";  //free
					$set[$cardnumber][$column][$row]["c"] = true;  //free
				}
				else {
					//to ensure we do not have the same number twice in one column
					$numberexists = true; //loop entry
					while ($numberexists) {
						$random = rand(1+$column*$maxColumnNumber,($column+1)*$maxColumnNumber);

						$numberexists =false; //assume it is not repeated until it is found
						for ($checker=0; $checker<$row; $checker++)
						if ($set[$cardnumber][$column][$checker]["n"] == $random) $numberexists=true;
					}

					$set[$cardnumber][$column][$row]["n"] = $random;
					$set[$cardnumber][$column][$row]["c"] = false;
				}
			} //row
		} //column
	}	//cardnumbber

	save_set($set);
}

function generate_card($freesquare) {
	$CI =& get_instance();
	//global $maxColumnNumber;
	$maxColumnNumber = $CI->config->item('maxColumnNumber');

	// $freesquare = 1; //Para generar siempre las cartas con un espacio en el medio
	//for ($cardnumber = 0; $cardnumber <$numbercards; $cardnumber++) {

		// The next two variables are only used when freesquare == 2 (random location)
		$randcolumn = rand(0,4);
		$randrow = rand(0,4);

		for ($column = 0; $column<5; $column++) {
			for ($row = 0; $row<5; $row++) {
				if (($column==2) && ($row == 2) && ($freesquare==1)) {  //free
					$set[$column][$row]["n"] = "f";  //free
					//$set[$column][$row]["c"] = true;  //free
				}
				else if (($column==$randcolumn) && ($row==$randrow) && ($freesquare==2)){
					$set[$column][$row]["n"] = "f";  //free
					//$set[$column][$row]["c"] = true;  //free
				}
				else {
					//to ensure we do not have the same number twice in one column
					$numberexists = true; //loop entry
					while ($numberexists) {
						$random = rand(1+$column*$maxColumnNumber,($column+1)*$maxColumnNumber);

						$numberexists =false; //assume it is not repeated until it is found
						for ($checker=0; $checker<$row; $checker++)
						if ($set[$column][$checker]["n"] == $random) $numberexists=true;
					}

					$set[$column][$row]["n"] = $random;
					//$set[$column][$row]["c"] = false;
				}
			} //row
		} //column
	//}	//cardnumbber

	//save_set($set);
	return $set;
}


/** display_card()
* This function draws an HTML table for one or four cards.
* By setting the $fourperpage parameter to 1, the tables
* are drawn smaller.
*/
function display_pretty_card($cardnumber, $showbtns = true, $showchecked = true){
	if($cardnumber != "[Error]"){

	//global $bingoletters, $setid;
	$CI =& get_instance();
	$setid = get_set_id();
	$bingoletters = $CI->config->item('bingoletters');
	$draws = load_draws();
	//$set = load_set();  //normal cards
	//print_r($cardnumber);
	//print_r($set);
		//if(isset($set) > 0){
			for ($i=0; $i < sizeof($cardnumber); $i++) { 
				//if (isset($set[$cardnumber[$i]])) {  //only if the card actually exists!

					echo '<div id=cars_'.$i.' class = "bingo-card" style="background-color:'.$CI->config->item('card_bg_color').'; color:'.$CI->config->item('card_font_color').';border: 1px solid '.$CI->config->item('card_font_color').';">';
					//echo '<p style="text-align: center; width=100%;"><h2 style="text-align: center;">Tarjeta '.$cardnumber[$i].'</h2></p>';
					echo '<div class="center"><table width=100% cellpadding=0" class="tirana"><tr class="card-view">';
					for ($column = 0; $column<5; $column++) {
						echo '<td width=10% align="center"><b><span class="big-letter">'.$bingoletters[$column].'</span></b></td>';
					}
					echo "</tr>";
					//table itself
					for ($row = 0; $row<5; $row++) {
						echo "<tr>";

						for ($column = 0; $column<5; $column++) { //column has to be inner loop due to HTML table
							if ($cardnumber[$i]->card[$column][$row]["n"]=="f") {
								echo "\n<td align=center class=\"checked\">";
								echo '<div class="number">';
								echo '<img src="'.base_url().'assets/template/img/logo_70x70.png" class="star">';
								echo '</div></td>';
							} else {
								echo "\n<td align=center class=".($showchecked && isset($draws) && in_array($cardnumber[$i]->card[$column][$row]["n"],$draws) ? "checked":"unchecked").">";
								echo '<div class="number">';
								echo "<p class='number-card'>".$cardnumber[$i]->card[$column][$row]["n"]."</p>";
								echo '</div></td>';
							}
								
						}
						echo "</tr>";
					} //table itself
					echo "</table></div>";
					if($showbtns)
					{
						echo '<div style="text-align: center; margin: 15px;">';
						/*if($i > 0) {
							echo '<button style="margin: 1%;" onclick="document.getElementById(\'cars_'.($i-1).'\').scrollIntoView();" id="bingo-btn" class="btn btn-primary">Anterior</button>';
						}*/
						echo '<button id="cleanBingo" style="margin: 1%;" onclick="cleanBingo(\'cars_'.$i.'\','. $cardnumber[$i]->table_index .')" id="clean-btn" class="btn btn-secondary">Limpiar</button>';
						echo '<button id="buttonBingo" style="margin: 1%;" onclick="doBingo('. $cardnumber[$i]->table_index .')" id="bingo-btn" class="btn btn-danger">Bingo</button>';
						/*if($i < sizeof($cardnumber)-1) {
							echo '<button style="margin: 1%;" onclick="document.getElementById(\'cars_'.($i+1).'\').scrollIntoView();" id="bingo-btn" class="btn btn-primary">Siguiente</button>';
						}*/
						echo '</div>';
					}
					echo '<p style="text-align: center; width=100%;"><h2 style="text-align: center;">Tarjeta '.$cardnumber[$i]->table_index.'</h2></p>';
					echo "</div>";
				//}
			}
		//}
		/*else{ 
			echo "<h1 style='text-align: center;'>No se han creado tablas</h1>";
		}*/
	}
	else{ 
		echo "<h1 style='text-align: center;'>No se le ha asignado una tabla</h1>";
	}
}

/*function display_card ($cardnumber,$fourperpage=false,$name_on=false,$rules="") {
	global $bingoletters, $setid;
	global $headerfontcolor, $headerbgcolor, $mainfontcolor, $mainbgcolor, $selectedfontcolor, $selectedbgcolor, $bordercolor;

	$set = load_set();  //normal cards
	if ($name_on) $names = load_name_file();
	if (!isset($names)) $name_on = false; //turn off 'card name' feature if names could not be loaded
	
	if (is_array($set)) {
		$maxloop = ($fourperpage)?4:1; //if we attempt to print four per page, need to loop 4 times
		for ($c=0; $c< $maxloop; $c++) {
			$cardshown = $cardnumber+$c;
			
			//if four per page, generate big four cell table
			if ($fourperpage) {
				switch ($c) {
					case 0: echo '<center><table width=100% border=0 cellpadding=5><tr valign=top><td width=50%>';
							break;
					case 1:
					case 3: echo '<td width=50%>';
							break;
					case 2: echo '<tr><td>';
							break;
				}
			}
			
			if (isset($set[$cardshown])) {  //only if the card actually exists!
				//start printing card
				echo '<center><table width='.(($fourperpage)?'265':'625').' border=1 cellpadding=0 BORDERCOLOR="'.$bordercolor.'"><tr height='.(($fourperpage)?'50':'110').'>';
				//header
				for ($column = 0; $column<5; $column++) {
					echo '<td  width=20% align="center" bgcolor="'.$headerbgcolor.'"><b><font size="'.(($fourperpage)?'+3':'+7').'" color="'.$headerfontcolor.'">'.$bingoletters[$column].'</font></b></td>';
				}
				echo "</tr>";
		
				//table itself
				for ($row = 0; $row<5; $row++) {
					echo "<tr height=".(($fourperpage)?"50":"110").">";
		
					for ($column = 0; $column<5; $column++) { //column has to be inner loop due to HTML table
						echo "\n<td align=center bgcolor=\"".($set[$cardshown][$column][$row]["checked"]?$selectedbgcolor:$mainbgcolor)."\">";
						if ($fourperpage) echo '<font size=+3>';
						else echo '<font size=+5 color="'.($set[$cardshown][$column][$row]["checked"]?$selectedfontcolor:$mainfontcolor).'">';
						if ($set[$cardshown][$column][$row]["number"]=="Free") {
							echo '<img src="images/star.gif"'.(($fourperpage)?'height=45':'height=105') .' align="middle" >';
						} else {
							echo $set[$cardshown][$column][$row]["number"].'</font></td>';
						}
					}
					echo "</tr>";
				} //table itself
				echo "</table><table width=".(($fourperpage)?"65%>":"45%>");
				printf("<tr><td align=left colspan=2><b><font size=-1>%s<b></td><td align=right colspan=3><font size=-2> Card Number: %s%'04s </font></td></tr>",(($name_on && $cardshown < count($names))?$names[$cardshown]:''),$setid,$cardshown+1);
				echo "</table></center>";
			}
			//if four per page, terminate big four cell table
			if ($fourperpage) {
				switch ($c) {
					case 0:
					case 2: echo '</td>';
							break;
					case 1: echo '<br><br></td></tr>';
							break;
					case 3: echo '</td></tr></table>';
							break;
				} //switch
			} //if four per page
		} // for loop
		
		if ($rules=="on") {
			echo '<p style="page-break-before: always">';
			readfile ("config/rules.html");
			
		}
	}
	else echo "set could not be opened";
}*/

/** random_number()
* This function generates a new random number.
* The number is unique: the function checks the draws.xx.dat file
* and loops until a new number randomly generated.
* The file draws.xx.dat is resorted and rewritten before the end of the function
* so it includes the new number.
* The function calls mark_cards() to have the new number set in all
* cards, and call check_bingo() to update the winners' list.
*/
/*function random_number($numberinplay) {
	global $bingoletters, $maxColumnNumber;

	$draws = load_draws();

	if ($draws!=null){
		$total=count($draws);
	} else $total=0;

	$samenumbertwice= true;
	while ($samenumbertwice) {

		$col = rand(0,4);
		$row = rand(1,$maxColumnNumber);
		$num = $maxColumnNumber*$col+$row;

		$samenumbertwice=false;

		if ($total >0 ) //no need to check if we have no numbers yet
		for ($i=0;$i < $total; $i++)
		if ($num==$draws[$i]) $samenumbertwice=true;
	}
	$draws[$total]=$num;
	save_draws($draws);

	mark_cards($col,$num);
	check_bingo($numberinplay);
	return $bingoletters[$col].$num;
}*/

/** submit_number()
* This function is used in manual mode to enter a number into the draw list.
* The file draws.xx.dat is resorted and rewritten before the end of the function
* so it includes the new number.
* The function calls mark_cards() to have the new number set in all
* cards, and call check_bingo() to update the winners' list.
*/
function submit_number($number/*,$numberinplay*/) {
	//global $bingoletters;
	$CI =& get_instance();
	$bingoletters = $CI->config->item('bingoletters');

	$draws = load_draws();

	if ($draws!=null){
		$total=count($draws);
	} else $total=0;

	$samenumbertwice=false;

	//extract the number out of the input string
	$convert = intval(substr($number,1));

	if ($total >0 ) //no need to check if we have no numbers yet
	for ($i=0;$i < $total; $i++)
	if ($convert==$draws[$i]) $samenumbertwice=true;

	if (!$samenumbertwice) {

		$draws[$total]=$convert;
		save_draws($draws);

		//mark_cards(array_search(strtoupper(substr($number,0,1)),$bingoletters),$convert);
		//check_bingo_simple($numberinplay);
		//check_bingo($numberinplay);
		return FALSE;
	} else {
		$index = array_search($convert, $draws);
		// echo "index ". $index;
		//unmark_cards(array_search(strtoupper(substr($number,0,1)),$bingoletters),$convert);
		array_splice($draws, $index, 1);
		save_draws($draws);
		return TRUE;
	}
}

function getLastDraws($clear = false)
{
	$draws = load_draws();

	$offset = $clear ? 1 : 0;

    $last_draws = array();

    $last_draws['ldl_1'] = ""; $last_draws['ldn_1'] = ""; $last_draws['ldl_2'] = ""; $last_draws['ldn_2'] = ""; $last_draws['ldl_3'] = ""; $last_draws['ldn_3'] = "";

    if($draws)
    {
		if(sizeof($draws) >= (4 - $offset))
		{
		  $last_draws['ldl_1'] = find_letter($draws[sizeof($draws)-(2 - $offset)]);
		  $last_draws['ldn_1'] = $draws[sizeof($draws)-(2 - $offset)];
		  $last_draws['ldl_2'] = find_letter($draws[sizeof($draws)-(3 - $offset)]);
		  $last_draws['ldn_2'] = $draws[sizeof($draws)-(3 - $offset)];
		  $last_draws['ldl_3'] = find_letter($draws[sizeof($draws)-(4 - $offset)]);
		  $last_draws['ldn_3'] = $draws[sizeof($draws)-(4 - $offset)];
		} else if(sizeof($draws) >= (3 - $offset))
		{
		  $last_draws['ldl_1'] = find_letter($draws[sizeof($draws)-(2 - $offset)]);
		  $last_draws['ldn_1'] = $draws[sizeof($draws)-(2 - $offset)];
		  $last_draws['ldl_2'] = find_letter($draws[sizeof($draws)-(3 - $offset)]);
		  $last_draws['ldn_2'] = $draws[sizeof($draws)-(3 - $offset)];
		}
		else if(sizeof($draws) == (2 - $offset))
		{
		  $last_draws['ldl_1'] = find_letter($draws[sizeof($draws)-(2 - $offset)]);
		  $last_draws['ldn_1'] = $draws[sizeof($draws)-(2 - $offset)];
		}
	}
	return $last_draws;
}


/** mark_cards()
* This function will "color" a given number on all cards
* that possess this number.  The card set will be resaved
* before the end of the function.
* This function should be called before check_bingo() which
* determine winning cards.
*/
/*function mark_cards($col, $num) { //col provided here to speed up search in the right column only

	$set = load_set();
	if ($set!=null){
		$numcards=count($set);
	} else $numcards=0;
	//$numcards=count($set);

	for ($n=0; $n<$numcards;$n++) {
		for ($r=0; $r<=4; $r++) { //go down the given column
			if ($set[$n][$col][$r]["n"]==$num)
			$set[$n][$col][$r]["c"]=true;
		}
	}
	save_set($set);
}
function unmark_cards($col, $num) { //col provided here to speed up search in the right column only

	$set = load_set();
	if ($set!=null){
		$numcards=count($set);
	} else $numcards=0;
	//$numcards=count($set);

	for ($n=0; $n<$numcards;$n++) {
		for ($r=0; $r<=4; $r++) { //go down the given column
			if ($set[$n][$col][$r]["n"]==$num)
			$set[$n][$col][$r]["c"]=false;
		}
	}
	save_set($set);
}*/


/*function check_bingo_simple($numberinplay){
	global $setid;
	$setid = get_set_id();
	$set=load_set();
	$numcards = count($set);

	$new_winners = load_new_winners(); //load the latest winner array
	if ($new_winners<>null) save_old_winners($new_winners); //saves the current list of winners as the old one

	$draws = load_draws();

	@unlink("data/new_winners.".$setid.".json"); //erases the new winners file

	for ($n=0; $n<min($numberinplay,$numcards);$n++) {  //checking each card
		$matches = 0;
		for ($c=0; $c<5; $c++) {
			for ($r=0; $r<5;	$r++) {
				for ($d=0; $d < count($draws); $d++) { 
					if ($set[$n][$c][$r]["number"] == $draws[$d]) {
						$matches++;
					}
				}

			}
		}
		if ($matches == 25) {
			$new_winners[$n]=true; //if it made it here, this pattern is winning
		}else{
			$new_winners[$n]=false;
		}

	}
	save_new_winners($new_winners);
}*/

/** check_bingo()
* This function goes through the complete set and attempts to find
* winning cards.
* Winning cards are cards that match the current winning pattern.
* All cards are saved to the file winners.xx.dat and the file
* is re-written each time.
* The array tracks the success of all winning patterns for each card.
* Each card can be a winner in many patterns at the same time
*/
function check_bingo() {
	$CI =& get_instance();
	
	$setid = get_set_id();
	$cards = $CI->bingo_model->getUsersTables(get_set_id());//config->item('winningpatternarray');
	$winners = array();
	$winningpatternarray = $CI->bingo_model->getRoomWinningPattern(get_set_id());
	$draws = load_draws();
	$winningset = load_winning_patterns();
	$patternkeywords = $CI->config->item('patternkeywords');
    $today = date("Y-m-d H:i:s",time()-7200);
	foreach($cards as $card)
	{
    if($card->last_activity!=NULL)
	//	if($card->last_activity!=NULL && date( "Y-m-d H:i:s", strtotime($card->last_activity)) > $today)
    //if($card->last_activity!=NULL &&/*$card->logged &&*/ date( "Y-m-d H:i:s", strtotime($card->last_activity)) > $today)
		{
			$wins = check_table_lite($card->card, $card->user_id, $card->table_index, $patternkeywords, $winningpatternarray, $setid, $draws, $winningset);
			//$wins = check_table($card->user_id, $card->table_index);
			if(sizeof($wins) > 0 )
			{
				$data  = array(
					'user_id' => $card->user_id,
					'last_login' => $card->last_login,
					'last_activity' => $card->last_activity,
					'name' => $card->name,
					'room' => $setid,
					'card' => $card->table_index,
					'patterns' => json_encode($wins)
				);
				array_push($winners,$data);
			}
		}
	}
	return $winners;
}

function check_bingo_lite() {
	$CI =& get_instance();
	
	$setid = get_set_id();
	$cards = $CI->bingo_model->getUsersTables($setid);//config->item('winningpatternarray');
	$winners = array();
	$winningpatternarray = $CI->bingo_model->getRoomWinningPattern($setid);
	$draws = load_draws();
	$winningset = load_winning_patterns();
	$patternkeywords = $CI->config->item('patternkeywords');
    $today = date("Y-m-d H:i:s",time()-1800);

	foreach($cards as $card)
	{
		if($card->last_activity!=NULL && date( "Y-m-d H:i:s", strtotime($card->last_activity)) > $today)
		{
			$wins = check_table_lite($card->card, $card->user_id, $card->table_index, $patternkeywords, $winningpatternarray, $setid, $draws, $winningset);
			//$wins = check_table($card->user_id, $card->table_index);
			if(sizeof($wins) > 0 )
			{
				$data  = array(
					'user_id' => $card->user_id,
					'last_login' => $card->last_login,
					'last_activity' => $card->last_activity,
					'name' => $card->name,
					'room' => $setid,
					'card' => $card->table_index,
					'patterns' => json_encode($wins)
				);
				array_push($winners,$data);
				break;
			}
		}
	}
	return $winners;
}

function check_bingo_user($userid) {
	$CI =& get_instance();
	
	$setid = get_set_id();
	$cards = $CI->bingo_model->getUserTables($setid,$userid);//config->item('winningpatternarray');
	$winners = array();
	$winningpatternarray = $CI->bingo_model->getRoomWinningPattern($setid);
	$draws = load_draws();
	$winningset = load_winning_patterns();
	$patternkeywords = $CI->config->item('patternkeywords');
	foreach($cards as $card)
	{
		$wins = check_table_lite($card->card, $card->user_id, $card->table_index, $patternkeywords, $winningpatternarray, $setid, $draws, $winningset);
		//$wins = check_table($card->user_id, $card->table_index);
		if(sizeof($wins) > 0 )
		{
			$data  = array(
				'user_id' => $card->user_id,
				'last_login' => $card->last_login,
				'last_activity' => $card->last_activity,
				'name' => $card->name,
				'room' => $setid,
				'card' => $card->table_index,
				'patterns' => json_encode($wins)
			);
			array_push($winners,$data);
			break;
		}
	}
	return $winners;
}

/** check_bingo()
* This function goes through the complete set and attempts to find
* winning cards.
* Winning cards are cards that match the current winning pattern.
* All cards are saved to the file winners.xx.dat and the file
* is re-written each time.
* The array tracks the success of all winning patterns for each card.
* Each card can be a winner in many patterns at the same time
*/
/*function check_bingo ($numberinplay) {
	//global $winningpatternarray;
	//global $setid;
	$CI =& get_instance();
	$winningpatternarray = $CI->bingo_model->getRoomWinningPatterConfig(get_set_id());//config->item('winningpatternarray');

	$setid = get_set_id();
	$set=load_set();
	if ($set!=null){
		$numcards=count($set);
	} else $numcards=0;
	//$numcards = count($set);
	$winningset = load_winning_patterns();

	$new_winners = load_new_winners(); //load the latest winner array

	if ($new_winners<>null) save_old_winners($new_winners); //saves the current list of winners as the old one

	//@unlink("data/new_winners.".$setid.".json"); //erases the new winners file
	$result = $CI->bingo_model->clearNewWinners($setid);

	//if a numberinplay is given that is smaller than the the number
	// of cards in the set, the rest of the cards are not verified.
	// because of multiple winning patterns, each card must be verified whether or not it was a previous winner
	for ($n=0; $n<min($numberinplay,$numcards);$n++) {  //checking each card

		for ($p=0; $p<count($winningpatternarray); $p++) { //cycle through all winning patterns
					//** optimize conditions
		
			if ($winningpatternarray[$p]!= "on") {
				$new_winners[$n][$p]=false;
				continue;  // go to the next pattern if user doesn't want this pattern checked
			}
			if (isset($new_winners[$n][$p]) && $new_winners[$n][$p]) continue; //this card already won against this pattern, no test required


			//normal bingo
			if ($p==0) { //normal bingo

				for ($c=0; $c<5; $c++) {
					$rowbingo=true; //assume there is bingo in rows and prove wrong
					$colbingo=true; //assume there is bingo in columns and prove wrong
					for ($r=0; $r<5;$r++) {
						if (!$set[$n][$c][$r]["c"]) $colbingo=false; //as soon as one is not checked
						if (!$set[$n][$r][$c]["c"]) $rowbingo=false; //as soon as one is not checked
					} //end of that column/row, if we still have either bingo, we have a winner
					if ($rowbingo||$colbingo){
						$new_winners[$n][0]=true;  //current winning pattern is good, normal bingo
						break; //no need to keep checking for this pattern
					} else $new_winners[$n][0]=false;
				}
				
				//if it is still not a winner, check the diagonals
				if (!isset($new_winners[$n][0]) or !$new_winners[$n][0]) { 
					$bingod1=true; //assume there is bingo in diagonals, prove wrong
					$bingod2=true;
					for ($d=0; $d<5 ; $d++) {
						if (!$set[$n][$d][$d]["c"]) $bingod1=false; //as soon as one item from diagonal is not checked
						if (!$set[$n][$d][4-$d]["c"]) $bingod2=false;
					}
					if ($bingod1||$bingod2) {
						$new_winners[$n][0]=true;
					} else $new_winners[$n][0]=false;
				}
				
			} //if $p==0
			
			//for all patterns but normal bingo
			//check all the "winning squares" against the current card
			//by loading the appropriate card of the "winningpatterns" set
			//stop at the first unmatching square
			else {
				for ($c=0; $c<5; $c++) {
					for ($r=0; $r<5;	$r++) {
						if ($winningset[$p-1][$c][$r]["c"] && !$set[$n][$c][$r]["c"]) {
							$new_winners[$n][$p]=false; //as soon as one square is not checked, not a winner
							continue 3; //break from loop 1, loop 2, continue testing at the next pattern
						}
					}
				}
				$new_winners[$n][$p]=true; //if it made it here, this pattern is winning
			} //if $p!= 0

		} // end for

	} //for each card


	//refresh the new winner list
	save_new_winners($new_winners);

}*/

function check_table ($id, $card) {
	//global $winningpatternarray;
	//global $setid;
	//global $patternkeywords;
	$CI =& get_instance();
	//$winningpatternarray = $CI->bingo_model->getRoomWinningPatterConfig(get_set_id());//config->item('winningpatternarray');
	$winningpatternarray = $CI->bingo_model->getRoomWinningPattern(get_set_id());
	$patternkeywords = $CI->config->item('patternkeywords');
	$setid = get_set_id();

	//$set=load_set();
	/*if ($set!=null){
		$numcards=count($set);
	} else $numcards=0;*/
	$draws = load_draws();

	if ($draws!=null){
		$total=count($draws);
	} else $total=0;

	//$numcards = count($set);
	$winningset = load_winning_patterns();
	//print_r("****************");
	//print_r($winningpatternarray);

	$n = $card;
	$set = $CI->bingo_model->getCard($setid,$card);

	$win_patt = array();
	//echo $n." ".$numcards."<br>";
	//print_r($set);
	//print_r($set[$n]);
	//if a numberinplay is given that is smaller than the the number
	// of cards in the set, the rest of the cards are not verified.
	// because of multiple winning patterns, each card must be verified whether or not it was a previous winner
	//echo '<div><h1>'.$id." *** ".$card.'</h1></div>';

	//for ($n=0; $n<min($numberinplay,$numcards);$n++) {  //checking each card
	if($winningpatternarray != null && $total >0){
	//isset($draws) && in_array($cardnumber[$i]->card[$column][$row]["n"],$draws)
		//for ($p=0; $p<count($winningpatternarray); $p++) { //cycle through all winning patterns
					//** optimize conditions
		//isset($draws) && in_array($set[$cardnumber[$i]][$column][$row]["number"],$draws)
			//if ($winningpatternarray [$p]!= "on") {
				//$new_winners[$n][$p]=false;
			//	continue;  // go to the next pattern if user doesn't want this pattern checked
			//}
			//if (isset($new_winners[$n][$p]) && $new_winners[$n][$p]) continue; //this card already won against this pattern, no test required
			//echo $patternkeywords[$p]."<br>";
			//echo $winningpatternarray[$p]->name."<br>";

			//normal bingo
			if ($winningpatternarray->id == 1)//if ($winningpatternarray[$p]->id == 1)//if ($p==0) //normal bingo
			{
				for ($c=0; $c<5; $c++) {
					$rowbingo=true; //assume there is bingo in rows and prove wrong
					$colbingo=true; //assume there is bingo in columns and prove wrong
					for ($r=0; $r<5;$r++) {
						//echo "col:".$c." ".$set[$c][$r]["checked"]."\n";
						//echo "row:".$r." ".$set[$r][$r]["checked"]."\n";

						if ($set[$c][$r]["n"] != "f" && !in_array($set[$c][$r]["n"],$draws)) $colbingo=false; //as soon as one is not checked
						if ($set[$c][$r]["n"] != "f" && !in_array($set[$r][$c]["n"],$draws)) $rowbingo=false; //as soon as one is not checked
					} //end of that column/row, if we still have either bingo, we have a winner
					//echo "  *****  ";
					if ($rowbingo||$colbingo){
						$win_patt[$n][0]= array("name" => $winningpatternarray->name);//$patternkeywords[$p]); //current winning pattern is good, normal bingo
						break; //no need to keep checking for this pattern
					} //else $win_patt[$n][0]=false;
				}
				//echo "-------------<br>";
				//if it is still not a winner, check the diagonals
				if (!isset($win_patt[$n][0]) or !$win_patt[$n][0]) { 
					$bingod1=true; //assume there is bingo in diagonals, prove wrong
					$bingod2=true;
					for ($d=0; $d<5 ; $d++) {
						//echo "col:".$d." ".$set[$d][$d]["c"]."\n";
						//echo "row:".$d." ".$set[$d][4-$d]["c"]." =*= ";

						if ($set[$d][$d]["n"] != "f" && !in_array($set[$d][$d]["n"],$draws)) $bingod1=false; //as soon as one item from diagonal is not checked
						if ($set[$d][4-$d]["n"] != "f" && !in_array($set[$d][4-$d]["n"],$draws)) $bingod2=false;
					}
					if ($bingod1||$bingod2) {
						$win_patt[$n][0]= array("name" => $winningpatternarray->name);//$patternkeywords[$p]);
					} //else $win_patt[$n][0]=array("win" => false,"name" => $patternkeywords[$p]);
				}
				//echo "-------------<br>";

				
			} //if $p==0
			
			//for all patterns but normal bingo
			//check all the "winning squares" against the current card
			//by loading the appropriate card of the "winningpatterns" set
			//stop at the first unmatching square
			else {
				//echo $winningpatternarray->pattern."<br>";
				$pattern = json_decode($winningpatternarray->pattern,true);
				//echo $winningpatternarray->pattern."<br>";
				$win = true;
				for ($c=0; $c<5; $c++) {
					for ($r=0; $r<5;	$r++) {
						//echo $c.'  '.$r.'  ('.$pattern[$c][$r]["c"].')  '.($set[$c][$r]["n"]).'<br>';//." && ".(!$set[$c][$r]["c"])." -> ".$set[$c][$r]["number"]." =*= ";
						if ($pattern[$c][$r]["c"] && ($set[$c][$r]["n"] != "f" && !in_array($set[$c][$r]["n"],$draws))) {
							//$win_patt[$n][$p]=array("win" => false,"name" => $patternkeywords[$p]); //as soon as one square is not checked, not a winner
							$win = false;
							continue 2;//3; //break from loop 1, loop 2, continue testing at the next pattern
						}
					}
				}
				if($win) $win_patt[$n][0]/*[$p]*/=array("name" => $winningpatternarray->name);//$patternkeywords[$p]); //if it made it here, this pattern is winning
				//echo "-------------<br>";
			} //if $p!= 0

		//} // end for

	//} //for each card
	}

	//refresh the new winner list
	//echo "Listo!";
	//print_r($win_patt);
	return $win_patt;

}

function check_table_lite($set, $id, $card, $patternkeywords, $winningpatternarray, $setid, $draws, $winningset) {
	//$CI =& get_instance();
	//$patternkeywords = $CI->config->item('patternkeywords');
	
	if ($draws!=null){
		$total=count($draws);
	} else $total=0;

	$n = $card;
	$set = json_decode($set,true);
	//$set2 = $CI->bingo_model->getCard($setid,$card);

	$win_patt = array();
	
	if($winningpatternarray != null && $total >0){

		//normal bingo
		if ($winningpatternarray->id == 1)//if ($winningpatternarray[$p]->id == 1)//if ($p==0) //normal bingo
		{
			for ($c=0; $c<5; $c++) {
				$rowbingo=true; //assume there is bingo in rows and prove wrong
				$colbingo=true; //assume there is bingo in columns and prove wrong
				for ($r=0; $r<5;$r++) {
					//echo "col:".$c." ".$set[$c][$r]["checked"]."\n";
					//echo "row:".$r." ".$set[$r][$r]["checked"]."\n";

					if ($set[$c][$r]["n"] != "f" && !in_array($set[$c][$r]["n"],$draws)) $colbingo=false; //as soon as one is not checked
					if ($set[$c][$r]["n"] != "f" && !in_array($set[$r][$c]["n"],$draws)) $rowbingo=false; //as soon as one is not checked
				} //end of that column/row, if we still have either bingo, we have a winner
				//echo "  *****  ";
				if ($rowbingo||$colbingo){
					$win_patt[$n][0]= array("name" => $winningpatternarray->name);//$patternkeywords[$p]); //current winning pattern is good, normal bingo
					break; //no need to keep checking for this pattern
				} //else $win_patt[$n][0]=false;
			}
			//echo "-------------<br>";
			//if it is still not a winner, check the diagonals
			if (!isset($win_patt[$n][0]) or !$win_patt[$n][0]) { 
				$bingod1=true; //assume there is bingo in diagonals, prove wrong
				$bingod2=true;
				for ($d=0; $d<5 ; $d++) {
					//echo "col:".$d." ".$set[$d][$d]["c"]."\n";
					//echo "row:".$d." ".$set[$d][4-$d]["c"]." =*= ";

					if ($set[$d][$d]["n"] != "f" && !in_array($set[$d][$d]["n"],$draws)) $bingod1=false; //as soon as one item from diagonal is not checked
					if ($set[$d][4-$d]["n"] != "f" && !in_array($set[$d][4-$d]["n"],$draws)) $bingod2=false;
				}
				if ($bingod1||$bingod2) {
					$win_patt[$n][0]= array("name" => $winningpatternarray->name);//$patternkeywords[$p]);
				} //else $win_patt[$n][0]=array("win" => false,"name" => $patternkeywords[$p]);
			}
			//echo "-------------<br>";
		} 
		
		//for all patterns but normal bingo
		//check all the "winning squares" against the current card
		//by loading the appropriate card of the "winningpatterns" set
		//stop at the first unmatching square
		else {
			//echo $winningpatternarray->pattern."<br>";
			$pattern = json_decode($winningpatternarray->pattern,true);
			//echo $winningpatternarray->pattern."<br>";
			$win = true;
			for ($c=0; $c<5; $c++) {
				for ($r=0; $r<5;	$r++) {
					//echo $c.' '.$r.' ('.$pattern[$c][$r]["c"].')  '.($set[$c][$r]["n"]).'<br>';//." && ".(!$set[$c][$r]["c"])." -> ".$set[$c][$r]["number"]." =*= ";
					if ($pattern[$c][$r]["c"] && ($set[$c][$r]["n"] != "f" && !in_array($set[$c][$r]["n"],$draws))) {
						//$win_patt[$n][$p]=array("win" => false,"name" => $patternkeywords[$p]); //as soon as one square is not checked, not a winner
						$win = false;
						continue 2;//3; //break from loop 1, loop 2, continue testing at the next pattern
					}
				}
			}
			if($win) $win_patt[$n][0]/*[$p]*/=array("name" => $winningpatternarray->name);//$patternkeywords[$p]); //if it made it here, this pattern is winning
			//echo "-------------<br>";
		} //if $p!= 0

	//} //for each card
	}
	//refresh the new winner list
	return $win_patt;
}

/** restart()
* This function allows one to restart the game mode.
* Current list of winners will be erased, as well as all drawn numbers.
* The card set will remain untouched, but all "colours" will be reset, except
* for the free square. Very useful if you are using the same set of bingo cards for
* several games.
*/
function restart() { //erases winners, draws, and clears all cards but keeps numbers
	//global $setid;
	$CI =& get_instance();
	$setid = get_set_id();

	//$CI->bingo_model->clearOldWinners($setid);
	//$CI->bingo_model->clearNewWinners($setid);
	$CI->bingo_model->clearDraws($setid);
	$CI->bingo_model->clearMessages($setid);
	//@unlink ("data/old_winners.".$setid.".json");
	//@unlink ("data/new_winners.".$setid.".json");
	//@unlink("data/draws.".$setid.".json");
	// @unlink("data/tables-users".$setid.".json");
	/*if (set_exists()) {
		$set = load_set();
		if ($set!=null){
			$numcards=count($set);
		} else $numcards=0;
		//$numcards=count($set);

		for ($n=0; $n<$numcards;$n++) {
			for ($c=0; $c<5; $c++) {
				for ($r=0; $r<5; $r++) { //go down the column
					$set[$n][$c][$r]["c"]=($set[$n][$c][$r]["n"]=="f"); //don't forget the free

				}
			}
		}
		save_set($set);
	}*/

}

/** draws_table()
* This function generates an HTML table of all numbers drawn.
* It is used in game mode on the right side. The file containing the
* numbers drawn (draws.xx.dat) is already sorted.
*/
/*function draws_table() { //table of all numbers drawn
	$draws = load_draws();

	echo '<table width="100%" border=1 cols=5><tr>';
	if ($draws!=null) {
		$number = count($draws);

		for ($i =0; $i<$number; $i++) {
                  echo '<td align=center width="20%" >';
					echo '<div class="ball-img drawn"><p><a href= "view.php?sendnumber='.find_letter($i).$i.'"><b>'.find_letter($i).$i.'</b></a></p></div>';
                  echo '</td>';
			if (($i+1)%5==0) echo "</tr><tr>";
		}

	} else echo "<td>No numbers drawn yet</td>";
	echo '</tr></table>';
}*/

/** winners_table()
* This function generates an HTML table of all winning cards.
* It is used in game mode at the bottom.
* Each winning card number is displayed with its view link.
*/
/*function winners_table_simple() {
	global $patternkeywords;
	global $winningpatternarray;

	$winners = load_new_winners();
	$old_winners = load_old_winners(); //to be indicated in a different color

	// echo '<table width="100%" border=1><tr>';



            echo '<table cols=12 style="margin-left: auto; margin-right: auto;"><tr>';

		$wincounter = 0;  // counts the number of winning cards per pattern for line return
		// and enables the No winners yet! msg for each category
		if ($winners!=null) {
			if ($old_winners != null && $winners != $old_winners) {
				// echo '<script type="text/javascript">document.body.onload = function(){alert("Nuevo Ganador");};</script>';
				 echo '	<div class="alert alert-info alert-dismissible" role="alert" style="position: fixed; top: 50%; z-index: 100; box-shadow: 4px 4px 27px rgba(128, 128, 128, 0.43); width: 40%; left: 30%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Nuevo(s) Ganador(es)</div>';

			}
			for ($i =0; $i<count($winners); $i++) {  //cycle through all cards
				// echo "isset :". isset($winners[$i]). " & winners: ".$winners[$i];
				if (isset($winners[$i]) && $winners[$i]) {
					$wincounter++;

					//if this card pattern combinations was already true, black, else red.					
					$color = ($old_winners[$i])? "#000000":"#ff5555";

					//Resize card numbers to fit background image
					if ($i<100) $font_size="3";
					else if ($i<1000) $font_size="2";
					else $font_size="1";

					echo '<td align=center width="23" height="25" background="images/ubb2.gif"><a href="view.php?cardnumber='.get_user_id($i).'" target=_blank><font size="'.$font_size.'" color="'.$color.'">'.($i+1).'</font></a></td>';
					if (($wincounter)%12==0) echo "</tr><tr>";
				}
			}
		}
		if ($wincounter==0) echo "<td>No hay ganadores por ahora</td>";



		echo '</tr></table>';
		// echo '</td></tr>';
	// echo '</tr></table>';
}*/

/*function winners_table() {
	global $patternkeywords;
	global $winningpatternarray;

	$winners = load_new_winners();

	$old_winners = load_old_winners(); //to be indicated in a different color

	if ($old_winners != null && $winners != $old_winners) {
		// echo '<script type="text/javascript">document.body.onload = function(){alert("Nuevo Ganador");};</script>';
		 echo '	<div class="alert alert-info alert-dismissible" role="alert" style="position: fixed; top: 50%; z-index: 100; box-shadow: 4px 4px 27px rgba(128, 128, 128, 0.43); width: 40%; left: 30%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Nuevo(s) Ganador(es)</div>';
	}
	echo '<table width="100%" border=1><tr>';

	for ($patterncountdown=count($winningpatternarray)-1; $patterncountdown >= 0; $patterncountdown--) { //for all winning patterns

		if ($winningpatternarray[$patterncountdown]!="on") continue; // if pattern not selected, continue to the next one

		//write the title of the winning pattern
		echo '<td nowrap valign="top" align=center><font size="1"><b>'.$patternkeywords[$patterncountdown].'</b></font><br>';

            //Place graphic to match winning pattern
            switch ($patternkeywords[$patterncountdown]) {
					case 'Clasico':
                                    echo '<img src="images/nc.gif">';
                                    break;
					case 'Cuatro Esquinas':
                                    echo '<img src="images/fc.gif">';
                                    break;
					case 'Forma Cruz':
                                    echo '<img src="images/cs.gif">';
                                    break;
					case 'Forma T':
                                    echo '<img src="images/ts.gif">';
                                    break;
					case 'Forma X':
                                    echo '<img src="images/xs.gif">';
                                    break;
					case 'Forma +':
                                    echo '<img src="images/ps.gif">';
                                    break;
					case 'Forma Z':
                                    echo '<img src="images/zs.gif">';
                                    break;
					case 'Forma N':
                                    echo '<img src="images/ns.gif">';
                                    break;
					case 'Caja':
                                    echo '<img src="images/bs.gif">';
                                    break;
					case 'Cuadrado':
                                    echo '<img src="images/ss.gif">';
                                    break;
					case 'Completo':
                                    echo '<img src="images/ffc.gif">';
                                    break;

            } //end switch

            echo '</td><td><table cols=12><tr>';

		$wincounter = 0;  // counts the number of winning cards per pattern for line return
		// and enables the No winners yet! msg for each category
		if ($winners!=null) {

			for ($i =0; $i<count($winners); $i++) {  //cycle through all cards
				if (isset($winners[$i][$patterncountdown]) && $winners[$i][$patterncountdown]) {
					$wincounter++;

					//if this card pattern combinations was already true, black, else red.					
					$color = ($old_winners[$i][$patterncountdown])? "#000000":"#ff5555";

					//Resize card numbers to fit background image
					if ($i<100) $font_size="3";
					else if ($i<1000) $font_size="2";
					else $font_size="1";

					echo '<td align=center width="23" height="25" background="images/ubb2.gif"><a href="view.php?cardnumber='.get_user_id($i).'" target=_blank><font size="'.$font_size.'" color="'.$color.'">'.($i).'</font></a></td>';
					if (($wincounter)%12==0) echo "</tr><tr>";
				}
			}
		}
		if ($wincounter==0) echo "<td>No hay ganadores por ahora</td>";
		echo '</tr></table></td></tr>';
	}
	echo '</tr></table>';
}*/



/** find_letter()
* This function returns the letter associated with the
* bingo number passed as a parameter (1 to 75): B, I, N, G or O
*/
function find_letter($num) {
	$CI =& get_instance();
	$bingoletters = $CI->config->item('bingoletters');
	$maxColumnNumber = $CI->config->item('maxColumnNumber');
	//global $bingoletters, $maxColumnNumber;
	return $bingoletters[intval(($num-1) /$maxColumnNumber)];
}


/** load_set()
* This function attemps to load the card set
* from the file set.xx.dat where xx is the setid.
*/
function load_set() {

	$CI =& get_instance();
	$setid = get_set_id();

	if (set_exists()) {
		//$filearray=file("data/draws.".$setid.".dat");
		//$draws = unserialize($filearray[0]);
		$result = $CI->bingo_model->getSet($setid);	
		$set =  json_decode($result->data, true);
		return $set;
	} else {
		echo "<h1 style='text-align: center;'>No se han creado tablas del Bingo " . $setid . "</h1>";
		return null;
	}
}

/** save_set()
* This function attemps to save the card set
* to the file set.xx.dat where xx is the setid.
* Error msgs were removed because the demo
* on sourceforge.net cannot save files.
* The file is written by serializing each card onto a different line
* Serializing the whole set table also works, but the file
* is a lot more difficult to observe with a text editor.
*/
function save_set(&$set) {
	//global $setid;
	$CI =& get_instance();
	$setid = get_set_id();
	$numcards = count($set);

	$CI->bingo_model->saveSet($setid,$set);
}

/** load_draws()
* This function attemps to load the series of numbers drawn
* from the file draws.xx.dat where xx is the setid.
*/
function load_draws() {
	//global $setid;
	$CI =& get_instance();
	$setid = get_set_id();

	$result = $CI->bingo_model->getDraw($setid);

	//print_r($result);
	if (isset($result) > 0){
		//$filearray=file("data/draws.".$setid.".dat");
		//$draws = unserialize($filearray[0]);

		$draws =  json_decode($result->data, true);
		return $draws;
	} else return null;

}

/** save_draws()
* This function attemps to save the series of numbers drawn
* to the file draws.xx.dat where xx is the setid.
* Error msgs were removed because the demo
* on sourceforge.net cannot save files.
* The file is written by serializing the whole
* draws table, once sorted.
*/
function save_draws(&$draws) {

	$CI =& get_instance();
	$setid = get_set_id();
	//$numcards = count($draws);

	$CI->bingo_model->saveDraws($setid,$draws);
}

/** load_old_winners()
* This function attemps to load the series of winning card numbers
* from the file old_winners.xx.dat where xx is the setid.
*/
function load_old_winners() {
	$CI =& get_instance();
	$setid = get_set_id();

	$result = $CI->bingo_model->getOldWinners($setid);

	//print_r($result);
	if (isset($result) > 0){
		$winners =  json_decode($result->new_winners, true);
		return $winners;
	} else return null;
}

/** save_old_winners()
* This function attemps to save the series of winning cards
* to the file old_winners.xx.dat where xx is the setid.
* Error msgs were removed because the demo
* on sourceforge.net cannot save files.
* The file is written by serializing the whole
* winners table.
*/
function save_old_winners(&$winners) {
	$CI =& get_instance();
	$setid = get_set_id();

	$CI->bingo_model->saveOldWinners($setid,$winners);


}

/** load_new_winners()
* This function attemps to load the series of new winning card numbers
* from the file new_winners.xx.dat where xx is the setid.
*/
function load_new_winners() {
	$CI =& get_instance();
	$setid = get_set_id();

	$result = $CI->bingo_model->getNewWinners($setid);

	//print_r($result);
	if (isset($result) > 0){
		$new_winners =  json_decode($result->new_winners, true);
		return $new_winners;
	} else return null;
}


/** save_new_winners()
* This function attemps to save the series of new winning cards
* to the file new_winners.xx.dat where xx is the setid.
* Error msgs were removed because the demo
* on sourceforge.net cannot save files.
* The file is written by serializing the table containing the new
* winners table.
*/
function save_new_winners(&$new_winners) {
	$CI =& get_instance();
	$setid = get_set_id();

	$CI->bingo_model->saveNewWinners($setid,$new_winners);
}

/** load_name_file()
* This function attempts to load the word list (wordlist.txt)
*/
/*function load_name_file() {

	global $setid;
	if (file_exists("config/names.txt")) {
		$filearray = file("config/names.txt");

		for ($i=0; $i<count($filearray); $i++)
		if ($i==(count($filearray)-1)) {
			$names[$i]=$filearray[$i];
		}
		else $names[$i] = substr($filearray[$i],0,strlen($filearray[$i])-2);
		return $names;
	} else {
		echo "Name file could not be loaded";
		return null;
	}
}*/

/** load_winning_patterns()
* This function attemps to load the winning patterns set (winningpatterns.dat) which contains
* all winning patterns, with the exception of the normal winning pattern (pattern 0)
* (any row, any column, any diagonal).  The set is loaded when the user wishes to preview
* customize a given winning pattern.
*/
function load_winning_patterns() {
	$CI =& get_instance();
	$setid = get_set_id();

	//$result = $CI->bingo_model->getWinningPatterns($setid);
	$result = $CI->bingo_model->getAllWinningPatterns();

	//print_r($result);
	if (isset($result) > 0){
		//$set =  json_decode($result->winning_patterns, true);
		//return $set;
		return $result;
	} else return null;

	/*if (file_exists("data/winningpatterns.json")) {

		$myfile = file_get_contents("data/winningpatterns.json", "r");
		$set =  json_decode($myfile, true);

		//$filearray = file("data/winningpatterns.dat");
		//for ($i=0; $i< $filearray[0]; $i++) { //first row is number of rows
		//	$set[$i] = unserialize($filearray[$i+1]);
		//}
		
		return $set;
	} else {
		echo "set could not be loaded";
		return null;
	}*/
}


/** update_winning_patterns()
* This function updates the given $cardnumber within the "winningp atterns" set based
* on the information graphically entered from the web page.  The squares selected on the
* interactive web page are converted to a hidden string.  The string is passed to this
* function as a parameter, along with the pattern number chosen.  The set is updated
* to "check" all squares that were selected by the user.
* The functions saves the previewpatterns set prior to exiting.
*/
/*function update_winning_patterns($hiddenstring, $cardnumber) {
	global $bingoletters;

	@$winningset=load_winning_patterns();

	if (is_array($winningset)) {

		for ($row = 0; $row<5; $row++) {
			for ($column = 0; $column<5; $column++) {

				//eg. if B0 is in the string, then we must ensure that square becomes checked in
				//the first card of the previewpattern set
				echo $bingoletters[$column].$row."  ".$hiddenstring."\n";
				if (preg_match(("/".$bingoletters[$column].$row."/"),$hiddenstring)) {
					$winningset[$cardnumber][$column][$row]["checked"]=true;
				} else $winningset[$cardnumber][$column][$row]["checked"]=false;
			}
		}
		save_winning_patterns($winningset); 
	}
}
*/
/** save_winning_patterns()
* This function attemps to save the pattern preview set
* to the winningpatterns.dat file, a carefully crafted file that stores all winning patterns.
* with the exception of the normal winning pattern (pattern 0) which is not easily represented
* (any row, any column, any diagonal).  This function is called once the user has selected
* the squares of the winning pattern from the GUI.  The string of winning squares is converted
* from the interactive form with the function call update_winning_patterns() and then saved here
*/
/*function save_winning_patterns(&$set) {
	if (file_exists("data/winningpatterns.dat")) {
		//we must first open the file in reading to determine the number of rows

		$filearray = file("data/winningpatterns.dat");
		$numcards= trim($filearray[0]);

		//then we write the appropriate number of cards in the previewpatterns set
		if (@$fp=fopen("data/winningpatterns.dat","w")) {
			fwrite($fp,$numcards."\n");
			for ($i =0; $i<$numcards; $i++) {
				fwrite($fp, serialize($set[$i])."\n");  //one card per row
			}
			fclose($fp);
		}
	}
	if (file_exists("data/winningpatterns.json")) {
		//we must first open the file in reading to determine the number of rows

		$filearray = file("data/winningpatterns.json");
		$numcards= trim($filearray[0]);

		//then we write the appropriate number of cards in the previewpatterns set
		if (@$fp=fopen("data/winningpatterns.json","w")) {
			fwrite($fp, json_encode($set));
			fclose($fp);
		}
	}
}*/


/** display_interactive_card()
* function similar to display_card() that is called when the user wishes
* to preview or customize one of the "winning patterns".  The technique
* used to customize the winning pattern involves Javascript and CSS components
* that are not supported by Netscape 4 (Opera 6 and IE 6 have been tested and
* are fully compatible.  The card is an HTML table in which the cells react to mouse
* clicks.  When the card is first loaded, the information is retreived from the
* winningpatterns.dat set and is represented visually on the screen.  A string
* is generated from the "checked cell" and stored (hidden) in the form.  When the user
* clicks a cell from the table, its color is reversed, and the hidden string is truncated
* or expanded with the name of the chosen cell.  When the form is submitted, the
* updated winningpatterns.dat file is saved.
* This function returns a string composed of the names of the selected cell of the given
* card in the winningpatterns.dat file: eg. B0;I0;N0;N1;N2;N3;N4;G0;O0 would be returned
* if the T-shaped winning pattern was selected.
*/
/*function display_interactive_card($cardnumber) {
	global $bingoletters;
	
	@$winningset=load_winning_patterns(); //display a pattern preview
	$hiddenstring="";  //sets the initial value to avoid error msg below.
	if (is_array($winningset)) {
		
		echo '<center><table width="75%" border="0" cellpadding="20" bgcolor="silver"><tr>';
		//header
		for ($column = 0; $column<5; $column++) { 
				echo '<td  width="20%" align="center" bgcolor="#dd00dd"><b><font size="+7">'.$bingoletters[$column].'</font></b></td>';
		}
		echo "</tr>";

		//table
		for ($row = 0; $row<5; $row++) {
			echo "<tr>\n";
			
			for ($column = 0; $column<5; $column++) { //column has to be inner loop due to HTML table
				echo "\n<td align=\"center\" style=\"background:".($winningset[$cardnumber][$column][$row]["checked"]?"#eeee00;":"silver;")."\" onClick = \"this.style.background=clickcell(this.style.background,'".$bingoletters[$column].$row."')\">";
				echo '<font size="+5">';
				echo $winningset[$cardnumber][$column][$row]["number"].'</font></td>';
				if ($winningset[$cardnumber][$column][$row]["checked"]) $hiddenstring.=($bingoletters[$column].$row.';');
			}
			echo "</tr>";
		}
		echo "</table></center>";
		
	}
	else echo "set could not be opened";
	return $hiddenstring;
}
*/
/** get_table_id()
* This function returns an array composed of the ids of the asigned cards of the user
*/
	function get_table_id($user_id){
		$CI =& get_instance();
		$setid = get_set_id();
		$result = $CI->bingo_model->getUserTables($setid, $user_id);
		
		$rows = array();
		foreach ($result as $r) {
			$r->card = json_decode($r->card, true);
			$rows[] = $r;
		}
	    if (sizeof($rows)>0) {
			return $rows;
		}else
		{
			//print_r("---- [Error] ".$mysql->error);
			return "[Error]";
		}	
		
	}


/*function get_user_id($tableid){
		global $setid;
		$mysql  = new mysqli('localhost', 'admin-bingo', 'ulU!ad5DBq-.', 'bingo_virtual');
		$sql_bingo = "SELECT * FROM user_table". 
					" WHERE table_index ='".$tableid."' ".
					" AND room ='".$setid."' ";
		$result = $mysql->query($sql_bingo) or die($mysql->error);
		if ($row = mysqli_fetch_array($result)) {
			$count = $row["user_id"];
			$mysql->close();
			return $count;
		}else
		{

			$mysql->close();
			//print_r("---- [Error] ".$mysql->error);
			return "NU";
		}
		
		
	}*/

/*
	This function prevents to inject malicious code inside the App
*/
	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	function asign_table($user_id){
		//global $setid;
		$CI =& get_instance();
		$setid = get_set_id();
		$result = get_table_id($user_id);
		if ($result == "[Error]") {
			add_table($user_id);
		}
	}

	function add_table($user_id){
		//global $setid;
		$CI =& get_instance();
		$setid = get_set_id();

		$card = generate_card($CI->config->item('freesquare'));
		$CI->bingo_model->addUserTable($setid, $user_id, $card);
		
	}

	function remove_table($user_id){
		//global $setid;
		$CI =& get_instance();
		$setid = get_set_id();

		$CI->bingo_model->removeUserTable($setid, $user_id);
		
	}

	function setCurrentWinningPattern($pattern_id){
		//global $setid;
		$CI =& get_instance();
		$setid = get_set_id();

		$CI->bingo_model->setRoomWinningPattern($setid, $pattern_id);
		
	}

	function setCurrentDrawMode($mode){
		//global $setid;
		$CI =& get_instance();
		$setid = get_set_id();

		$CI->bingo_model->setRoomDrawMode($setid, $mode);
		
	}

	/*function save_users_bingo_DB($users){
		global $setid;

		$mysql  = new mysqli('localhost', 'admin-bingo', 'ulU!ad5DBq-.', 'bingo_virtual');
		$sql_bingo = "UPDATE bingos". 
					" SET users='". $users."'".
					" WHERE id ='".$setid."' ";
		$result = $mysql->query($sql_bingo) or die($mysql->error);
		$mysql->close();
	}*/