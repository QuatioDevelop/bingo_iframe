<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bingo extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
		$this->permissions = $this->backend_lib->control();
        if (!$this->session->userdata("user_data")) {
			redirect(base_url());
		}
		date_default_timezone_set("America/Bogota");
		$this->load->helper('file');
		$this->load->model("bingo_model");
		$this->load->model("clientes_model");
    }

	public function index()
	{
		$data  = array(
			'u_permissions' => $this->permissions 
		);
		$this->backend_model->setUserPlace($this->session->userdata('user_data')['user_uname'],"options");
		$this->load->view("admin/bingo/view",$data);
		
	}

	public function colorview()
	{
		$data  = array(
			'iframe' => $this->bingo_model->getRoomIframe(get_set_id()),
			'u_permissions' => $this->permissions 
		);
		$this->load->view("admin/bingo/beatyview",$data);
		
	}

	public function audit()
	{
		$sid = get_set_id();
		$data  = array(
			'logs' => $this->logs_model->getLogMessages($sid),
			'bingo_tries' => $this->bingo_model->getAllMessages($sid),
			'u_permissions' => $this->permissions 
		);
		$this->backend_model->setUserPlace($this->session->userdata('user_data')['user_uname'],"audit");
		$this->load->view("admin/bingo/audit",$data);
		
	}

	public function loginfails()
	{
		$data  = array(
			'login_fails' => $this->logs_model->getFailSesion(get_set_id()),
			'error_regs' => $this->logs_model->getErrorRegs(get_set_id()),
			'u_permissions' => $this->permissions 
		);
		$this->backend_model->setUserPlace($this->session->userdata('user_data')['user_uname'],"loginfails");
		$this->load->view("admin/bingo/sesionfails",$data);
		
	}

	public function winners()
	{
		$set = get_set_id();
		$data  = array(
			'total_clients' => $this->clientes_model->getClientsLogged($set), 
			'winners' => $this->clientes_model->getWinners($set), 
			'u_permissions' => $this->permissions 
		);
		$this->backend_model->setUserPlace($this->session->userdata('user_data')['user_uname'],"winnerslist");
		$this->load->view("admin/bingo/winnerslist",$data);
	}

	public function winnerslite()
	{
		$draws = load_draws();
		$data  = array(
			'winners' => check_bingo(),
			'total_clients' => $this->clientes_model->getClientsLogged(get_set_id()), 
			'total_draws' => $draws != null ? sizeof($draws) : 0,
			'last_draw' => $draws != null && sizeof($draws) > 0 ? $draws[sizeof($draws)-1] : "-",
			'u_permissions' => $this->permissions 
		);
		$this->backend_model->setUserPlace($this->session->userdata('user_data')['user_uname'],"winners");
		$this->load->view("admin/bingo/winners",$data);
		
	}

	public function iswinner()
	{
		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		/*$data  = array(
			'winners' => check_bingo_lite(),
			'total_clients' => $this->clientes_model->getClientsLogged(get_set_id()), 
			'u_permissions' => $this->permissions 
		);*/
		$uid = test_input($this->input->post('uid'));
		//$dato_json = array('response' => "Ready".$id);
		echo json_encode(check_bingo_user($uid));
		
	}

	public function checkwinner()
	{
		$this->outh_model->CSRFVerify();

		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		$set = get_set_id();
		$draws = load_draws();

		$winners = check_bingo();//$this->clientes_model->getWinners($set);
		$wins = "";
		foreach($winners as $winner){
            $wins .= '<tr>';
            $wins .= '    <td>'.$winner['name'].'</td>';
            $wins .= '    <td>'.$winner['user_id'].'</td>';
            $wins .= '    <td><a href="'.base_url().'admin/bingo/viewcard/'.$this->outh_model->Encryptor('encrypt',$winner['user_id']).'/'.$winner['card'].'" target=_blank>'.$winner['card'].'</a></td>';
                
                  $patterns = json_decode($winner['patterns'], true); 
                  $patt = array();
                  foreach($patterns[$winner['card']] as $pattern)
                  {
                    array_push($patt, $pattern['name']);
                  }
                
            $wins .= '    <td>'.implode("<br>",$patt).'</td>';
            $wins .= '    <td>'.$winner['last_login'].'</td>';
            $wins .= '    <td>'.$winner['last_activity'].'</td>';
            $wins .= '    <td>';
            $wins .= '      <div class="btn-group">';
            $wins .= '        <a href="'.base_url().'admin/bingo/dobingo/'.$winner['user_id'].'/'.$winner['card'].'" class="btn btn-info"><span class="fa fa-bold"></span></a>';
            $wins .= '      </div>';
            $wins .= '    </td>';
            $wins .= '</tr>';
          }


		$data  = array(
			'winners' => $wins, 
			'total_clients' => $this->clientes_model->getClientsLogged($set), 
			'total_draws' => $draws != null ? sizeof($draws) : 0,
			'last_draw' => $draws != null && sizeof($draws) > 0 ? $draws[sizeof($draws)-1] : "-",
			'u_permissions' => $this->permissions 
		);
		
		echo json_encode($data);
	}

	public function dobingo($uid, $card)
	{
		$this->outh_model->CSRFVerify();
	
		//if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		date_default_timezone_set("America/Bogota");

		$timestamp = date("Y-m-d H:i:s");
		//$uid = test_input($this->input->post('id'));
		//$sid = test_input($this->input->post('sid'));
		//$card = test_input($this->input->post('card'));
		$sid = get_set_id();
		$already = $this->bingo_model->getMessage($sid, $uid, $card);
		if($already == null)
		{
			$wins = check_table($uid, $card);
			if(sizeof($wins) > 0 )
			{
				$data  = array(
					'user_id' => $uid,
					'room' => $sid,
					'timestamp' => $timestamp,
					'card' => $card,
					'patterns' => json_encode($wins),
					'status' => '0' 
				);
				$msgs = $this->bingo_model->saveMessages($data);
				//$query3    = "INSERT INTO messages values (NULL,'$uid','$sid','$timestamp','".$card."','".json_encode($wins)."','0')";
				//$con       = $mysqli->query($query3) or die($mysql->error);
				////$row        = mysqli_fetch_array($con);
			}
		}
		redirect(base_url()."admin/bingo/winners");
	}

	public function loadusers()
	{
		$data  = array(
			'error' => '',
			'u_permissions' => $this->permissions 
		);
		$this->load->view("admin/bingo/loadusers",$data);
		
	}

	public function do_upload()
    {
    	$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

    	set_time_limit(0);
    	$setid = get_set_id();
    	//echo "Uploading ";
    	//print_r($_FILES['userfile']);
    	// If import request is submitted
        if($this->input->post('importSubmit')){
            // Form field validation rules
            $this->form_validation->set_rules('userfile', 'CSV file', 'callback__file_check');
            // Validate submitted form data
            if($this->form_validation->run() == true){
            	$fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
				$lines = $this->_readInputFromFile($fp);
				$size = count($lines);
				//echo $size."<br>";
				$uc = 0;
				$nosaved = "";
				for ($i = 0; $i < $size; $i++)
				{
					//echo "-------------------------------------<br>";
					//echo "i = ".$i."<br>";
				    
				    $columns = explode(",", $lines[$i]);
					$id = test_input($columns[0]);
					$name = test_input($columns[1]);
					$email = test_input($columns[2]);
					$cellphone = test_input($columns[3]);
					$tablas = test_input($columns[4]);
					//$query = "INSERT INTO `users`(`user_id`, `name`, `email`, `phone`) VALUES ('".$id."','".($name)."','".$email."','".($cellphone)."')";
					$client = $this->clientes_model->getClient($id);

					if(empty($client))
					{
						$data  = array(
							'user_name' => $id, 
							'name' => $name,
							'email' => $email,
							'phone' => $cellphone,
							'bingo' => $setid,
							'password' => password_hash(strtoupper($id), PASSWORD_BCRYPT),
							'role' => '3'
						);

						if ($this->clientes_model->save($data)) {
							//$result = $mysqli->query($query) or die($mysqli->error);
							//echo $query." x ".$tablas."<br>";
							$uc++;
							for ($j=0; $j < $tablas; $j++) { 
							  	add_table($id);
							  	//echo "add_table(".$id.")<br>";
							}
						}else
						{
							$nosaved .= $id." No guardó<br>";
						}
					}else
					{
						$nosaved .= $id." Ya existe<br>";
					}
				}
				//print_r("Usuarios ")
				$error = array('success_msg' => 'Usuarios registrados: '.$uc.'/'.$size,'u_permissions' => $this->permissions,
								'info_msg' => $nosaved);
        		$this->logs_model->logMessage("info","Usuario ".$this->session->userdata('user_data')['user_uname']." registró ".$uc." nuevos usuarios");
				$this->load->view('admin/bingo/loadusers', $error);
            }else{
                $error = array('error_msg' => 'Invalid file, please select only CSV file.:)','u_permissions' => $this->permissions);
        		$this->logs_model->logMessage("warning","Usuario ".$this->session->userdata('user_data')['user_uname']." intentó registrar nuevos usuarios - Archivo inválido");
				$this->load->view('admin/bingo/loadusers', $error);
            }
        }else{
            $error = array('error_msg' => 'Error on file upload, please try again.:)','u_permissions' => $this->permissions);
        	$this->logs_model->logMessage("warning","Usuario ".$this->session->userdata('user_data')['user_uname']." intentó registrar nuevos usuarios - error subiendo archivo");
			$this->load->view('admin/bingo/loadusers', $error);
        }
            //$config['upload_path']          = './uploads/';
            //$config['allowed_types']        = 'cvs';
            //$config['max_size']             = 100;
    //
//
            //$this->load->library('upload', $config);
//
            //if (!$this->upload->do_upload('userfile'))
            //{
            //        $error = array('error' => $this->upload->display_errors(),'u_permissions' => $this->permissions);
            //        $this->load->view('admin/bingo/loadusers', $error);
            //}
            //else
            //{
            //	$fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
			//	$lines = readInputFromFile($fh);
			//	echo $size."<br>";
			//   for ($i = 0; $i < $size; $i++)
			//   {
			//		echo "-------------------------------------<br>";
			//		echo "i = ".$i."<br>";
			//      
			//      	$columns = explode(",", $lines[$i]);
			//		$id = $columns[0];
			//		$name = $columns[1];
			//		$email = $columns[2];
			//		$cellphone = $columns[3];
			//		$tablas = $columns[4];
			//		$query = "INSERT INTO `users`(`user_id`, `name`, `email`, `phone`) VALUES ('".$id."','".($name)."','".$email."','".($cellphone)."')";
			//		//$result = $mysqli->query($query) or die($mysqli->error);
			//		echo $query." x ".$tablas."<br>";
			//		/*$sql = "SELECT * FROM users WHERE user_id='".$id."'";
			//		$result = $mysqli->query($sql) or die($mysqli->error);
			//		if ($row = mysqli_fetch_array($result)) {
			//			echo "<p> Se encontró un usuario con esta cédula ".$id."</p>";
			//		}else
			//		{
			//			//(`id`, `name`, `user_id`, `email`, `phone`, `cellphone`, `address`)
			//			$query = "INSERT INTO `users`(`user_id`, `name`, `email`, `phone`) VALUES ('".$id."','".($name)."','".$email."','".($cellphone)."')";
			//			$result = $mysqli->query($query) or die($mysqli->error);
			//			//echo $query."<br>";
			//			for ($j=0; $j < $tablas; $j++) { 
			//		      	add_table($id);
			//		      	//echo "add_table(".$id.")<br>";
			//			}
			//		}*/
			//		
			//   }
            //    //$data = array('upload_data' => $this->upload->data());
            //    //$this->load->view('upload_success', $data);
            //}
    }

    /*
     * Callback function to check file value and type during validation
     */
    public function _file_check($str){
        
        $allowed_mime_types = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
        if(isset($_FILES['userfile']['name']) && $_FILES['userfile']['name'] != ""){
            $mime = get_mime_by_extension($_FILES['userfile']['name']);
            $fileAr = explode('.', $_FILES['userfile']['name']);
            $ext = end($fileAr);
            if(($ext == 'csv') && in_array($mime, $allowed_mime_types)){
                return true;
            }else{
                //$this->form_validation->set_message('file_check', 'Please select only CSV file to upload.');
                //print_r('Please select only CSV file to upload.');
                return false;
            }
        }else{
            //$this->form_validation->set_message('file_check', 'Please select a CSV file to upload.');
            //print_r('Please select a CSV file to upload.');
            return false;
        }
    }

    function _readInputFromFile($fh)
	{
	   //$fh = fopen($file, 'r');
		if(isset($fh))
		{
		   while (!feof($fh))
		   {
		      $ln = fgets($fh);
		      $parts[] = $ln;
		   }

		   fclose($fh);

		   return $parts;
		}else
			return array();
	}
	
	public function viewcard($user_id, $card)
	{

		//$id = $cardnumber;//get_table_id($_GET["cardnumber"]);

		$result = $this->clientes_model->getClient($this->outh_model->Encryptor('decrypt',$user_id));

                /*$sql = "SELECT * FROM users WHERE user_id='".$id."'";
                $result = $mysqli->query($sql) or die($mysql->error());
                if ($row = mysqli_fetch_array($result)) {
                  $name = utf8_encode($result->name);
                  $clampName = (strlen($name) > 53) ? substr($name,0,50).'...' : $name;
                  echo $clampName. " - ".$id;
                }*/
		$name = $result->name;
        $clampName = (strlen($name) > 53) ? substr($name,0,50).'...' : $name;
		$data  = array(
			'card' => $card,
			'clampName' => $clampName,
			'user_id' => $this->outh_model->Encryptor('decrypt',$user_id)
		);
		$this->load->view("admin/bingo/card",$data);
		
	}

	public function checktable($user_id, $id, $card)
	{

		//$id = $cardnumber;//get_table_id($_GET["cardnumber"]);

		$result = $this->clientes_model->getClient($this->outh_model->Encryptor('decrypt',$user_id));

                /*$sql = "SELECT * FROM users WHERE user_id='".$id."'";
                $result = $mysqli->query($sql) or die($mysql->error());
                if ($row = mysqli_fetch_array($result)) {
                  $name = utf8_encode($result->name);
                  $clampName = (strlen($name) > 53) ? substr($name,0,50).'...' : $name;
                  echo $clampName. " - ".$id;
                }*/
		$name = $result->name;
        $clampName = (strlen($name) > 53) ? substr($name,0,50).'...' : $name;
		$data  = array(
			'card' => $card,
			'clampName' => $clampName,
			'user_id' => $this->outh_model->Encryptor('decrypt',$user_id),
			'id' => $id
		);
		$this->load->view("admin/bingo/checktable",$data);
		
	}

	public function dochecktable()
	{
		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		$setid = get_set_id();
			// echo "room: ".$room. " setid ". $setid;
			// echo "numbercards ". $numbercards;
			//$cardnumber = test_input($_GET["cardnumber"]);
			$id = test_input($this->input->post("id"));
			//$card = test_input($_GET["card"]);
		$this->bingo_model->hideMessages($id);
		//$query3    = "UPDATE messages SET status='1' WHERE id='".$id."';";
		//$con       = $mysqli->query($query3) or die($mysql->error);
		////$row        = mysqli_fetch_array($con);

		$dato_json = array('response' => "Ready".$id);
		echo json_encode($dato_json);
	}

	public function dowinnertable()
	{
		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		$setid = get_set_id();
		// echo "room: ".$room. " setid ". $setid;
		// echo "numbercards ". $numbercards;
		//$cardnumber = test_input($_GET["cardnumber"]);
		$id = test_input($this->input->post("id"));
		//$card = test_input($_GET["card"]);
		$this->bingo_model->winnerMessages($id);
		//$query3    = "UPDATE messages SET status='1' WHERE id='".$id."';";
		//$con       = $mysqli->query($query3) or die($mysql->error);
		////$row        = mysqli_fetch_array($con);

		$dato_json = array('response' => "Ready".$id);
		echo json_encode($dato_json);
	}

	public function tablechecked($id)
	{
		$this->outh_model->CSRFVerify();
	
		//if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		$setid = get_set_id();
		// echo "room: ".$room. " setid ". $setid;
		// echo "numbercards ". $numbercards;
		//$cardnumber = test_input($_GET["cardnumber"]);
		//$id = test_input($this->input->post("id"));
		//$card = test_input($_GET["card"]);
		$this->bingo_model->hideMessages($id);
		//$query3    = "UPDATE messages SET status='1' WHERE id='".$id."';";
		//$con       = $mysqli->query($query3) or die($mysql->error);
		////$row        = mysqli_fetch_array($con);

		//$dato_json = array('response' => "Ready".$id);
		//echo json_encode($dato_json);
		redirect(base_url()."admin/bingo/audit");
	}
	public function tablewinner($id)
	{
		$this->outh_model->CSRFVerify();
	

		$setid = get_set_id();
		// echo "room: ".$room. " setid ". $setid;
		// echo "numbercards ". $numbercards;
		//$cardnumber = test_input($_GET["cardnumber"]);
		//$id = test_input($this->input->post("id"));
		//$card = test_input($_GET["card"]);
		$this->bingo_model->winnerMessages($id);
		//$query3    = "UPDATE messages SET status='1' WHERE id='".$id."';";
		//$con       = $mysqli->query($query3) or die($mysql->error);
		////$row        = mysqli_fetch_array($con);

		//$dato_json = array('response' => "Ready".$id);
		//echo json_encode($dato_json);
		redirect(base_url()."admin/bingo/audit");
	}

	public function pushandler()
	{
		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		//$id = test_input($_POST['id']);

		//$query3    = "UPDATE messages SET status='1' WHERE id='".$id."';";
		//$con       = $mysqli->query($query3) or die($mysql->error);
		////$row        = mysqli_fetch_array($con);
		$msgs = $this->bingo_model->getMessages(get_set_id());
		foreach ($msgs as $msg ) {
			$msg->user_id = $this->outh_model->Encryptor('encrypt', $msg->user_id);
		}

		echo json_encode($msgs);
	}

	public function restart()
	{
		restart();
		redirect(base_url()."admin/bingo/host");
		//$this->host();
	}

	public function randomnumber()
	{
		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		$setid = get_set_id();
		
		$cfg = $this->bingo_model->getRoomData($setid);

		$draws=load_draws(); 
		$maxColumnNumber = $this->config->item('maxColumnNumber');
		//$draws = load_draws();
		if ($draws!=null){
			$total=count($draws);
		} else $total=0;
	    //print_r("  cdm:  ");
		//print_r($cfg->current_draw_mode);
	    //print_r("  <br>  ");
		$ctrlReady = TRUE;
		$control = 0;
		$samenumbertwice= true;
		if($total == 75)
		{
			$dato_json = array('last_draws' => getLastDraws(), 'letter' => "-", 'winners' => !empty(check_bingo_lite()), 'number' => "-" );
			echo json_encode($dato_json);
		}else
		{
			while ($samenumbertwice) {
				$control++;
				if($cfg->current_draw_mode==0)
				{
					$col = rand(0,4);
				}else
				{
					$col = $cfg->current_draw_mode - 1;
				}
				$row = rand(1,$maxColumnNumber);
				$nume = $maxColumnNumber*$col+$row;
				//echo $col." - ".$row." - ".$nume;
	    		//print_r("  <br>  ");
				$samenumbertwice=false;

				if ($total >0 ) //no need to check if we have no numbers yet
				for ($i=0;$i < $total; $i++)
				if ($nume==$draws[$i]) $samenumbertwice=true;
				if($control > 75)
				{
					$ctrlReady = FALSE;
					break;
				}
			}
			if($ctrlReady)
			{
				submit_number(find_letter($nume).$nume/*,get_number_cards()*/);
				$dato_json = array('last_draws' => getLastDraws(),'letter' => find_letter($nume), 'winners' => !empty(check_bingo_lite()), 'number' => $nume,  );
				echo json_encode($dato_json);//"<h1>".find_letter($nume).$nume."</h1>";
			}else
			{
				$dato_json = array('last_draws' => getLastDraws(), 'letter' => "-", 'winners' => !empty(check_bingo_lite()), 'number' => "-" );
				echo json_encode($dato_json);
			}
		}
	}

	public function options(){

		$data  = array(
			'winningpatterns' => $this->bingo_model->getAllWinningPatterns(),
			'winningpatternarray' => $this->bingo_model->getRoomWinningPatterConfig(get_set_id()),
			'channel' => $this->bingo_model->getRoomChannel(get_set_id()),
			'iframe' => $this->bingo_model->getRoomIframe(get_set_id())
		);

		$this->load->view("admin/bingo/options",$data);
	}

	public function saveOptions(){

		$this->outh_model->CSRFVerify();

		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		$winningpatterns = $this->bingo_model->getAllWinningPatterns();

		$iframe = $this->input->post("iFrame"); 
		$channel = $this->input->post("channel"); 
		$winningpatternarray = array();
		foreach ($winningpatterns as $wp) {
			array_push($winningpatternarray,array('enabled' => $this->input->post("winningpatternform".$wp->id), 'pattern' => $wp ));
		}
		/*$winningpattern0 = $this->input->post("winningpatternform0"); 
		$winningpattern1 = $this->input->post("winningpatternform1"); 
		$winningpattern2 = $this->input->post("winningpatternform2"); 
		$winningpattern3 = $this->input->post("winningpatternform3"); 
		$winningpattern4 = $this->input->post("winningpatternform4"); 
		$winningpattern5 = $this->input->post("winningpatternform5"); 
		$winningpattern6 = $this->input->post("winningpatternform6"); 
		$winningpattern7 = $this->input->post("winningpatternform7"); 
		$winningpattern8 = $this->input->post("winningpatternform8"); 
		$winningpattern9 = $this->input->post("winningpatternform9"); 
	    $winningpattern10 = $this->input->post("winningpatternform10");
	    $winningpattern11 = $this->input->post("winningpatternform11");*/

		//echo $winningpatternform0 ." 1 ".$winningpatternform1 ." 2 ".$winningpatternform2 ." 3 ".$winningpatternform3 ." 4 ".$winningpatternform4 ." 5 ".$winningpatternform5 ." 6 ".$winningpatternform6 ." 7 ".$winningpatternform7 ." 8 ".$winningpatternform8 ." 9 ".$winningpatternform9 ." 10 ".$winningpatternform10." 11 ";
		$set = get_set_id();

		$this->bingo_model->setRoomIframe($set, $iframe);
		$this->bingo_model->setRoomChannel($set, $channel);

		//$winningpatternarray = array ($winningpattern0,$winningpattern1, $winningpattern2, $winningpattern3, $winningpattern4, $winningpattern5, $winningpattern6, $winningpattern7, $winningpattern8, $winningpattern9, $winningpattern10, $winningpattern11);
		
		/*$this->config->set_item('winningpattern0',$winningpatternform0);
		$this->config->set_item('winningpattern1',$winningpatternform1);
		$this->config->set_item('winningpattern2',$winningpatternform2);
		$this->config->set_item('winningpattern3',$winningpatternform3);
		$this->config->set_item('winningpattern4',$winningpatternform4);
		$this->config->set_item('winningpattern5',$winningpatternform5);
		$this->config->set_item('winningpattern6',$winningpatternform6);
		$this->config->set_item('winningpattern7',$winningpatternform7);
		$this->config->set_item('winningpattern8',$winningpatternform8);
		$this->config->set_item('winningpattern9',$winningpatternform9);
		$this->config->set_item('winningpattern10',$winningpatternform10);*/

		//$this->bingo_model->setRoomWinningPatterConfig($set, json_encode($winningpatternarray));

		$this->bingo_model->removeRoomWinningPattern($set);

		if($winningpatternarray && count($winningpatternarray) > 0)
		{
			for ($i=0; $i < count($winningpatternarray); $i++) { 
				if($winningpatternarray[$i]['enabled'] == "on")
				{
					$dataPatterns  = array(
						'bingo' => $set, 
						'winning_pattern' => $winningpatternarray[$i]['pattern']->id
					);

					$this->bingo_model->saveRoomWinningPattern($dataPatterns);
				}
			}
		}

		$this->logs_model->logMessage("info","Usuario ".$this->session->userdata('user_data')['user_uname']." cambió las opciones del bingo ".$set." - iframe: ".$iframe);
		//restart();
		//$this->load->view("admin/bingo/options");
		redirect(base_url()."admin/bingo");
	}
	public function config(){

		$this->load->view("admin/bingo/config", $this->bingo_model->getRoomData(get_set_id()));
	}
	public function saveConfig(){

		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST
		
		$room_name = $this->input->post("room_name");
		$time = $this->input->post("time");
		$date = $this->input->post("date");
		
		$this->form_validation->set_rules("room_name","Nombre","required");
		$this->form_validation->set_rules("time","Hora","required|trim|min_length[8]|max_length[8]|callback_validate_time");
		$this->form_validation->set_rules("date","Fecha","required|callback_validate_date");
		
		if ($this->form_validation->run()) {
			
			$data  = array(
				'name' => $room_name,
				'time' => $time,
				'date' => $date
			);
			
			if ($this->bingo_model->updateRoomData(get_set_id(),$data)) {
				//@unlink ("data/old_winners.".$setid.".json");
				//@unlink ("data/new_winners.".$setid.".json");
				//@unlink ("data/draws.".$setid.".json");
				$this->bingo_model->clearDraws(get_set_id());
				//generate_cards($this->config->item('MAX_LIMIT'), 1);

				$this->logs_model->logMessage("info","Usuario ".$this->session->userdata('user_data')['user_uname']." cambió la configuración del bingo ".$set);
				redirect(base_url()."admin/bingo");
			}
			else{
				$this->session->set_flashdata("error",$this->lang->line('error_saving_data'));
				$this->logs_model->logMessage("warning","Error al usuario ".$this->session->userdata('user_data')['user_uname']." tratar de cambiar la configuración del bingo ".$set);
				$this->config();
			}
		}
		else{
			$this->logs_model->logMessage("warning","Error de campos al usuario ".$this->session->userdata('user_data')['user_uname']." tratar de cambiar la configuración del bingo ".$set);
			$this->config();
		}
	}

	public function validate_time($str){
	    if (strrchr($str,":")) {
	        list($hh, $mm, $ss) = explode(':', $str);
	        if (!is_numeric($hh) || !is_numeric($mm) || !is_numeric($ss)){
	        	$this->form_validation->set_message('time', 'El campo {field} debe tener el formato "HH:mm:ss"');
	            return FALSE;
	        }elseif ((int) $hh > 24 || (int) $mm > 59 || (int) $ss > 59){
	        	$this->form_validation->set_message('time', 'El campo {field} debe tener el formato "HH:mm:ss"');
	            return FALSE;
	        }elseif (mktime((int) $hh, (int) $mm, (int) $ss) === FALSE){
	        	$this->form_validation->set_message('time', 'El campo {field} debe tener el formato "HH:mm:ss"');
	            return FALSE;
	        }
	        return TRUE;
	    }else{
	        $this->form_validation->set_message('time', 'El campo {field} debe tener el formato "HH:mm:ss"');
	        return FALSE;
	    }   
	}

	public function validate_date($date) {

		if (date('Y-m-d', strtotime($date)) == $date) {
			return TRUE;
		} else {
			$this->form_validation->set_message('date', 'El campo {field} debe tener el formato "yyyy-mm-dd"');
			return FALSE;
		}
		
	}

	public function host(){
		$data  = array(
			'config' => $this->bingo_model->getRoomData(get_set_id()),
			'currentpattern' => $this->bingo_model->getRoomWinningPattern(get_set_id()),
			'winningpatternarray' => $this->bingo_model->getRoomWinningPatterConfig(get_set_id()),
			'clearText' => false,
			'sendnumber' => null
		);
		$this->load->view("admin/bingo/host",$data);
	}

	public function sendnumber($number){
		//if(set_exists())
		{
			$clearText = submit_number($number/*,get_number_cards()*/);
			$data  = array(
				'clearText' => $clearText,
				'sendnumber' => $number
			);
			/*$this->load->view("admin/bingo/host",$data);*/
			$this->session->set_flashdata("last",$data);
			redirect(base_url()."admin/bingo/host");
		}
		//else
		//	$this->index();
	}

	public function senddraw(){

		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST
		
		$number = test_input($this->input->post("n"));

		$clearText = submit_number($number/*,get_number_cards()*/);
		$data  = array(
			'response' => "Ready",
			'winners' => !empty(check_bingo_lite()), 
			'last_draws' => getLastDraws($clearText),
			'clear' => $clearText,
			'num' => $number
		);
		echo json_encode($data);
	}

	public function changepattern(){

		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST
		
		$number = test_input($this->input->post("pid"));
		$index = test_input($this->input->post("i"));

		setCurrentWinningPattern($number);
		$data  = array(
			'response' => "Ready",
			'id' => $number,
			'index' => $index
		);
		echo json_encode($data);
	}
	public function changedrawmode(){

		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST
		
		$index = test_input($this->input->post("i"));
		$current = test_input($this->input->post("ci"));

		$current = $current + $index;
		if($current >= sizeof($this->config->item('draw_modes')))
		{
			$current = 0;
		}
		if($current < 0)
		{
			$current = sizeof($this->config->item('draw_modes')) - 1;
		}

		//$this->config->set_item('current_draw_mode',$current);

		setCurrentDrawMode($current);
		$data  = array(
			'response' => "Ready",
			'index' => $current
		);
		echo json_encode($data);
	}
	public function store(){

		$this->outh_model->CSRFVerify();

		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST
	
		$name = $this->input->post("name");

		$this->form_validation->set_rules("name",$this->lang->line('admin_campaigns_table_name'),"required|is_unique[campaigns.name]");

		if ($this->form_validation->run()) {

			$data  = array(
				'name' => $name
			);

			if ($this->campaigns_model->save($data)) {
				redirect(base_url()."admin/bingo");
			}
			else{
				$this->session->set_flashdata("error",$this->lang->line('error_saving_data'));
				redirect(base_url()."admin/bingo/add");
			}
		}
		else{
			/*redirect(base_url()."mantenimiento/bingo/add");*/
			$this->add();
		}

		
	}

	public function edit($id){
		$data  = array(
			'campaign' => $this->campaigns_model->getCampaign($id), 
		);
		$this->load->view("admin/bingo/edit",$data);
	}

	public function update(){
		$this->outh_model->CSRFVerify();

		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		$idCampaign = $this->input->post("idCampaign");
		$name = $this->input->post("name");

		$actualcampaign = $this->campaigns_model->getCampaign($idCampaign);

		if ($name == $actualcampaign->name) {
			$is_unique = "";
		}else{
			$is_unique = "|is_unique[campaigns.name]";
		}

		$this->form_validation->set_rules("name",$this->lang->line('admin_campaigns_table_name'),"required".$is_unique);
		if ($this->form_validation->run()) {
			$data = array(
				'name' => $name, 
			);

			if ($this->campaigns_model->update($idCampaign,$data)) {
				redirect(base_url()."admin/bingo");
			}
			else{
				$this->session->set_flashdata("error",$this->lang->line('error_saving_data'));
				redirect(base_url()."admin/bingo/edit/".$idCampaign);
			}
		}else{
			$this->edit($idCampaign);
		}

		
	}

	/*public function view($id){
		$data  = array(
			'iframe' => $this->bingo_model->getRoomIframe(get_set_id()),
			'campaign' => $this->campaigns_model->getCampaign($id)
		);
		$this->load->view("admin/bingo/view",$data);
	}*/

	public function delete($id){
		$this->campaigns_model->remove($id);
		redirect(base_url()."admin/bingo");
	}

	/*public function getCampaigns(){
		$valor = $this->input->post("valor");
		$campaign = $this->campaigns_model->getCampaignsLike($valor);
		echo json_encode($campaign);
	}
	public function getCampaign(){
		$campaign = $this->campaigns_model->getCampaign($this->input->post("campaign_id"));
		//$valor = $this->input->post("valor");
		//$products = $this->pedidos_model->getProducts($valor);
		echo json_encode($campaign);
	}*/
}