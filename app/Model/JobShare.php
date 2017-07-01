<?php 

namespace App\Model;
  
use Illuminate\Database\Eloquent\Model;
  
class JobShare extends Model
{
     /**
	  * table name
	  */
     protected $table = 'job_share';
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
     protected $fillable = ['user_id', 'job_id', 'name', 'email', 'telp', 'created', 'n_status', 'sent_email', 'sent_sms'];
	 /**
	  * not mass assignable property
	  */
     //protected $guarded = ['updated'];
}
?>

