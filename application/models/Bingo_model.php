<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bingo_model extends CI_Model {

	public function getLastBingoId($room){
		/*$sql_bingo = "SELECT id FROM bingos WHERE room='".$room."'". 
				" ORDER BY id DESC".
				" LIMIT 1";*/
		$this->db->select('id,name');
        $this->db->from('bingos');
		$this->db->where("room",$room);
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1); 
		$resultado = $this->db->get();
		return $resultado->row();
	}
	public function createBingo($room){
		
		$data  = array(
			'room' => $room
		);
		$this->db->insert("bingos",$data);
		return $this->db->insert_id();
		
	}

	public function getRoomIframe($room){
		$this->db->select('iframe');
        $this->db->from('bingos');
		$this->db->where("id",$room);
		$this->db->limit(1); 
		$resultado = $this->db->get();
		return $resultado->row();
	}

	public function setRoomIframe($room, $iframe){
		$data  = array(
			'iframe' => $iframe
		);
		$this->db->where("id",$room);
		return $this->db->update("bingos",$data);
	}
	public function getRoomChannel($room){
		$this->db->select('winning_patterns');
        $this->db->from('bingos');
		$this->db->where("id",$room);
		$this->db->limit(1); 
		$resultado = $this->db->get();
		return $resultado->row();
	}
	public function setRoomChannel($room, $channel){
		$data  = array(
			'winning_patterns' => $channel
		);
		$this->db->where("id",$room);
		return $this->db->update("bingos",$data);
	}

	public function getAllWinningPatterns(){
		$this->db->select('*');
        $this->db->from('winning_patterns');
		$resultado = $this->db->get();
		//if($resultado->result() != null)
			return $resultado->result();
		//else
		//	return array();
	}
	public function getRoomWinningPatterConfig($room){
		$this->db->select('bingo_winning_pattern.bingo,
							winning_patterns.*');
        $this->db->from('bingo_winning_pattern');
        $this->db->join('winning_patterns', 'bingo_winning_pattern.winning_pattern = winning_patterns.id');
		$this->db->where("bingo",$room);
		$resultado = $this->db->get();
		return $resultado->result();
		
		/*$this->db->select('winning_patterns_config');
        $this->db->from('bingos');
		$this->db->where("id",$room);
		$this->db->limit(1); 
		$resultado = $this->db->get();
		if($resultado->row() != null)
			return json_decode($resultado->row()->winning_patterns_config,true);
		else
			return array();*/
	}
	public function getRoomWinningPattern($room){
		$this->db->select('bingos.current_wp,
							winning_patterns.*');
        $this->db->from('bingos');
        $this->db->join('winning_patterns', 'bingos.current_wp = winning_patterns.id');
		$this->db->where("bingos.id",$room);
		$resultado = $this->db->get();
		return $resultado->row();
	}
	public function setRoomWinningPattern($room,$patid){
		$data  = array(
			'current_wp' => $patid
		);
		$this->db->where("id",$room);
		return $this->db->update("bingos",$data);
	}
	public function setRoomDrawMode($room,$mode){
		$data  = array(
			'current_draw_mode' => $mode
		);
		$this->db->where("id",$room);
		return $this->db->update("bingos",$data);
	}
	public function setRoomWinningPatterConfig($room, $config){
		$data  = array(
			'winning_patterns_config' => $config
		);
		$this->db->where("id",$room);
		return $this->db->update("bingos",$data);
	}

	public function removeRoomWinningPattern($room){
		$this->db->where("bingo",$room);
		return $this->db->delete("bingo_winning_pattern");
	}

	public function saveRoomWinningPattern($data){
		$this->db->insert("bingo_winning_pattern",$data);
	}

	public function getUsersTables($setid){
		$this->db->select('user_table.*,
                        clients.name,
                        clients.logged,
                        clients.last_login,
                        clients.last_activity');
        $this->db->from('user_table')->join('clients', 'user_table.user_id = clients.user_name');
		$this->db->where("user_table.room",$setid);
		$resultado = $this->db->get();
		return $resultado->result();
	}
	public function getUserTables($setid, $id){
		/*$sql_bingo = "SELECT * FROM user_table". 
					" WHERE user_id ='".$id."' ".
					" AND room ='".$setid."' ";*/
		$this->db->select('user_table.*,
                        clients.name,
                        clients.logged,
                        clients.last_login,
                        clients.last_activity');
        $this->db->from('user_table')->join('clients', 'user_table.user_id = clients.user_name');
		$this->db->where("user_table.user_id",$id);
		$this->db->where("user_table.room",$setid);
		$resultado = $this->db->get();
		return $resultado->result();
	}
	public function getCard($setid, $card){
		/*$sql_bingo = "SELECT * FROM user_table". 
					" WHERE user_id ='".$id."' ".
					" AND room ='".$setid."' ";*/
		$this->db->select('card');
        $this->db->from('user_table');
		$this->db->where("table_index",$card);
		$this->db->where("room",$setid);
		$resultado = $this->db->get();
		//print_r($this->db->last_query()); 
		$row = $resultado->row();
		if($row == null)
			return null;
		else
			return json_decode($row->card,true);
		
	}
	//getCard($card)
	public function addUserTable($setid, $user_id, $card){
		/*$sql_bingo = "SELECT MAX(table_index) as table_index FROM user_table WHERE room ='".$setid."' ";
		if ($row = mysqli_fetch_array($result)) {
			$count = $row["table_index"];
		}else
		{
			$count = 0;
		}
		$count++;
		$sql_bingo = "INSERT INTO user_table (user_id, table_index, room) VALUES ('".$user_id."','".$count."','".$setid."')";*/


		$this->db->select('MAX(table_index) as table_index');
        $this->db->from('user_table');
		$this->db->where("room",$setid);
		$resultado = $this->db->get();
		$row = $resultado->row();
		if($row == null)
			$count = 0;
		else
			$count = $row->table_index;
		$count++;

		$data  = array(
			'user_id' => $user_id,
			'room' => $setid,
			'card' => json_encode($card),
			'table_index' => $count
		);
		return $this->db->insert("user_table",$data);
	}

	public function removeUserTable($setid, $user_id){
		
		$this->db->select('MAX(table_index) as table_index, id');
        $this->db->from('user_table');
		$this->db->where("room",$setid);
		$this->db->where("user_id",$user_id);
		$resultado = $this->db->get();
		$row = $resultado->row();
		if($row == null)
		{
			return false;
		}
		else
		{
			$id = $row->id;
			$this->db->where("id",$id);
			return $this->db->delete("user_table");
		}
	}

	public function getDraw($setid){
		$this->db->select('data');
        $this->db->from('draws');
		$this->db->where("room",$setid);
		$resultado = $this->db->get();
		return $resultado->row();
	}

	public function getSet($setid){
		$this->db->select('data');
        $this->db->from('sets');
		$this->db->where("room",$setid);
		$resultado = $this->db->get();
		return $resultado->row();
	}
	public function getWinningPatterns($setid){
		$this->db->select('winning_patterns');
        $this->db->from('bingos');
		$this->db->where("id",$setid);
		$resultado = $this->db->get();
		return $resultado->row();
	}
	public function getNewWinners($setid){
		$this->db->select('new_winners');
        $this->db->from('bingos');
		$this->db->where("id",$setid);
		$resultado = $this->db->get();
		return $resultado->row();
	}
	public function getOldWinners($setid){
		$this->db->select('old_winners');
        $this->db->from('bingos');
		$this->db->where("id",$setid);
		$resultado = $this->db->get();
		return $resultado->row();
	}
	public function saveNewWinners($setid,$sets){

		$data  = array(
			'new_winners' => json_encode($sets)
		);
		$this->db->where("id",$setid);
		return $this->db->update("bingos",$data);

		/*$this->db->select('new_winners');
        $this->db->from('bingos');
		$this->db->where("id",$setid);
		$resultado = $this->db->get();
		return $resultado->row();*/
	}
	public function clearNewWinners($setid){

		$data  = array(
			'new_winners' => NULL
		);
		$this->db->where("id",$setid);
		return $this->db->update("bingos",$data);

		/*$this->db->select('new_winners');
        $this->db->from('bingos');
		$this->db->where("id",$setid);
		$resultado = $this->db->get();
		return $resultado->row();*/
	}
	public function clearOldWinners($setid){

		$data  = array(
			'old_winners' => NULL
		);
		$this->db->where("id",$setid);
		return $this->db->update("bingos",$data);

		/*$this->db->select('new_winners');
        $this->db->from('bingos');
		$this->db->where("id",$setid);
		$resultado = $this->db->get();
		return $resultado->row();*/
	}
	public function saveOldWinners($setid,$sets){

		$data  = array(
			'old_winners' => json_encode($sets)
		);
		$this->db->where("id",$setid);
		return $this->db->update("bingos",$data);

		/*$this->db->select('old_winners');
        $this->db->from('bingos');
		$this->db->where("id",$setid);
		$resultado = $this->db->get();
		return $resultado->row();*/
	}
	public function drawExits($setid){
		$this->db->select('id');
        $this->db->from('draws');
		$this->db->where("room",$setid);
		$resultado = $this->db->get();
		return $resultado->row();
	}
	public function setExits($setid){
		$this->db->select('id');
        $this->db->from('sets');
		$this->db->where("room",$setid);
		$resultado = $this->db->get();
		return $resultado->row();
	}

	public function getRoomData($setid){
		$this->db->select('*');
        $this->db->from('bingos');
		$this->db->where("id",$setid);
		$resultado = $this->db->get();
		return $resultado->row();
	}

	public function getNumCards($setid){
		//$sql_bingo = "SELECT MAX(table_index) as table_index FROM user_table WHERE room ='".$setid."' ";

		$this->db->select('MAX(table_index) as table_index');
        $this->db->from('user_table');
		$this->db->where("room",$setid);
		$resultado = $this->db->get();
		return $resultado->row();
	}

	public function updateRoomData($setid, $data){
		//$sql = "UPDATE `bingos` SET `name`='".utf8_decode($room_name)."',`date`='".date('Y-m-d',strtotime(str_replace('/', '-', $date)))."',`time`='".$time."' WHERE id=".$setid; 
		//if($setid)
		$this->db->where("id",$setid);
		return $this->db->update("bingos",$data);		
	}

	public function clearDraws($setid){
		//$sql = "UPDATE `bingos` SET `name`='".utf8_decode($room_name)."',`date`='".date('Y-m-d',strtotime(str_replace('/', '-', $date)))."',`time`='".$time."' WHERE id=".$setid; 
		$data  = array(
				'data' => ''
			);
		$this->db->where("room",$setid);
		return $this->db->update("draws",$data);		
	}

	public function clearMessages($setid){
		//$sql = "UPDATE `bingos` SET `name`='".utf8_decode($room_name)."',`date`='".date('Y-m-d',strtotime(str_replace('/', '-', $date)))."',`time`='".$time."' WHERE id=".$setid; 
		$data  = array(
				'status' => '1'
			);
		$this->db->where("room",$setid);
		$this->db->where("status",'0');
		return $this->db->update("messages",$data);		
	}

	public function saveSet($setid, $set){
		//$sql = "UPDATE `bingos` SET `name`='".utf8_decode($room_name)."',`date`='".date('Y-m-d',strtotime(str_replace('/', '-', $date)))."',`time`='".$time."' WHERE id=".$setid; 
		if($this->setExits($setid) !== null)
		{
			$data  = array(
				'data' => json_encode($set)
			);
			$this->db->where("room",$setid);
			return $this->db->update("sets",$data);
		}else
		{
			$data  = array(
				'data' => json_encode($set),
				'room' => $setid
			);
			return $this->db->insert("sets",$data);
		}
				
	}

	public function saveDraws($setid, $draws){
		//$sql = "UPDATE `bingos` SET `name`='".utf8_decode($room_name)."',`date`='".date('Y-m-d',strtotime(str_replace('/', '-', $date)))."',`time`='".$time."' WHERE id=".$setid; 
		if($this->drawExits($setid) !== null)
		{
			$data  = array(
				'data' => json_encode($draws)
			);
			$this->db->where("room",$setid);
			return $this->db->update("draws",$data);
		}else
		{
			$data  = array(
				'data' => json_encode($draws),
				'room' => $setid
			);
			return $this->db->insert("draws",$data);
		}
				
	}
	
	public function getAllMessages($setid){
		//$query3 = "SELECT * FROM messages WHERE status='0' ORDER BY timestamp DESC;";
		$this->db->select('clients.name, messages.*');
        $this->db->from('messages');
        $this->db->join('clients', 'messages.user_id = clients.user_name');
		$this->db->where("room",$setid);
		$resultado = $this->db->get();
		return $resultado->result();
	}
	
	public function getMessages($setid){
		//$query3 = "SELECT * FROM messages WHERE status='0' ORDER BY timestamp DESC;";

		$this->db->select('*');
        $this->db->from('messages');
		$this->db->where("room",$setid);
		$this->db->where("status",'0');
		$this->db->order_by('timestamp', 'DESC');
		$resultado = $this->db->get();
		return $resultado->result();
	}

	public function getMessage($room, $id, $card){
		//$query3 = "SELECT * FROM messages WHERE status='0' ORDER BY timestamp DESC;";

		$this->db->select('*');
        $this->db->from('messages');
		$this->db->where("room",$room);
		$this->db->where("user_id",$id);
		$this->db->where("card",$card);
		$this->db->where("status",'0');
		$resultado = $this->db->get();
		return $resultado->row();
	}

	public function saveMessages($data){
		//$query3 = "SELECT * FROM messages WHERE status='0' ORDER BY timestamp DESC;";
		return $this->db->insert("messages",$data);
	}

	public function hideMessages($id){
		//"UPDATE messages SET status='1' WHERE id='".$id."';";
		$data  = array(
			'status' => '1'
		);
		$this->db->where("id",$id);
		return $this->db->update("messages",$data);
	}
	public function winnerMessages($id){
		//"UPDATE messages SET status='1' WHERE id='".$id."';";
		$data  = array(
			'status' => '2'
		);
		$this->db->where("id",$id);
		return $this->db->update("messages",$data);
	}

}