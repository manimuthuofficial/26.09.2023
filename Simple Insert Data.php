Simple Insert Data:
-------------------

View : user.php

<form method="post" action="<?php echo base_url('user/insertUser'); ?>">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" />

    <label for="email">Email:</label>
    <input type="text" name="email" id="email" />

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" />

    <input type="submit" value="Submit" />
</form>

Model : UserModel.php

<?php
class UserModel extends CI_Model 
{

	public function __construct() 
	{
        parent::__construct();
        
        // Load the database library
        $this->load->database();
    }

    public function insertUser($data) 
	{
        $this->db->insert('users', $data);
        return $this->db->insert_id(); // Return the ID of the inserted user
    }
}

Controller: UserController.php

<?php
class UserController extends CI_Controller {

    public function __construct() 
	{
        parent::__construct();
        $this->load->model('UserModel');
    }
	
	public function index() 
	{
        // Load a view with a form
        $this->load->view('user'); //User registration form
    }

    public function insertUser() 
	{
        // Collect user data from the form into an array
        $data = [
            'username' => $this->input->post('username'),
            'email'    => $this->input->post('email'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
        ];

        // Insert the user data into the database
        $user_id = $this->UserModel->insertUser($data);

        if ($user_id) 
		{
            // User inserted successfully, you can redirect to a success page
        } 
		else 
		{
            // Handle the insertion error
        }
    }
}


Routes : Form action using route 

$route['user/insertUser'] = 'UserController/insertUser';
<form method="post" action="<?php echo base_url('user/insertUser'); ?>">

Form action using ControllerName and MethodName

Using ControllerName/methodName
<form method="post" action="<?php echo base_url('UserController/insertUser'); ?>">


Simple Insert Data - 2
----------------------
Model :
<?php
class UserModel extends CI_Model 
{

    public function __construct() 
    {
        parent::__construct();		    
        // Load the database library
        $this->load->database();
    }

    public function insertUser($data) 
    {
        if ($this->db->insert('users', $data)) 
		{
            // Insertion was successful
            return true;
        } 
		else 
		{
            // Insertion failed
            return false;
        }
    }
}
?>

Controller:
public function insertUser() 
{
    // Check if the request method is POST
    if ($this->input->server('REQUEST_METHOD') === 'POST') 
	{
        // Collect user data from the form into an array
        $data = [
            'username' => $this->input->post('username'),
            'email'    => $this->input->post('email'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
        ];

        // Attempt to insert the user data into the database
        if ($this->UserModel->insertUser($data)) 
		{
            // User inserted successfully, you can redirect to a success page
            redirect('user/success');
			OR
			// User inserted successfully, load the success view
            $this->load->view('success_message');
			OR
			// Set a success flashdata message
            $this->session->set_flashdata('success_message', 'User has been successfully inserted.');
        } 
		else 
		{
            // Handle the insertion error, you can redirect to an error page
            redirect('user/error');
			OR
			// User insertion error, load the success view
            $this->load->view('success_message');
			OR
			// Set a failure flashdata message
            $this->session->set_flashdata('error_message', 'Failed to insert the user.');
        }
    } 
	else 
	{
        // If the request method is not POST, show an error or redirect
        redirect('user/error');
    }
}

Both Flashdata & Redirect in Controller :

public function insertUser() 
{
    // Check if the request method is POST
    if ($this->input->server('REQUEST_METHOD') === 'POST') 
	{
        // Collect user data from the form into an array
        $data = [
            'username' => $this->input->post('username'),
            'email'    => $this->input->post('email'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
        ];

        // Attempt to insert the user data into the database
        if ($this->UserModel->insertUser($data)) 
		{
            // Set a success flashdata message
            $this->session->set_flashdata('success_message', 'User has been successfully inserted.');
            // Redirect to the success view
            redirect('user/success');
        } 
		else 
		{
            // Set a failure flashdata message
            $this->session->set_flashdata('error_message', 'Failed to insert the user.');
            // Redirect to the error view
            redirect('user/error');
        }
    } 
	else 
	{
        // If the request method is not POST, show an error or redirect
        redirect('user/error');
    }
}


Show Flashdata Message :

<form method="post" action="<?php echo base_url('user/insertUser'); ?>">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" />

    <label for="email">Email:</label>
    <input type="text" name="email" id="email" />

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" />

    <input type="submit" value="Submit" />
</form>

<?php if ($this->session->flashdata('success_message')): ?>
    <div class="alert alert-success">
        <?php echo $this->session->flashdata('success_message'); ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error_message')): ?>
    <div class="alert alert-danger">
        <?php echo $this->session->flashdata('error_message'); ?>
    </div>
<?php endif; ?>

"Display a success message along with the  user's ID :"
// application/controllers/UserController.php
<?php
class UserController extends CI_Controller 
{

    public function __construct() 
	{
        parent::__construct();
        $this->load->model('UserModel');
    }

    public function register() 
	{
        // Check if the request method is POST
        if ($this->input->server('REQUEST_METHOD') === 'POST') 
		{
            // Collect user data from the form into an array
            $data = [
                'username' => $this->input->post('username'),
                'email'    => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            ];

            // Attempt to insert the user data into the database
            $user_id = $this->UserModel->insertUser($data);

            if ($user_id) 
			{
                // Set a success flash message with the user's ID
                $this->session->set_flashdata('success_message', 'Registration successful. Your user ID is ' . $user_id);
            } 
			else 
			{
                // Set an error flash message
                $this->session->set_flashdata('error_message', 'Registration failed. Please try again.');
            }
        }

        // Redirect back to the registration form page
        redirect('user/registration_form');
    }

}
?>

To automatically populate the user type dropdown in the user registration form with values from another table in CodeIgniter, you can follow these steps:

// application/models/UserTypeModel.php
class UserTypeModel extends CI_Model 
{
    public function getUserTypes() 
    {
        $query = $this->db->get('user_types');
        return $query->result();
    }
}

// application/controllers/UserController.php
class UserController extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('UserTypeModel');
    }

    public function registration_form() 
    {
        // Load user types from the model
        $data['user_types'] = $this->UserTypeModel->getUserTypes();

        // Load the registration form view and pass user types as data
        $this->load->view('user_registration', $data);
    }

    // ... other controller functions ...
}

<!-- application/views/user_registration.php -->
<form method="post" action="<?php echo base_url('user/register'); ?>">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" />

    <label for="email">Email:</label>
    <input type="text" name="email" id="email" />

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" />

    <label for="user_type">User Type:</label>
    <select name="user_type" id="user_type">
        <?php foreach ($user_types as $type): ?>
            <option value="<?php echo $type->id; ?>"><?php echo $type->type_name; ?></option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="Register" />
</form>
