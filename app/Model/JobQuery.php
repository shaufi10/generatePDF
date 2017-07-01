<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JobQuery extends Model
{
     /**
	  * table name
	  */
     protected $table = 'job_query';
	 /**
	  * primary key column
	  */
	 protected $primaryKey = 'seeker_id';
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
     protected $fillable = ['age','is_employed','curr_employment_sector','city',
     						'province','country','edu_level','interests','employment_type',
     						'sectors','distance','has_portofolio','has_work_experience',
     						'salary_min','salary_max','skills', 'bio', 'experience', 'emp_status',
     						'created'];
	 /**
	  * not mass assignable property
	  */
     protected $guarded = ['updated'];

	public static function createDummy() {
		$profile = new JobQuery;
		$profile->age=22;
		$profile->is_employed=1;
		$profile->curr_employment_sector='corporate banking';
		$profile->city='jakarta';
		$profile->province='dki jakarta';
		$profile->country='indonesia';
		$profile->edu_level='d3';
		$profile->interests=array('social media','automotive','networking','technology','computers');
		$profile->employment_type='part-time';
		$profile->sectors=array('media');
		$profile->distance=20;
		$profile->has_portofolio=0;
		$profile->has_work_experience=0;
		$profile->salary_min=4000000;
		$profile->salary_max=7500000;
		$profile->skills=array('sap','microsoft','telecom','internet of things','marketing');
		return $profile;
	}
}
?>
