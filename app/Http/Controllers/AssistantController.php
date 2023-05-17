<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Disease\Disease;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\DB;
use App\Models\Assistant\Assistant;
use App\Models\Treatment\Treatment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\DoctorController;

class AssistantController extends Controller
{

 ///   create  assistant account  (personal info )
public function store(Request $request){

    $validator= Validator::make($request->all(),
    [
        'user_name'=>'required|unique:assistants,user_name',
        'email'=>'required|unique:assistants,email',
        'name'=>'required',
        'ar_name'=>'required',
        'password'=>'required|min:8|confirmed',
   ]);
    $password= bcrypt($request->password);
   // $access_token= Str::random(64);

    $name= $request->firstName .' '. $request->middleName .' '. $request->lastName;

    $assistant= Assistant::create([
        'user_name'=>$request->user_name,
        'name'=>$request->name,
        'ar_name'=>$request->ar_name,
        'email'=>$request->email,
        'password'=>$password,
        'image'=>null,
        'access_token'=>null,
       ]);


     return response()->json(['assistant'=>$assistant,
       'type'=>'assistants'
    ],200);
}







// create disease file for patient
public function create_patient_file(Request $request){


    $assistant=DB::table('assistants')->select('*')
    ->where('access_token','=',$request->access_token)
    ->first();

    $reservation=DB::table('reservations')->select('id')
    ->where('patient_id','=',$request->patient_id)
    ->where('status','reserved')
    ->orderBy('reservations.id', 'DESC')
    ->first();

     if($reservation){
        $reservation_id =$reservation->id;
        if( $reservation_id != null){
               return $reservation_id;
        }
     }
     //return $reservation_id;
    //  if  yes
    if($assistant != null){

        $validator= Validator::make($request->all(),
        [
            'patient_id'=>'required',
            'Chief_Complaint'=>'required',

            'health_changes'=>'required',
            'physician_care'=>'required',
            'serious_illnesses_or_operations'=>'required',
             'pregnant'=>'required',
             'Heart_Failur'=>'required',
             'Heart_Attack'=>'required',
             'Angina'=>'required',
             'Pacemaker'=>'required',
             'Congential_Heart_Disease'=>'required',
             'Other_Heart_Disease'=>'required',
             'Anemia'=>'required',
             'Hemophilia'=>'required',
             'Lcukaemia'=>'required',
             'Blood_Transfusion'=>'required',
             'Other_Blood_Disease'=>'required',
             'Asthma'=>'required',
             'Chronic_Obstructive_Pulmonary_Disease'=>'required',
             'Gastro_ocsophagcal_reflux'=>'required',
             'Hepatitits'=>'required',
             'Liver_disease'=>'required',
             'Epilepsy'=>'required',
             'Parkinsons_disease'=>'required',
             'Kidney_Failur'=>'required',
             'Dialysis'=>'required',
             'Drug_Allergy'=>'required',
             'Food_Allergy'=>'required',
             'Cancer'=>'required',
             'Medicines_currently_used'=>'required',
             'smoke'=>'required',
             'cigarette_kind'=>'required',
             'cigarette_frequently'=>'required',
             'dental_treatment_problem'=>'required',
             'face_jaw_teeth_injury'=>'required',
             'dry_mouth'=>'required',
             'local_anesthetic_reaction'=>'required',
             'clench_on_teeth'=>'required',
             'hard_to_breathe'=>'required',
             'sleep_scared'=>'required',
             'people_nervous'=>'required',
             'nightmares'=>'required',
             'Thumb_succing'=>'required',
             'Toungue_thrust'=>'required',
             'Nail_biting'=>'required',
             'Other_Habits'=>'required',
             'TMJ'=>'required',
             'Lymph_node'=>'required',
             'Patient_profile'=>'required',
             'Lip_Competency'=>'required',
             'Incisol_classification'=>'required',
             'Overjet'=>'required',
             'Overbite'=>'required',
             'Hard_Palate'=>'required',
             'mucosa'=>'required',
             'Floor_of_mouth'=>'required',
             'Lips'=>'required',
             'Tongue'=>'required',
             'Gums_and_Tissues'=>'required',
             'Saliva'=>'required',
             'Natural_Teeth'=>'required',
             'Dentures'=>'required',
             'Oral_Cleanliness'=>'required',
             'Dental_Pain'=>'required',


 ]);


          if ($validator->fails()) {
              return response()->json(['msg'=> $validator->errors() ],404);
              }




              $disease= Disease::create([
                'patient_id'=>$request->patient_id,

                'Chief_Complaint'=>$request->Chief_Complaint,
                'health_changes'=>$request->health_changes,
                'physician_care'=>$request->physician_care,
                'serious_illnesses_or_operations'=>$request->serious_illnesses_or_operations,
                 'pregnant'=>$request->pregnant ,
                 'Heart_Failur'=>$request->Heart_Failur ,
                 'Heart_Attack'=>$request->Heart_Attack ,
                 'Angina'=>$request->Angina ,
                 'Pacemaker'=>$request->Pacemaker ,
                 'Congential_Heart_Disease'=>$request->Congential_Heart_Disease ,
                 'Other_Heart_Disease'=>$request->Other_Heart_Disease ,
                 'Anemia'=>$request->Anemia ,
                 'Hemophilia'=>$request->Hemophilia ,
                 'Lcukaemia'=>$request->Lcukaemia ,
                 'Blood_Transfusion'=>$request->Blood_Transfusion ,
                 'Other_Blood_Disease'=>$request->Other_Blood_Disease ,
                 'Asthma'=>$request->Asthma ,
                 'Chronic_Obstructive_Pulmonary_Disease'=>$request->Chronic_Obstructive_Pulmonary_Disease ,
                 'Gastro_ocsophagcal_reflux'=>$request->Gastro_ocsophagcal_reflux ,
                 'Hepatitits'=>$request->Hepatitits ,
                 'Liver_disease'=>$request->Liver_disease ,
                 'Epilepsy'=>$request->Epilepsy ,
                 'Parkinsons_disease'=>$request->Parkinsons_disease ,
                 'Kidney_Failur'=>$request->Kidney_Failur ,
                 'Dialysis'=>$request->Dialysis ,
                 'Drug_Allergy'=>$request->Drug_Allergy ,
                 'Food_Allergy'=>$request->Food_Allergy ,
                 'Cancer'=>$request->Cancer ,
                 'Medicines_currently_used'=>$request->Medicines_currently_used ,
                 'smoke'=>$request->smoke ,
                 'cigarette_kind'=>$request->cigarette_kind ,
                 'cigarette_frequently'=>$request->cigarette_frequently ,
                 'dental_treatment_problem'=>$request->dental_treatment_problem ,
                 'face_jaw_teeth_injury'=>$request->face_jaw_teeth_injury ,
                 'dry_mouth'=>$request->dry_mouth ,
                 'local_anesthetic_reaction'=>$request->local_anesthetic_reaction ,
                 'clench_on_teeth'=>$request->clench_on_teeth ,
                 'hard_to_breathe'=>$request->hard_to_breathe ,
                 'sleep_scared'=>$request->sleep_scared ,
                 'people_nervous'=>$request->people_nervous ,
                 'nightmares'=>$request->nightmares ,
                 'Thumb_succing'=>$request->Thumb_succing ,
                 'Toungue_thrust'=>$request->Toungue_thrust ,
                 'Nail_biting'=>$request->Nail_biting ,
                 'Other_Habits'=>$request->Other_Habits ,
                 'TMJ'=>$request->TMJ ,
                 'Lymph_node'=>$request->Lymph_node ,
                 'Patient_profile'=>$request->Patient_profile ,
                 'Lip_Competency'=>$request->Lip_Competency ,
                 'Incisol_classification'=>$request->Incisol_classification ,
                 'Overjet'=>$request->Overjet ,
                 'Overbite'=>$request->Overbite ,
                 'Hard_Palate'=>$request->Hard_Palate ,
                 'mucosa'=>$request->mucosa ,
                 'Floor_of_mouth'=>$request->Floor_of_mouth ,
                 'Lips'=>$request->Lips ,
                 'Tongue'=>$request->Tongue ,
                 'Gums_and_Tissues'=>$request->Gums_and_Tissues ,
                 'Saliva'=>$request->Saliva ,
                 'Natural_Teeth'=>$request->Natural_Teeth ,
                 'Dentures'=>$request->Dentures ,
                 'Oral_Cleanliness'=>$request->Oral_Cleanliness ,
                 'Dental_Pain'=>$request->Dental_Pain ,
                'image'=>null,
                'reservation_id'=>$reservation_id,

            ]);

            $diseases_id=DB::table('diseases')->select('id')
            ->where('patient_id','=',$request->patient_id)
            ->orderBy('id', 'DESC')
            ->first();

            $diseases_id=DB::table('diseases')
            ->where('id','=',$diseases_id->id)
            ->update(['reservation_id'=> $reservation_id]);


              //return response()->json(['diseases_id'=>$diseases_id]);
              return response()->json(['message'=>'file created']);


    }else{
        return response()->json(['message'=>'no token' ],404);
    }

}




public function get_level_courses(Request $request){
    $assistant=DB::table('assistants')->select('*')
    ->where('access_token','=',$request->access_token)
    ->first();
    if(  $assistant){

        $courses=DB::table('courses')
        ->select('id','name')
        ->where('level','=',$request->level)
        ->get();
        return response()->json(['courses'=>$courses]);

   }else{
    return response()->json(['message'=>'no token' ],404);
   }
}




public function get_course_sections(Request $request){
    $assistant=DB::table('assistants')->select('*')
    ->where('access_token','=',$request->access_token)
    ->first();

    if(  $assistant){
        $sections=DB::table('clinics')
        ->select('id','day' ,'start_time','end_time')
        ->where('course_id','=',$request->course_id)
        ->get();
        return response()->json(['sections'=>$sections]);
    }else{
        return response()->json(['message'=>'no token' ],404);
       }
}




public function show_section_students(Request $request){
    $assistant=DB::table('assistants')->select('*')
    ->where('access_token','=',$request->access_token)
    ->first();

    if(  $assistant){
    return (new AssistantController)->get_students_req( $request);
     }else{
        return response()->json(['message'=>'no token' ],404);

     }

}




public function add_treatments(Request $request)
{


   $assistant=DB::table('assistants')->select('*')
   ->where('access_token','=',$request->access_token)
   ->first();

  if(  $assistant == null){
    return response()->json(['messages'=>'no token' ]);

   }

              $treatments = $request->treatments;

              // input validatoion ====================================

              $validator_errors=[];
              $i =0;
           foreach($treatments  as $treatment)
           {  $i +=1;
            $validator= Validator::make( $treatment , [
                'patient_id'=>'required',
                'reg_id'=>'required',
                'req_id'=>'required',
                'tooth'=>'required',
                'tooth_id'=>'required',
                'start_date'=>'required',
                'end_date'=>'required',
                'description'=>'required',

            ]);
            if ($validator->fails()) {
                $validator_errors[]=$validator->errors();
            }

           }

              if ($validator_errors != null) {
                  return response()->json(['messages'=> "validator errors",
                                           'validator_errors'=> $validator_errors, ]);

                 }


              //  date validation ======================================
                 $date_errors=[];
                 $i =0;
              foreach($treatments  as $treatment)
              {  $i +=1;
                $y=(new AssistantController)->date_validation($treatment)->original['date_validation_errors'];
                if($y !=null){
                    $y['number']= $i;
                    $date_errors[]=$y;
                }

              }
              if($date_errors != null){
                return response()->json(['messages'=>"date errors" ,
                                         'date_errors'=>$date_errors ,]);

              }

               //  add   treatments  ===================================



              foreach($treatments  as $treatment)
              {
                $y=(new AssistantController)->add_treatment($treatment);
                if($y !="treatment created"){
                   return response()->json(['messages'=>$y]);
                }

              }

               return response()->json(['messages'=>"created successfully"]);




}




public function add_treatment( $request){



             $diseases=DB::table('diseases')->select('*')
              ->where('patient_id','=',$request['patient_id'])
              ->get();
              if($diseases == null){
                return "diseases id not exist";

              }

              $disease_id =0;
              foreach($diseases as $disease){
                $disease_id=$disease->id;
              }

             $treatment= Treatment::create([
                'disease_id'=>$disease_id,
                'registeration_id'=>$request['reg_id'],
                'requirement_id'=>$request['req_id'],
                'tooth'=>$request['tooth'],
                'tooth_id'=>$request['tooth_id'],
                'start_date'=>$request['start_date'],
                'end_date'=>$request['end_date'],
                'status'=>'not completed',
                'description'=>$request['description'],


            ]);

                     return "treatment created";
                return response()->json(['messages'=>'treatment created' ],200);


}





public function get_patient_files(Request $request){
    $assistant=DB::table('assistants')->select('*')
    ->where('access_token','=',$request->access_token)
    ->first();
    if($assistant){
        $diseases =DB::table('diseases')
        ->select('diseases.created_at','diseases.id')
        ->where('patient_id',$request->patient_id)
        ->get();

    foreach($diseases as $disease){
        $treatments =DB::table('treatments')
        ->join('requirements','requirements.id','treatments.requirement_id')
        ->select('requirements.name as requirement','treatments.status')
        ->where('treatments.disease_id',$disease->id)
        ->get();
        $disease->treatments=$treatments;
    }



        return response()->json(['diseases'=>$diseases ]);

    }else{
        return response()->json(['messages'=>'no token' ]);
   }
}


public function get_selected_file(Request $request){

    $assistant=DB::table('assistants')->select('*')
    ->where('access_token','=',$request->access_token)
    ->first();
    if($assistant){

  // disease  treatments   ///  reg  ->> student
  $treatments=DB::table('treatments')
  ->join('requirements','requirements.id','treatments.requirement_id')
  ->join('registerations','treatments.registeration_id','registerations.id')
  ->join('students','registerations.student_id','students.id')
  ->join('clinics','registerations.clinic_id','clinics.id')
   ->select('students.name','students.phone',
   'requirements.name as requirement_name','treatments.*',
  'clinics.start_time','clinics.end_time','clinics.day',
  'clinics.hall')
  ->where('treatments.disease_id',$request->disease_id)
  ->get();

    $disease = Disease::find($request->disease_id);
    if($disease->image != null){
        $disease->image = asset("storage").'/'.$disease->image;

    }

  return response()->json(['treatments'=>$treatments,
                             'disease'=>$disease]);


    }else{
        return response()->json(['messages'=>'no token' ]);
   }
}


public function change_treatments_status(Request $request){

    $assistant=DB::table('assistants')->select('*')
    ->where('access_token','=',$request->access_token)
    ->first();

    if($assistant){

        $treatment=DB::table('treatments')
        ->where('id','=',$request->treatment_id)
        ->where('status','=',"not completed")
        ->update(["status"=>"canceled"]);

        if($treatment ==1){
            return response()->json(['message'=>"Treatment has been cancelled"]);
        }else{
            return response()->json(['message'=>"Treatment not exist"]);
        }

      }else{
        return response()->json(['message'=>'no token' ],404);
      }


}


public function get_patient_name($patient_id){
    $patient=DB::table('patients')->select('id','name')
    ->where('id','=',$patient_id)
    ->first();
    if($patient){
        return response()->json(['patient'=>$patient ]);
    }
}



public function get_students_req(Request $request){

    $course= DB::table('courses')
    ->join('clinics','courses.id','clinics.course_id')
    ->where('clinics.id',$request->clinic_id)
    ->select('courses.id')
    ->first();
    $course_id = $course->id;

    $students=DB::table('students')
    ->join('registerations','registerations.student_id','students.id')
    ->join('clinics','registerations.clinic_id','clinics.id')
    ->select('students.id','students.name','registerations.id as reg_id')
    ->where('clinics.course_id',$course_id)
    ->where('clinics.id',$request->clinic_id)
    ->get();



    foreach(  $students as   $student){
        $reqs=DB::table('requirements')
        ->select('requirements.id','requirements.name','requirements.course_id')
        ->where('course_id',$course_id)
        ->get();

        $treatments=DB::table('requirements')
        ->leftJoin('treatments','treatments.requirement_id','requirements.id')
        ->select('requirements.id as req_id','requirements.name','requirements.course_id',
        'treatments.status','treatments.registeration_id as reg_id','treatments.start_date','treatments.end_date',
        'treatments.disease_id as disease_id','treatments.id as treatment_id')
        ->where('course_id',$course_id)
        ->where('treatments.registeration_id','=', $student->reg_id)
        ->where('treatments.status','!=','canceled')
        ->get();

        $student_req= $reqs;
        foreach($student_req as $req){$req->status=null;}

          // course req
          foreach( $treatments as $treatment){   //student treatments
            foreach( $student_req as $req){

                  if($req->id == $treatment->req_id){
                      $req->status= $treatment->status;
                      $req->disease_id= $treatment->disease_id;
                      $req->treatment_id= $treatment->treatment_id;
                      $req->start_date= $treatment->start_date;
                      $req->end_date= $treatment->end_date;
                  }

          }
         }

         $student->reqs =  $student_req;
    }  // foreach




    return response()->json(['students'=>$students ]);


}




public function date_validation($request)
{
 $start_date =$request['start_date'];
 $end_date =$request['end_date'];


 $treatments_errors_in= DB::table('treatments')
 ->where('treatments.registeration_id', 15)
 ->whereDate('treatments.end_date','>= ', $end_date  )
 ->whereDate('treatments.start_date',' <= ', $start_date)
 ->where('treatments.status','== ', 'not completed')
 ->select('treatments.id as treatment_id','treatments.start_date','treatments.end_date')
 ->get();

 $treatments_errors_start= DB::table('treatments')
 ->where('treatments.registeration_id', 15)
 ->whereDate('treatments.end_date','<= ', $end_date  )
 ->whereDate('treatments.end_date',' >= ', $start_date)
 ->where('treatments.status','== ', 'not completed')
 ->select('treatments.id as treatment_id','treatments.start_date','treatments.end_date')
 ->get();


 $treatments_errors_end= DB::table('treatments')
 ->where('treatments.registeration_id', 15)
 //->whereDate('treatments.end_date','> ', $end_date  )
 ->whereDate('treatments.start_date',' >= ', $start_date)
 ->whereDate('treatments.start_date',' <= ', $end_date)
 ->where('treatments.status','== ', 'not completed')
 ->select('treatments.id as treatment_id','treatments.start_date','treatments.end_date')
 ->get();

 $date_validation_errors =[];


 foreach($treatments_errors_in as $in){
    if(  in_array($in->treatment_id, $date_validation_errors ) == 0){
        $date_validation_errors[]=$in->treatment_id;
    }
 }
 foreach($treatments_errors_start as $in){
    if(  in_array($in->treatment_id, $date_validation_errors ) == 0){
        $date_validation_errors[]=$in->treatment_id;
    }
 }
 foreach($treatments_errors_end as $in){
    if(  in_array($in->treatment_id, $date_validation_errors ) == 0){
        $date_validation_errors[]=$in->treatment_id;
    }
 }


 return response()->json(['date_validation_errors'=>$date_validation_errors,]);


 return response()->json(['treatments_errors_in'=>$treatments_errors_in,
                         'treatments_errors_start'=>$treatments_errors_start,
                         'treatments_errors_end'=>$treatments_errors_end,
                         'date_validation_errors'=>$date_validation_errors,

                        ]);

}









}// end of class
