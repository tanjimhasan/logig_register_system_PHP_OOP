<?php
		include_once 'Session.php';
		include 'Database.php';
class User{

	private $db;
    public	function __construct(){

		$this->db = new Database();
	}
	// This method use to register the user

	public function userRegistration($data){

		$name     = $data['name'];
		$username = $data['username'];
		$email    = $data['email'];
		$password = $data['password'];
		

		$chk_email = $this->emailCheck($email);

		if ($name == "" OR $username == "" OR $email == "" OR $password == "") {
			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>Field Must not be Empty</div>";
			return $msg;
		}
		if (strlen($username)<3) {
			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>Username is too short</div>";
			return $msg;
		}

		if (filter_var($email,FILTER_VALIDATE_EMAIL) === false) {
			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>The Email address is not valid</div>";
			return $msg;
		}
		if ($chk_email == true) {
			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>The email address already Exist</div>";
			return $msg;
		}
		$password = md5($data['password']);
		$sql   = "INSERT INTO tbl_user (name,username,email,password) VALUES (:name,:username,:email,:password)";
		$query = $this->db->pdo->prepare($sql);
		$query->bindValue(':name',$name);
		$query->bindValue(':username',$username);
		$query->bindValue(':email',$email);
		$query->bindValue(':password',$password);
		$result = $query->execute();

		if ($result) {
			$msg = "<div class='alert alert-Success'><strong>Success ! </strong>Thank you, You have been registered</div>";
			return $msg;
		}else{
			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>Sorry! there has been problem inserting your details.</div>";
			return $msg;
		}

	}

	public function emailCheck($email){

		$sql   = "SELECT email FROM tbl_user WHERE email = :email";
		$query = $this->db->pdo->prepare($sql);
		$query->bindValue(':email',$email);
		$query->execute();
		if ($query->rowCount() > 0) {
			return true;
		}else{
			return false;
		}

	}

	public function getLoginUser($email,$password){

		$sql   = "SELECT * FROM tbl_user WHERE email = :email AND password = :password LIMIT 1";
		$query = $this->db->pdo->prepare($sql);
		$query->bindValue(':email',$email);
		$query->bindValue(':password',$password);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_OBJ);
		return $result;

	}

	public function userLogin($data){

		$email     = $data['email'];
		$password  = md5($data['password']);

		$chk_email = $this->emailCheck($email);

		if ($email == "" OR $password == "") {
			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>Field Must not be Empty</div>";
			return $msg;

		}

		if (filter_var($email,FILTER_VALIDATE_EMAIL) === false) {

			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>The Email address is not valid</div>";
			return $msg;
		}
		if ($chk_email == false) {
			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>The email address Not Exist</div>";
			return $msg;

		}

		$result = $this->getLoginUser($email,$password);
		if ($result) {
			Session::init();
			Session::set("login",true);
			Session::set("name",$result->name);
			Session::set("id",$result->id);
			Session::set("username",$result->username);
			Session::set("logimsg","<div class='alert alert-seccess'><strong>Success ! </strong>You are logged in</div>");
			header("Location: index.php");
		}else{
			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>Data not found</div>";
			return $msg;
		}

	}

	public function getUserData(){

		$sql    = "SELECT * FROM tbl_user ORDER BY id DESC";
		$query  = $this->db->pdo->prepare($sql);
		$query->execute();
		$result = $query->fetchAll();
		return $result;

	}

	public function getUserByID($id){

		$sql    = "SELECT * FROM tbl_user WHERE id = :id LIMIT 1";
		$query  = $this->db->pdo->prepare($sql);
		$query->bindValue(':id',$id);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_OBJ);
		return $result;

	}

	public function updateUserData($id,$data){

		$name     = $data['name'];
		$username = $data['username'];
		$email    = $data['email'];

		if ($name == "" OR $username == "" OR $email == "") {
			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>Field Must not be Empty</div>";
			return $msg;
		}
		$sql = "UPDATE tbl_user set

				name     = :name,
				username = :username,
				email    = :email
		WHERE   id       = :id";

		$query  = $this->db->pdo->prepare($sql);
		$query->bindValue(':name',$name);
		$query->bindValue(':username',$username);
		$query->bindValue(':email',$email);
		$query->bindValue(':id',$id);
		$result = $query->execute();

		if ($result) {
			$msg = "<div class='alert alert-Success'><strong>Success ! </strong>Thank you,Your Data is updated Successfully</div>";
			return $msg;
		}else{
			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>Sorry! there has been problem updating your details.</div>";
			return $msg;
		}

	}

	public function checkPassword($id,$old_pass){

		$password = md5($old_pass);
		$sql      = "SELECT password FROM tbl_user WHERE id = :id AND password = :password";
		$query    = $this->db->pdo->prepare($sql);
		$query->bindValue(':id',$id);
		$query->bindValue(':password',$password);
		$query->execute();
		if ($query->rowCount() > 0) {
			return true;
		}else{
			return false;
		}

	}

	public function updatePassword($id,$data){
		
		$old_pass = $data['old_pass'];
		$new_pass = $data['password'];

		if ($old_pass =="" OR $new_pass == "") {
			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>Field Must Not be Empty.</div>";
			return $msg;
		}
		$checkpass = $this->checkPassword($id,$old_pass);

		if ($checkpass == false) {
			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>Your given Old password is wrong</div>";
			return $msg;
		}

		if (strlen($new_pass)<6) {
			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>Your Given new Password is too short.</div>";
			return $msg;
		}
		$password = md5($new_pass);
		$sql = "UPDATE tbl_user set

				password = :password
		WHERE   id       = :id";

		$query = $this->db->pdo->prepare($sql);

		$query->bindValue(':password',$password);
		$query->bindValue(':id',$id);
		$result = $query->execute();

		if ($result) {
			$msg = "<div class='alert alert-Success'><strong>Success ! </strong>Password Updated Successfully</div>";
			return $msg;
		}else{
			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>Sorry! there has been problem updating your Password.</div>";
			return $msg;
		}
	}

}
?>