<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JobSeekerDoc extends Model
{
     /**
	  * table name
	  */
     protected $table = 'job_seeker_doc';
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
     protected $fillable = ['user_id','doc_type','filename','doc_path','n_status',
     						'created'];
     protected $hidden = ['n_status', 'updated'];
	 /**
	  * not mass assignable property
	  */
     protected $guarded = ['updated'];
}
?>
