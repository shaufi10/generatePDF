<?php 

namespace App\Model;
  
use Illuminate\Database\Eloquent\Model;
  
class Employer extends Model
{
     /**
	  * table name
	  */
     protected $table = 'employer';
	 /**
	  * primary key column
	  */
	 //protected $id = 'id';
	 /**
	  * primary key is incrementing integer value
	  */
	 //$public $incrementing = false;
	 /**
	  * Do not let lumen automatically manage created_at and updated_at
	  */
	 public $timestamps = false;
	 /**
	  * mass assignable property
	  */
     protected $fillable = ['email','password','name','birthdate','mobile_number',
     						'sequrity_question','security_answer','n_status',
     						'created'];
	 /**
	  * not mass assignable property
	  */
     protected $guarded = ['updated'];
}
?>

