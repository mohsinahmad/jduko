<?phpdefined('BASEPATH') OR exit('No direct script access allowed');class Users extends CI_Controller{    function __construct()    {        parent::__construct();        if(!($this->session->userdata('in_use'))){            redirect('login','refresh');        }        $this->load->model('UserModel');        $this->load->model('AccessModel');        $this->load->library('form_validation');        $this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');    }    public function index($module)    {        $data['users'] = $this->UserModel->get_all_users();        if($module == 'Store'){            $data['type'] = 'yes';        }        else{            $data['type'] = 'no';        }        $this->load->view($module.'/header');        $this->load->view('user/index', $data);        $this->load->view($module.'/footer');        $this->load->view('user/userJs');    }    public function UpdatePassword($module)    {        $data['users'] = $this->UserModel->get_all_users();        $this->load->view($module.'/header');        $this->load->view('user/updateuser', $data);        $this->load->view($module.'/footer');        $this->load->view('user/userJs');    }    public function saveUser($module)    {        $this->form_validation->set_rules('username', 'User Name', 'trim|required|is_unique[users.UserName]',            array(                'required'      => 'آپ  نے رکن کا نام فراہم نہیں کیا ہے',                'is_unique'     => 'یہ  رکن کا نام پہلے سے موجود ہے')        );        $this->form_validation->set_rules('pass', 'Password', 'trim|required',            array(                'required'      => 'آپ نے  پاس ورڈ مہیا نہیں کیا ہے.')        );        $this->form_validation->set_rules('user_type','user_type','trim|required');        $this->form_validation->set_rules('cpass', 'Confirm Password', 'trim|required|matches[pass]',            array(                'required'      => 'آپ نے تصدیق پاس ورڈ مہیا نہیں کیا ہے',                'matches'		=> 'پاس ورڈ میچ نہیں ھوا')        );////        echo '<pre>';//        print_r($_POST);//        exit();        if($this->form_validation->run() == TRUE) {            $check = $this->UserModel->save_user();            if($check){                $this->session->set_flashdata('success',"رکن کامیابی سے شامل کردیا گیا ہے");                redirect('Users/index/'.$module,'refresh');            }        }else{            $data['users'] = $this->UserModel->get_all_users();            $this->load->view($module.'/header');            $this->load->view('user/index', $data);            $this->load->view($module.'/footer');            $this->load->view('user/userJs');        }    }    public function getUser($id)    {        $user = $this->UserModel->get_user($id);        $data = array('username' => $user[0]->UserName,            'status' => $user[0]->Locked );        echo json_encode($data);    }    public function UpdateUserId()    {        $this->form_validation->set_rules('username', 'User Name', 'trim|required',            array(                'required' => 'آپ  نے رکن نام فراہم نہیں کیا ہے')        );        $this->form_validation->set_rules('password', 'Password', 'trim',            array(                'required' => 'آپ نے  پاس ورڈ مہیا نہیں کیا ہے.')        );        $this->form_validation->set_rules('confpass', 'Confirm Password', 'trim|matches[password]',            array(                'matches' => 'پاس ورڈ میچ نہیں ھوا')        );        if($this->form_validation->run() == TRUE) {            $check = $this->UserModel->update_user_id();            if($check){                $response= array('success' => "ok");}            else{                $response=array('error' =>"ok");            }            echo json_encode($response);        }else{            $this->form_validation->set_error_delimiters('','');            $response = array('form_error' => "ok" ,                'username' => form_error('username'),                // 'password' => form_error('password'),                'confpass' => form_error('confpass') );            echo json_encode($response);        }    }    public function updateuser($id)    {        $this->form_validation->set_rules('username', 'User Name', 'trim|required',            array(                'required' => 'آپ  نے صارف نام فراہم نہیں کیا ہے')        );        $this->form_validation->set_rules('password', 'Password', 'trim',            array(                'required' => 'آپ نے  پاس ورڈ مہیا نہیں کیا ہے.')        );        $this->form_validation->set_rules('confpass', 'Confirm Password', 'trim|matches[password]',            array(                'matches'=> 'پاس ورڈ میچ نہیں ھوا')        );        if($this->form_validation->run() == TRUE) {            $check = $this->UserModel->update_user($id);            if($check){                $response= array('success' => "ok");}            else{                $response=array('error' =>"ok");            }            echo json_encode($response);        }else{            $this->form_validation->set_error_delimiters('','');            $response = array('form_error' => "ok" ,                'username' => form_error('username'),                // 'password' => form_error('password'),                'confpass' => form_error('confpass') );            echo json_encode($response);        }    }    public function updateStatus($id)    {        $check = $this->UserModel->update_status($id);        if($check){            $response = array('success' => "ok");        }else{            $response = array('error' => "ok");        }        echo json_encode($response);    }    public function userDelete($id)    {        $check_access = $this->AccessModel->delete_access($id);        $check = $this->UserModel->delete_user($id);        if($check && $check_access){            $response= array('success' => "ok");}        else {            $response = array('error' => "ok");        }        echo json_encode($response);    }    public function get_type(){       $result =  $this->db->get('category_type')->result();        echo json_encode($result);    }}