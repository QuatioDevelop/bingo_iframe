
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends CI_Controller {

	public function __construct(){
		parent::__construct();
		//$this->permissions = $this->backend_lib->control();
		$this->load->model('backend_model');
		$this->load->model("clientes_model");
		$this->load->model("chat_model");
		$this->load->helper('string');
		$this->db->query('SET SESSION sql_mode =
                  REPLACE(REPLACE(REPLACE(
                  @@sql_mode,
                  "ONLY_FULL_GROUP_BY,", ""),
                  ",ONLY_FULL_GROUP_BY", ""),
                  "ONLY_FULL_GROUP_BY", "")');
	}
	public function index()
	{
		$data['strTitle']='';
		$data['strsubTitle']='';
		$list=[];
		//if($this->session->userdata['Admin']['role'] == 'Client_cs'){
			$list = $this->clientes_model->getClients(get_set_id());
			$data['strTitle']='Todos';
			$data['strsubTitle']='Personas';
			$data['chatTitle']='Seleccione con quién Hablar';
		/*}else{
			$list = $this->clientes_model->ClientsListCs();
			$data['strTitle']='All Connected Clients';
			$data['strsubTitle']='Clients';
			$data['chatTitle']='Select Client with Chat';
 
		}*/
		$vendorslist=[];
		foreach($list as $u){
			$vendorslist[]=
			[
				'id' => $this->outh_model->Encryptor('encrypt', $u->user_name),
				'name' => $u->name,
				'picture_url' => base_url()."assets/template/img/avatars/default-user-image.png",
			];
		}
		$data['vendorslist']=$vendorslist;
		$this->backend_model->setUserPlace($this->session->userdata('user_data')['user_uname'],"support");
		$this->load->view("dashboard/support", $data);
		/*$data  = array(
			'users' => $this->clientes_model->getUsers(), 
			'u_permissions' => $this->permissions 
		);
		$this->load->view("admin/usuarios/list",$data);*/
		
	}

	public function support()
	{
		//$this->getGraphData();
		if($this->session->userdata('user_data')['user_role'] == 1)
		{
			$data  = array(
				'iframe' => $this->bingo_model->getRoomIframe(get_set_id()),
				/*'clients' => $this->clientes_model->getClients(), 
				'users' => $this->users_model->getUsers(), 
				'campaigns' => $this->campaigns_model->getCampaigns(), */
				'permissions' => $this->permissions 
			);	
		}else
		{
			$data  = array(
				'iframe' => $this->bingo_model->getRoomIframe(get_set_id()),
				//'users' => $this->users_model->getClientCampaigns($this->session->userdata('user_data')['user_uname']), 
				//'campaigns' => $this->clientes_model->getClientCampaigns($this->session->userdata('user_data')['user_uname']), 
				'permissions' => $this->permissions 
			);
		}
		$this->backend_model->setUserPlace($this->session->userdata('user_data')['user_uname'],"support");
		$this->load->view("dashboard/support", $data);
		/*$data  = array(
			'users' => $this->clientes_model->getUsers(), 
			'u_permissions' => $this->permissions 
		);
		$this->load->view("admin/usuarios/list",$data);*/
		
	}

	public function send_message(){
		$post = $this->input->post();
		$messageTxt='NULL';
		$attachment_name='';
		$file_ext='';
		$mime_type='';/**/
		
		if(isset($post['type'])=='Attachment'){ 
		 	$AttachmentData = $this->ChatAttachmentUpload();
			//print_r($AttachmentData);
			$attachment_name = $AttachmentData['file_name'];
			$file_ext = $AttachmentData['file_ext'];
			$mime_type = $AttachmentData['file_type'];
			 
		}else{
			$messageTxt = reduce_multiples($post['messageTxt'],' ');
		}	
		 
				$data=array(
 					'sender_id' => $this->session->userdata('user_data')['user_uname'],
					'receiver_id' => $this->outh_model->Encryptor('decrypt', $post['receiver_id']),
					'message' =>  $messageTxt,
					'attachment_name' => $attachment_name,
					'file_ext' => $file_ext,
					'mime_type' => $mime_type,
					'message_date_time' => date('Y-m-d H:i:s'), //23 Jan 2:05 pm
					'ip_address' => $this->input->ip_address()
				);
		  
 				$query = $this->chat_model->SendTxtMessage($this->outh_model->xss_clean($data)); 
 				$response='';
				if($query == true){
					$response = ['status' => 1 ,'message' => '' ];
				}else{
					$response = ['status' => 0 ,'message' => 'sorry we re having some technical problems. please try again !'];
				}
             //$response = ['status' => 1 ,'message' => '' ];

				//$response ="sisas: ".$post['messageTxt'];
 		   echo json_encode($response);
	}
	public function ChatAttachmentUpload(){
		 
		
		$file_data='';
		if(isset($_FILES['attachmentfile']['name']) && !empty($_FILES['attachmentfile']['name'])){	
				$config['upload_path']          = './uploads/attachment';
				$config['allowed_types']        = 'jpeg|jpg|png|txt|pdf|docx|xlsx|pptx|rtf';
				//$config['max_size']             = 500;
				//$config['max_width']            = 1024;
				//$config['max_height']           = 768;
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('attachmentfile'))
				{
					echo json_encode(['status' => 0,
					'message' => '<span style="color:#900;">'.$this->upload->display_errors(). '<span>' ]); die;
				}
				else
				{
					$file_data = $this->upload->data();
					//$filePath = $file_data['file_name'];
					return $file_data;
				}
		    }
 		 
	}

	public function get_chat_history_by_vendor(){
		$receiver_id = $this->outh_model->Encryptor('decrypt', $this->input->get('receiver_id') );
		
		$Logged_sender_id = $this->session->userdata('user_data')['user_uname'];
		 
		$history = $this->chat_model->GetReciverChatHistory($receiver_id);
		//print_r($history);
		foreach($history as $chat):
			
			$message_id = $this->outh_model->Encryptor('encrypt', $chat['id']);
			$sender_id = $chat['sender_id'];
			$userName = $this->clientes_model->getClient($chat['sender_id'])->name;
			$userPic = base_url()."assets/template/img/avatars/default-user-image.png";//$this->clientes_model->PictureUrlById($chat['sender_id']);
			
			$message = $chat['message'];
			$messagedatetime = date('d M H:i A',strtotime($chat['message_date_time']));
			
 		?>
        	<?php
				$messageBody='';
            	if($message=='NULL'){ //fetach media objects like images,pdf,documents etc
					$classBtn = 'right';
					if($Logged_sender_id==$sender_id){$classBtn = 'left';}
					
					$attachment_name = $chat['attachment_name'];
					$file_ext = $chat['file_ext'];
					$mime_type = explode('/',$chat['mime_type']);
					
					$document_url = base_url('uploads/attachment/'.$attachment_name);
					
				  if($mime_type[0]=='image'){
 					$messageBody.='<img src="'.$document_url.'" onClick="ViewAttachmentImage('."'".$document_url."'".','."'".$attachment_name."'".');" class="attachmentImgCls">';	
				  }else{
					$messageBody='';
					 $messageBody.='<div class="attachment">';
                          $messageBody.='<h4>Attachments:</h4>';
                           $messageBody.='<p class="filename">';
                            $messageBody.= $attachment_name;
                          $messageBody.='</p>';
        
                          $messageBody.='<div class="pull-'.$classBtn.'">';
                            $messageBody.='<a download href="'.$document_url.'"><button type="button" id="'.$message_id.'" class="btn btn-primary btn-sm btn-flat btnFileOpen">Open</button></a>';
                          $messageBody.='</div>';
                        $messageBody.='</div>';
					}
						
											
				}else{
					$messageBody = $message;
				}
			?>
            
            
        
             <?php if($Logged_sender_id!=$sender_id){?>     
                  <!-- Message. Default to the left -->
                    <div class="direct-chat-msg">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left"><?=$userName;?></span>
                        <span class="direct-chat-timestamp pull-right"><?=$messagedatetime;?></span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <img class="direct-chat-img" src="<?=$userPic;?>" alt="<?=$userName;?>">
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                         <?=$messageBody;?>
                      </div>
                      <!-- /.direct-chat-text -->
                      
                    </div>
                    <!-- /.direct-chat-msg -->
			<?php }else{?>
                    <!-- Message to the right -->
                    <div class="direct-chat-msg right">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-right"><?=$userName;?></span>
                        <span class="direct-chat-timestamp pull-left"><?=$messagedatetime;?></span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <img class="direct-chat-img" src="<?=$userPic;?>" alt="<?=$userName;?>">
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                      	<?=$messageBody;?>
                          	<!--<div class="spiner">
                             	<i class="fa fa-circle-o-notch fa-spin"></i>
                            </div>-->
                       </div>
                       <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->
             <?php }?>
        
        <?php
		endforeach;
 		
	}
	public function chat_clear_client_cs(){
		$receiver_id = $this->outh_model->Encryptor('decrypt', $this->input->get('receiver_id') );
		
		$messagelist = $this->chat_model->GetReciverMessageList($receiver_id);
		
		foreach($messagelist as $row){
			
			if($row['message']=='NULL'){
				$attachment_name = unlink('uploads/attachment/'.$row['attachment_name']);
			}
 		}
		
		$this->chat_model->TrashById($receiver_id); 
 
 		
	}
}
