<?php 

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class APIController extends ResourceController
{
    use ResponseTrait;

    public $Db;
    // public $session;
    // public $request;

    public function __construct(){

        $this->Db = \Config\Database::connect();
        $validation =  \Config\Services::validation();

        $this->session = \Config\Services::session();
        helper(['form']);
        $this->request = \Config\Services::request();
    }

    // ----------- START API FOR FLEXI GUEST --------------//

    // REGISTRATION API
    public function registerAPI()
        {
            $rules = [
                "name" => "required",
                "email" => "required|valid_email|is_unique[users.email]|min_length[6]|max_length[50]",
                "phone_no" => "required",
                "password" => 'required|min_length[8]|max_length[255]',
                "confirm_password" => 'required|matches[password]',
                
            ];

            $messages = [
                "name" => [
                    "required" => "Name is required"
                ],
                "email" => [
                    "required" => "Email required",
                    "valid_email" => "Email address is not in format",
                    "is_unique"=> "Email already Exist"
                ],
                "phone_no" => [
                    "required" => "Phone Number is required"
                ],
                "password" => [
                    "required" => "password is required"
                ],
                "confirm_password" => [
                    "matches" => "confirm password must be same as password"
                ],
            ];

            if (!$this->validate($rules, $messages)) {

                $response = [
                    'status' => 500,
                    'error' => true,
                    'message' => $this->validator->getErrors(),
                    'data' => []
                ];
            } else {

                $userModel = new UserModel();
                $email = $this->request->getVar("email");

                // check wheather the email is present in customer table
                $isCustomer_data = $this->Db->table('FLXY_CUSTOMER')->where('CUST_EMAIL', $email)->get()->getRowArray();

                if(empty($isCustomer_data)){

                    $response = [
                        'status' => 404,
                        "error" => false,
                        'messages' => 'Sorry , You are not Reserved any room.',
                        'data' => []
                    ];
                    echo json_encode($response);die;

                }

                $data = [
                    "name" => $this->request->getVar("name"),
                    "email" => $email,
                    "phone_no" => $this->request->getVar("phone_no"),
                    "password" => password_hash($this->request->getVar("password"), PASSWORD_DEFAULT),
                    "role" => "editor",
                ];

                
                if ($this->Db->table('tbl_users')->insert($data) ) {

                    $response = [
                        'status' => 200,
                        "error" => false,
                        'messages' => 'Successfully, user has been registered',
                        'data' => []
                    ];
                    

                } else {

                    $response = [
                        'status' => 500,
                        "error" => true,
                        'messages' => 'Failed to create user',
                        'data' => []
                    ];
                }
            }

            return $this->respondCreated($response);
        }
 

    private function getKey()
        {
            return "my_application_secret";
        }
    
    // login API
    public function loginAPI()
        {
            $rules = [
                "email" => "required|valid_email|min_length[6]",
                "password" => "required",
            ];
    
            $messages = [
                "email" => [
                    "required" => "Email required",
                    "valid_email" => "Email address is not in format"
                ],
                "password" => [
                    "required" => "password is required"
                ],
            ];
    
            if (!$this->validate($rules, $messages)) {
    
                $response = [
                    'status' => 500,
                    'error' => true,
                    'message' => $this->validator->getErrors(),
                    'data' => []
                ];
    
                return $this->respondCreated($response);
                
            } else {
                $userModel = new UserModel();

                $sql = "SELECT * FROM tbl_users WHERE email=:email:";
                $param = ['email'=> $this->request->getVar("email")];
                $userdata = $this->Db->query($sql,$param)->getRowArray();
                if (!empty($userdata)) {
    
                    if (password_verify($this->request->getVar("password"), $userdata['password'])) {
    
                        $key = $this->getKey();
    
                        $iat = time(); // current timestamp value
                        $nbf = $iat + 10;
                        $exp = $iat + 3600;
    
                        $payload = array(
                            "iss" => "The_claim",
                            "aud" => "The_Aud",
                            "iat" => $iat, // issued at
                            "nbf" => $nbf, //not before in seconds
                            "exp" => $exp, // expire time in seconds
                            "data" => $userdata,
                        );
    
                        $token = JWT::encode($payload, $key,'HS256');
    
                        $response = [
                            'status' => 200,
                            'error' => false,
                            'messages' => 'User logged In successfully',
                            'data' => [
                                'token' => $token
                            ]
                        ];
                        return $this->respondCreated($response);
                    } else {
    
                        $response = [
                            'status' => 500,
                            'error' => true,
                            'messages' => 'Incorrect details',
                            'data' => []
                        ];
                        return $this->respondCreated($response);
                    }
                } else {
                    $response = [
                        'status' => 500,
                        'error' => true,
                        'messages' => 'User not found',
                        'data' => []
                    ];
                    return $this->respondCreated($response);
                }
            }
        }

    public function profileAPI()
    {

            $key = $this->getKey();
            $authHeader = $this->request->getHeader("Authorization");
            $authHeader = $authHeader->getValue();
            $token = $authHeader;

            try {
                
                $decoded = JWT::decode($token, new Key($key, 'HS256'));
            
                if ($decoded) {

                    $response = [
                        'status' => 200,
                        'error' => false,
                        'messages' => 'User details',
                        'data' => [
                            'profile' => $decoded
                        ]
                    ];
                    return $this->respondCreated($response);
                }
            } catch (Exception $ex) {
            
                $response = [
                    'status' => 401,
                    'error' => true,
                    'messages' => 'Access denied',
                    'data' => []
                ];
                return $this->respondCreated($response);
            }
    }
    
    // for Logout Just delete the token from session or anystorage
    public function logoutApi()
    {
        # code...
    }

    /* 

    Function to List all reservations with details
    METHOD: GET
    INPUT : -
    OUTPUT : Reservation details like Reservation_no,checkin _date,checkout_date,|apartment_details,apartment no| ,status ,name ,night, adult,childern count, document uploaded or not
    
    */ 
    public function listReservationsAPI()
    {
        try {
            // get token from request and get the user details from it.

            $key = $this->getKey();
            $authHeader = $this->request->getHeader("Authorization");
            $authHeader = $authHeader->getValue();
            $token = $authHeader;

            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            if(!empty($decoded)) {
                
                $userid = $decoded->data->id;
                $cust_id = $decoded->data->USR_CUST_ID;

                echo $cust_id;die;

                if(!empty($cust_id)){

               
                    $param = ['RESV_NAME' => $cust_id];
                    $sql = "SELECT a.RESV_ID,a.RESV_NAME,a.RESV_CHILDREN,a.RESV_ADULTS,a.RESV_NIGHT,a.RESV_ARRIVAL_DT,a.RESV_DEPARTURE,a.RESV_STATUS FROM FLXY_RESERVATION a LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = a.RESV_NAME LEFT JOIN FLXY_DOCUMENTS c ON c.CUST_NAME = a.RESV_NAME WHERE RESV_NAME=:RESV_NAME:";
                    $data = $this->Db->query($sql,$param)->getResultArray();
                    if(!empty($data)){

                        $response = [
                            'status' => 200,
                            'error' => false,
                            'messages' => 'Reservation fetched Successfully',
                            'data' => [
                                'token' => $data
                            ]
                        ];
                        return $this->respondCreated($response);
                    }

                }else{

                    $response = [
                        'status' => 401,
                        'error' => true,
                        'messages' => 'No user details Found',
                        'data' => []
                    ];
                    return $this->respondCreated($response);

                }

            }

        
            

        }catch (Exception $ex) {
            
            echo json_encode($e->errors());
        }
    }

    // ----------- END API FOR FLEXIGUEST ----------------//

}