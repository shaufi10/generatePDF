<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Mail_new;
use App\Book;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\JobSeeker;
use App\Model\JobQuery;
use App\Model\JobSeekerDoc;
use App\Model\JobSeekerFB;
use DateTime;
use Illuminate\Support\Facades\Mail;
use App\Model\JobVacancy;
use App\Model\TokenSeeker;

class UserController extends Controller {
    public function forgot(Request $request){
        do {
            $token_key = str_random(10);
        } while (JobSeeker::where("token_key", "=", $token_key)->first() instanceof JobSeeker);
        $job_seeker=JobSeeker::where('email', $request->email)
            ->update(['token_key' => $token_key]);
        $name=JobSeeker::where('email', $request->email)->first(['name']);;
        if ($job_seeker) {

            $url = 'https://api.sendgrid.com/';
            $user = env('MAIL_USERNAME');
            $pass = env('MAIL_PASSWORD');

            $json_string = array(
                'to' => array(
                    $request->email
                ),
                'category' => 'test_category'
            );

            $url_request_new=env('url_changepass').$token_key;
            $html="Hi,". $name->name."<br>
            Anda baru saja meminta untuk memperbaharui password login untuk Rob’s Jobs<br>
            Klik link dibawah ini mengganti password anda<br><br>".
            $url_request_new."<br>
            Apabila permintaan ini bukan dari Anda, silahkan abaikan email ini dan password anda tidak akan berubah<br><br>
            Terima Kasih,<br>
            Tim Rob’s Jobs";
            $params = array(
                'api_user' => $user,
                'api_key' => $pass,
                'x-smtpapi' => json_encode($json_string),
                'to' => $request->email,
                'subject' => 'Your Password Reset Link',
                'html' => $html,
                'fromname' => "robsjobs",
                'from' => env('MAIL_FROM_EMAIL'),
            );


            $request = $url . 'api/mail.send.json';
            $client = new \GuzzleHttp\Client();

            $response = $client->post($request, [
                'form_params' => $params
            ]);
            return ['status' =>$response->getStatusCode()];


        }
        else{
            return $this->error("User not exist", 400);
        }
    }
    public function change_password(Request $request,$token){
        if ($request->password==$request->passwordconfirmation){
            $job_seeker=JobSeeker::where('token_key', $token)
                ->update(['password' => $request->password,'token_key'=>'']);
            if (!$job_seeker){
                return $this->error("Token Key is wrong or no longer available", 400);
            }
        }
        else{
            return $this->error("Wrong Password Confirmation", 400);
        }
    }
	public function signup(Request $request) {
		$email = trim($request->input('email'));
		$name = trim($request->input('name'));
		$mobile_no = trim($request->input('mobile_no'));
		$password = $request->input('password');
		// TODO validate input updateProfile

		if ($email == null || strlen($email) <= 0) {
			return $this->error("No email is inputted", 400);
		}

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return $this->error("Invalid email", 400);
		}

		if ($name == null || strlen($name) <= 0) {
			return $this->error("No name is inputted", 400);
		}
		if ($mobile_no == null || strlen($mobile_no) <= 0) {
			return $this->error("No mobile number is inputted", 400);
		}

		if(!preg_match("/^([+]?\d{1,3}[- ]?)?\d{10}$/", $mobile_no)) {
			return $this->error("Invalid mobile number", 400);
		}

		if ($password == null) {
			return $this->error("No password is inputted", 400);
		}

		// after validation, then
		$job_seeker = JobSeeker::where('email',$email)->first();
		if ($job_seeker == NULL) {
			$job_seeker = JobSeeker::where('mobile_number',$mobile_no)->first();
			if ($job_seeker == NULL) {
				$job_seeker = new JobSeeker;
				$job_seeker -> name = $name;
				$job_seeker -> mobile_number = $mobile_no;
				$job_seeker -> email = $email;
				$job_seeker -> password = $password;
				$dt = new DateTime;
				$job_seeker -> created = $dt->format('Y-m-d H:i:s');
				$job_seeker -> n_status = 1;
				$job_seeker -> save();
				unset($job_seeker->password);
				unset($job_seeker->n_status);
				return ['data' => $job_seeker];
			} else {
				return $this->error("Mobile number has already been registered", 400);
			}
		} else {
			return $this->error("Email has already been registered", 400);
		}
	}

	public function login(Request $request) {
		$email = trim($request->input('email'));
		$password = $request->input('password');
		// TODO validate input updateProfile

		if ($email == null || strlen($email) <= 0) {
			return $this->error("No email/mobile number is inputted", 400);

		}

		if(preg_match('/^[+]?\d+$/',$email)) {

			// after validation, then
			$job_seeker = JobSeeker::where('mobile_number',$email)->first();
			if ($job_seeker != NULL) {
				// simple password validation
				if (strcmp($password,$job_seeker->password) == 0) {
					return ['data' => array('id' => $job_seeker->id, 'updated' => $job_seeker->updated, )];
				} else {
					return $this->error("Invalid password", 400);
				}
			} else {
				return $this->error("mobile_number has not been registered", 400);
			}

		} else {

			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				return $this->error("Invalid email", 400);
			}

			// after validation, then
			$job_seeker = JobSeeker::where('email',$email)->first();
			if ($job_seeker != NULL) {
				// simple password validation
				if (strcmp($password,$job_seeker->password) == 0) {
					return ['data' => array('id' => $job_seeker->id, 'updated' => $job_seeker->updated, )];
				} else {
					return $this->error("Invalid password", 400);
				}
			} else {
				return $this->error("Email has not been registered", 400);
			}


		}

	}



	public function getDocs($userid) {
		$docs = JobSeekerDoc::where([['seeker_id', $userid],['n_status',1]])->get();
		return ['data' => $docs];
	}
	public function deleteDoc(Request $request) {
		$docid = $request->input('docid');
		$userid = $request->input('userid');
		$onedoc = JobSeekerDoc::where([['seeker_id', $userid],['id', $docid],['n_status',1]])->first();
		if ($onedoc == NULL) {
			return $this->error("Invalid document", 400);
		}
		$onedoc -> n_status = 0;
		$onedoc -> save();
		return ['data' => $onedoc -> id];
	}

	public function uploadDoc(Request $request) {
		$userid = intval($request->input('userid'));
        $job_seeker = JobSeeker::find($userid);
        if ($job_seeker == NULL ) {
			return $this->error("Invalid userid",400);
		}
		$doctype = intval($request->input('doctype'));
    $docfilename = $request->input('filename');
		$doctypename = '';
		$destinationPath = '';
		$docUrl = '';
		switch ($doctype) {
			case 1:
				$destinationPath = env('PUBLIC_PATH','') . 'certs/';
				$docUrl = env('CERT_URL','');
				$doctypename = 'certificate';
				break;
			case 2:
				$destinationPath = env('PUBLIC_PATH','') . 'docs/';
				$docUrl = env('DOC_URL','');
				$doctypename = 'document';
				break;
			default:
				return $this->error("Invalid doctype",400);
				break;
		}
        if ($request->hasFile('docfile')) {
        	if ($request->file('docfile')->isValid()) {
        		$userid = $request->input('userid');
				$path = $request->docfile->path();
				$extension = $request->docfile->extension();
				//$getClientOriginalName = $request->filename->getClientOriginalName();
				$newfilename = md5("doc#{$userid}" . time()) . '.' . $extension;
				//$newfilename = md5_file($path) . $extension;
				$request->docfile->move($destinationPath, $newfilename);
				$newdoc = new JobSeekerDoc;
				$newdoc -> seeker_id = $userid;
				$newdoc -> doc_type = $doctype;
				$newdoc -> doc_path = $docUrl . $newfilename;
				$newdoc -> n_status = 1;
				$dt = new DateTime;
				$created = $dt->format('Y-m-d H:i:s');
				$newdoc -> created = $created;
        $newdoc -> filename = $docfilename;
				$newdoc -> save();
				$newdoc -> userid = $userid;
				$newdoc -> type = $doctypename;
				unset($newdoc -> seeker_id);
				unset($newdoc -> n_status);

				return ['data' => $newdoc];
			} else {
				return $this->error("Uploaded file is invalid",400);
			}
		} else {
			return $this->error("No file uploaded",400);
		}
	}

	public function uploadPhoto(Request $request) {
		$userid = intval($request->input('userid'));
		$job_seeker = JobSeeker::find($userid);
		if ($job_seeker == NULL) {
			return $this->error("Invalid userid",400);
		}
        if ($request->hasFile('photo')) {
        	if ($request->file('photo')->isValid()) {
        		$userid = $request->input('userid');
				$path = $request->photo->path();
				$extension = $request->photo->extension();
				//$getClientOriginalName = $request->filename->getClientOriginalName();
				$newfilename = md5("prof_pic#{$userid}") . '.' . $extension;
				//$newfilename = md5_file($path) . $extension;
				$destinationPath = env('PUBLIC_PATH','') . 'photos/';
				$request->photo->move($destinationPath, $newfilename);
				$job_seeker->image = env('PHOTO_URL','') . $newfilename;
				$job_seeker->save();
				return ['data' => array('photo' => $job_seeker->image)];
			} else {
				return $this->error("Uploaded file is invalid",400);
			}
		} else {
			return $this->error("No file uploaded",400);
		}
	}
	public function fcm($token,$data){
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids' => [$token] ,
            'data' => $data
        );
        $headers = array(
            'Authorization:key=AAAAlI5W9ok:APA91bEXD2pWZsFyex-Cs7MuRh_trlIzrTN1mIUIh97qZucIdEvVN7oaHkQdcY2SWnTvNHuWnuzuq4C1My6jrQr6vi70u_wSfeEjX-7PgRqkIFvqO37_qweM0KsPYTsp67I0Hw-6mi5208yl7Mg0chZcfUxCXVmVaA',
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);

        return $result;
    }
    public function send_notification (Request $request)
    {
        $job_data=JobVacancy::find($request->input('job_id'));
        return $this->fcm($request->input('token_key'),$job_data);

    }
	public function sendfcmtoalldevice($userid,$jobid){
        $user_token= TokenSeeker::where('job_seeker_id',$userid)->get();
        $job_data=JobVacancy::find($jobid);
        $result=[];
        foreach ($user_token as $user){
            array_push($result,json_decode($this->fcm($user->token_device,$job_data)));
        }
        return json_encode($result);
    }
    public function saveToken(Request $request){
        $job_seeker = JobSeeker::find($request->input('userid'));
        if ($job_seeker != NULL) {
            try {
                $token=new TokenSeeker;
                $token->job_seeker_id=$job_seeker->id;
                $token->token_device=$request->input('token_device');
                $token->save();
                return ['data' => $job_seeker];
            }
            catch(\Exception $e){

                return $this->error($e->getMessage(), 400);
            }

        } else {
            return $this->error("Invalid userid", 400);
        }
    }
    public function deleteToken(Request $request){
        try{
            $deletedrows=TokenSeeker::where('token_device',$request->input('token_device'))->delete();
            return ['data' => $deletedrows];
        }catch(Exception $e){
            return $this->error($e->getMessage(), 400);
        }
    }
	public function getProfile($userid) {
		$job_seeker = JobSeeker::find($userid);
		if ($job_seeker == NULL) {
			return $this->error("Invalid userid",400);
		}
		$job_query = JobQuery::find($userid);
		if ($job_query == NULL) {
			$job_seeker -> birthdate = $this -> reorder_birthdate($job_seeker -> birthdate);
			unset($job_seeker->created);
			unset($job_seeker->n_status);
			unset($job_seeker->password);
			unset($job_seeker->security_question);
			unset($job_seeker->security_answer);
			$job_seeker->is_employed = 0;
			return ['data' => $job_seeker];
		}
		$job_query->id = $job_seeker->id;
		unset($job_query->seeker_id);
		unset($job_query->created);
		unset($job_query->n_status);
		$job_query->email = $job_seeker->email;
		$job_query->name = $job_seeker->name;
		$job_query->mobile_number = $job_seeker->mobile_number;
		if ($job_query->bio == null) {
			$job_query->bio = '';
		}
		$job_query->image = $job_seeker->image;
    //$job_query->experience = $job_seeker->experience;
		return ['data' => $job_query];
	}

	public function updateProfile(Request $request) {
		$user_id = $request->input('userid');
		$name = $request->input('name');
		$birthdate = $request->input('birthdate');
		$city = $request->input('city');
		$province = $request->input('province');
    $country =  $request->input('country','');
		$salary_min = $request->input('salarymin');
		$salary_max = $request->input('salarymax');
		$edu_level = $request->input('edulevel');
    $jurusan = $request->input('jurusan');
    $kompetensi = $request->input('kompetensi');
		$interests = $request->input('interests');
		$employment_type = $request->input('emptype');
		$employment_sector = $request->input('empsector');
		$distance = $request->input('distance');
		$skills = $request->input('skills');
		$bio = $request->input('bio', '');
		$curr_employment_sector = $request->input('currentsector','');
    $is_employed = $request->input('isemployed',0);
    $has_work_experience = $request->input('hasexperience',0);
    $portofolio = $request->input('portofolio','');
    $experience = $request->input('experience','');
    $emp_status = $request->input('emp_status','');
    if($portofolio==''){
        $hasportofolio=0;
    }
    else{
        $hasportofolio=1;
    }

		// TODO validate input updateProfile

		// after validation, then
		$job_seeker = JobSeeker::find($user_id);
		if ($job_seeker != NULL) {
			// save data into job_seeker
			$job_seeker -> name = trim($name);
			$job_seeker -> birthdate = trim($birthdate);
			$job_seeker -> save();

			// save data into job_query
			$job_query = JobQuery::find($user_id);
			if ($job_query == NULL) {
				$job_query = new JobQuery;
				$job_query -> seeker_id = $user_id;
				$dt = new DateTime;
				$job_query -> created = $dt->format('Y-m-d H:i:s');
			}
			$job_query -> birthdate = $birthdate;
			$job_query -> city = $city;
			$job_query -> province = $province;
      $job_query -> country = $country;
			$job_query -> edu_level = $edu_level;
      $job_query -> jurusan = $jurusan;
      $job_query -> kompetensi = trim(strtolower($kompetensi));
			$job_query -> interests = trim(strtolower($interests));
			$job_query -> employment_type = trim(strtolower($employment_type));
			$job_query -> sectors = trim(strtolower($employment_sector));
			$job_query -> distance = $distance;
			$job_query -> salary_min = $salary_min;
			$job_query -> salary_max = $salary_max;
			$job_query -> skills = trim(strtolower($skills));
			$job_query -> bio = $bio;
      $job_query -> curr_employment_sector = trim(strtolower($curr_employment_sector));
      $job_query -> is_employed = $is_employed;
      $job_query -> experience = $experience;
      $job_query -> emp_status = $emp_status;
      $job_query -> has_work_experience = $has_work_experience;
            $job_query -> portofolio= $portofolio;
            $job_query -> has_portofolio = $hasportofolio;
			$job_query -> save();

			$job_query->id = $job_seeker->id;
			unset($job_query->seeker_id);
			unset($job_query->created);
			unset($job_query->n_status);
			$job_query->email = $job_seeker->email;
			$job_query->name = $job_seeker->name;
			$job_query->mobile_number = $job_seeker->mobile_number;
			return ['data' => $job_query];
		} else {
			return $this->error("Invalid userid", 400);
		}
	}

	public function getUser() {
		return response()->json(['data' => 'none']);
	}

	public function loginFb(Request $request) {
		//id, cover, name, first_name, last_name, age_range, link, gender,
		//locale, picture, timezone, updated_time, verified, email
		$user_fb_id = trim($request->input('fb_id'));
		$token = trim($request->input('token'));
		$name = trim($request->input('name'));

		$age_range = '';
		if ($request->has('age_range')) {
			$age_range = trim($request->input('age_range'));
		}

		$link = '';
		if ($request->has('link')) {
			$link = trim($request->input('link'));
		}

		$gender = '';
		if ($request->has('gender')) {
			$gender = trim($request->input('gender'));
		}
		if (empty($gender)) {
			$gender = 'undefined';
		}

		$picture = trim($request->input('picture'));
		if ($request->has('picture')) {
			$picture = trim($request->input('picture'));
		}

		$email = trim($request->input('email'));

		if ($user_fb_id == null || strlen($user_fb_id) <= 0) {
			return $this->error("No Facebook ID is inputted", 400);
		}

		if ($token == null || strlen($token) <= 0) {
			return $this->error("No Access Token is inputted", 400);
		}

		if ($name == null || strlen($name) <= 0) {
			return $this->error("No Name is inputted", 400);
		}

		if ($email == null || strlen($email) <= 0) {
			return $this->error("No email is inputted", 400);
		}

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return $this->error("Invalid email", 400);
		}

		// check whether FB ID already exists
		$job_seeker_fb = JobSeekerFB::where('fb_id',$user_fb_id)->first();
		if ($job_seeker_fb == NULL) {
			// check whether email already registered
			$job_seeker = JobSeeker::where('email',$email)->first();
			if ($job_seeker == NULL) {
				// not yet registered, so, insert both JobSeeker and JobSeekerFB
				$job_seeker = new JobSeeker;
				$job_seeker -> email = $email;
				$job_seeker -> password = '';
				$job_seeker -> name = $name;
				//$job_seeker -> birthdate = '';
				//$job_seeker -> mobile_number = '';
				$job_seeker -> image = "https://graph.facebook.com/{$user_fb_id}/picture?type=large";
				$dt = new DateTime;
				$job_seeker -> created = $dt->format('Y-m-d H:i:s');
				$job_seeker -> n_status = 1;
				$job_seeker -> save();

				$job_seeker_fb = new JobSeekerFB;
				$job_seeker_fb -> id = $job_seeker -> id;
				$job_seeker_fb -> fb_id = $user_fb_id;
				$job_seeker_fb -> token = $token;
				$job_seeker_fb -> age_range = $age_range;
				$job_seeker_fb -> link = $link;
				$job_seeker_fb -> gender = $gender;
				$job_seeker_fb -> picture = $picture;
				$job_seeker_fb -> created = $dt->format('Y-m-d H:i:s');
				$job_seeker_fb -> updated = $dt->format('Y-m-d H:i:s');
				$job_seeker_fb -> n_status = 1;
				$job_seeker_fb -> save();
				unset($job_seeker->password);
				unset($job_seeker->n_status);
				return ['data' => $job_seeker];
			} else {
				// FB is not registered, but there exists JobSeeker data
				return $this->error("The email address of this Facebook Account is already in use", 400);
			}
		} else {
			// FB ID already exists/registered
			$job_seeker = JobSeeker::where('id',$job_seeker_fb->id)->first();
			if ($job_seeker == NULL) {
				// JobSeeker already deleted, then

				// check whether email already registered
				$job_seeker = JobSeeker::where('email',$email)->first();
				if ($job_seeker == NULL) {
					// not yet registered, so, insert both JobSeeker
					$job_seeker = new JobSeeker;
					$job_seeker -> email = $email;
					$job_seeker -> password = '';
					$job_seeker -> name = $name;
					//$job_seeker -> birthdate = '';
					//$job_seeker -> mobile_number = '';
					$job_seeker -> image = "https://graph.facebook.com/{$user_fb_id}/picture?type=large";
					$dt = new DateTime;
					$job_seeker -> created = $dt->format('Y-m-d H:i:s');
					$job_seeker -> n_status = 1;
					$job_seeker -> save();

					// JobSeekerFB already exists, then update data
					$job_seeker_fb -> id = $job_seeker -> id;
					$job_seeker_fb -> token = $token;
					$job_seeker_fb -> age_range = $age_range;
					$job_seeker_fb -> link = $link;
					$job_seeker_fb -> gender = $gender;
					$job_seeker_fb -> picture = $picture;
					$job_seeker_fb -> created = $dt->format('Y-m-d H:i:s');
					$job_seeker_fb -> updated = $dt->format('Y-m-d H:i:s');
					$job_seeker_fb -> n_status = 1;
					$job_seeker_fb -> save();
					unset($job_seeker->password);
					unset($job_seeker->n_status);
					return ['data' => $job_seeker];
				} else {
					// FB is not registered, but there exists JobSeeker data
					return $this->error("The email address of this Facebook Account is already in use", 400);
				}
			} else {
				$job_seeker_fb -> token = $token;
				$job_seeker_fb -> age_range = $age_range;
				$job_seeker_fb -> link = $link;
				$job_seeker_fb -> gender = $gender;
				$job_seeker_fb -> picture = $picture;
				$job_seeker_fb -> n_status = 1;
				$job_seeker_fb -> save();

				unset($job_seeker->password);
				unset($job_seeker->n_status);
				return ['data' => array('id' => $job_seeker->id, 'updated' => $job_seeker->updated, )];
			}
		}
	}

	private function reorder_birthdate($birthdate) {
			if (empty($birthdate)) {
				$birthdate = '0000-00-00';
			}
			$birthdate_parts = explode('-', $birthdate);
			$retval = $birthdate_parts[2] . '-' . $birthdate_parts[1] . '-' . $birthdate_parts[0];
			unset($birthdate_parts);
			return $retval;
	}

}
