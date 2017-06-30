<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\EducationLevel;
use App\Model\Experience;
use App\Model\Interest;
use App\Model\EmploymentType;
use App\Model\Sector;
use App\Model\Skill;
use App\Model\City;
use App\Model\Province;
use App\Model\EmploymentStatus;
use App\Model\Jurusan;
use App\Model\Kompetensi;
use App\Model\StatusKepegawaian;
use DB;

class RegistrationController extends Controller {

	public function getCities() {
		$cities = City::all();
		return ['data' => $cities];
	}

	public function getJurusan(){
        $jurusan = Jurusan::all();
        return ['data' => $jurusan];

    }

    public function getKompetensi(){
        $kompetensi = Kompetensi::all();
        return ['data' => $kompetensi];
    }

    public function getStatusKepegawaian(){
        $status_kepegawaian=StatusKepegawaian::all();
        return ['data'=> $status_kepegawaian];
    }

	public function getCitiesOfProvince($province_id) {
		//$cities = City::all();
		$sql = 'SELECT A.id, A.city
FROM tbl_city AS A
INNER JOIN tbl_province AS B
ON A.province = B.province_name
WHERE B.id = ?';
		$cities = DB::select($sql, array($province_id));
		return ['data' => $cities];
	}

	public function getProvinces() {
		$provinces = Province::all();
		return ['data' => $provinces];
	}

	public function getSalaryRange() {
		$data = array('data' => array(
							 array('label' => '< 3.000.000', 'salary_min' => 0, 'salary_max' => 3000000),
							 array('label' => '> 3.100.000 to 5.000.000', 'salary_min' => 3100000, 'salary_max' => 5000000),
							 array('label' => '> 5.100.000 to 7.500.000', 'salary_min' => 5100000, 'salary_max' => 7500000),
							 array('label' => '> 7.600.000 to 10.000.000', 'salary_min' => 7600000, 'salary_max' => 10000000),
							 array('label' => '> 10.100.000 to 20.000.000', 'salary_min' => 10100000, 'salary_max' => 20000000),
							 array('label' => '> 20.100.000', 'salary_min' => 20100000, 'salary_max' => 1000000000),
							));
		return response()->json($data);
	}

	public function getEducation() {
		$edulevels = EducationLevel::orderBy('id', 'asc')->get();
		return ['data' => $edulevels];
	}

	public function getEmpStatus() {
		$emp_status = EmploymentStatus::orderBy('emp_status', 'asc')->get();
		return ['data' => $emp_status];
	}

	public function getExperience() {
		$experience = Experience::orderBy('experience', 'asc')->get();
		return ['data' => $experience];
	}

	public function getInterests() {
		$interests = Interest::orderBy('interest', 'asc')->get();
		return ['data' => $interests];
	}

	public function getEmploymentTypes() {
		$emptypes = EmploymentType::orderBy('employment_type', 'asc')->get();
		return ['data' => $emptypes];
	}

	public function getEmploymentSectors() {
		$sectors = Sector::orderBy('sector', 'asc')->get();
		return ['data' => $sectors];
	}

	public function getSkills() {
		$skills = Skill::orderBy('skill', 'asc')->get();

		return ['data' => $skills];
	}

}
