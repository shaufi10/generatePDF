<?php 

namespace App\Model;
  
use Illuminate\Database\Eloquent\Model;
  
class EmployerProfile extends Model
{
     /**
	  * table name
	  */
     protected $table = 'employer_profile';
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
     protected $fillable = ['employer_id', 'username', 'company_name', 'office_number', 'address',
     						'city', 'country', 'company_logo',
     						'created','company_code'];
	 /**
	  * not mass assignable property
	  */
     protected $guarded = ['updated'];
}
?>

