<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public  function getPatientForm(){
        return view("add-patient");
    }

    public function addPatient(Request $request){
        $name = $request->name;
        $age = $request->age;
        $gender = $request->gender;
        Patient::create([
            'name'   => $request->name,
            'age'    => $request->age,
            'gender' => $request->gender,
        ]);
        return "success broo";
    }


}
