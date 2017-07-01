<?php 

namespace App\Model;
  
use Illuminate\Database\Eloquent\Model;
  
class JobVacancy extends Model
{
     /**
	  * table name
	  */
     protected $table = 'job_vacancy';
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
     protected $fillable = ['employer_id','job_title','desc','banner','age_min',
     						'age_max','is_employed','is_similar_sector','city',
     						'province','education_level','interests',
     						'employment_type','employment_sector','has_portofolio',
     						'has_experience','salaray_min','salary_max',
     						'required_skills','dt_start','dt_end','n_status',
     						'created'];
	 /**
	  * not mass assignable property
	  */
     protected $guarded = ['updated'];
}
?>

