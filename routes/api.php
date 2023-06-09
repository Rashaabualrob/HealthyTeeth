<?php

use App\Mail\TestEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\TwilioSMSController;
use App\Http\Controllers\SecretarieController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\RadiographerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/logout',[AuthController::class,'logout']);  //
Route::post('/login',[AuthController::class,'login']);  //
Route::get('/store/{user_name}/{type}',[AuthController::class,'store']);  //

Route::post('/forget_password',[AuthController::class,'forget_password']);
Route::post('/password_verification',[AuthController::class,'password_verification']);
Route::post('/change_forgotten_password',[AuthController::class,'change_forgotten_password']);
Route::post('/get_name',[AuthController::class,'get_name']);
Route::post('/get_profile',[AuthController::class,'get_profile']);
Route::post('/get_image',[AuthController::class,'get_image']);
Route::post('/set_image',[AuthController::class,'set_image']);
Route::post('/change_password',[AuthController::class,'change_password']);

Route::get('/get_vedio',[AuthController::class,'get_vedio']);



//Route::get('/students',[StudentController::class,'index']);

Route::post('/student/store',[StudentController::class,'store']);
Route::get('/student/get_student_courses/{access_token}',[StudentController::class,'get_student_courses']);
Route::get('/student/get_patient_name/{patient_id}',[StudentController::class,'get_patient_name']);
Route::get('/student/show_progress/{access_token}',[StudentController::class,'show_progress']);
Route::post('/student/get_req_status',[StudentController::class,'get_req_status']);
Route::post('/student/get_selected_file',[StudentController::class,'get_selected_file']);





Route::post('/patient/register',[PatientController::class,'register']);  // create student
Route::post('/patient/login',[PatientController::class,'login']);  // login
Route::post('/patient/mobile_verification', [PatientController::class, 'mobile_verification']);
Route::post('/patient/show_selected_date_initials',[PatientController::class,'show_selected_date_initials']);
Route::post('/patient/select_initial',[PatientController::class,'select_initial']);
Route::delete('/patient/delete_initial/{access_token}',[PatientController::class,'delete_initial']);
Route::get('/patient/get_next_treatments/{access_token}',[PatientController::class,'get_next_treatments']);
Route::get('/patient/get_reserved_initials/{access_token}',[PatientController::class,'get_reserved_initials']);
Route::get('/patient/get_next_initial/{access_token}',[PatientController::class,'get_next_initial']);

Route::post('/patient/get_selected_file',[PatientController::class,'get_selected_file']);
Route::get('/patient/get_patient_files/{access_token}',[PatientController::class,'get_patient_files']);
Route::post('/patient/send_comment',[PatientController::class,'send_comment']);
Route::post('/patient/update_initial',[PatientController::class,'update_initial']);
Route::get('/patient/get_stars_topics/{access_token}',[PatientController::class,'get_stars_topics']);
Route::post('/patient/stars_evaluation',[PatientController::class,'stars_evaluation']);
Route::post('/patient/cancel_treatment',[PatientController::class,'cancel_treatment']);
Route::get('/patient/get_second_treatments', [PatientController::class, 'get_second_treatments']);

Route::post('/patient/sendSMS', [TwilioSMSController::class, 'index']);







Route::post('/assistant/store',[AssistantController::class,'store']);  // create student
Route::post('/assistant/create_patient_file',[AssistantController::class,'create_patient_file']);  // logout
Route::post('/assistant/get_level_courses',[AssistantController::class,'get_level_courses']);  // logout
Route::post('/assistant/get_course_sections',[AssistantController::class,'get_course_sections']);  // logout
Route::post('/assistant/show_section_students',[AssistantController::class,'show_section_students']);
Route::post('/assistant/add_treatments',[AssistantController::class,'add_treatments']);
Route::post('/assistant/add_treatment',[AssistantController::class,'add_treatment']);

Route::post('/assistant/get_patient_files',[AssistantController::class,'get_patient_files']);  // logout
Route::post('/assistant/get_selected_file',[AssistantController::class,'get_selected_file']);  // logout
Route::post('/assistant/change_treatments_status',[AssistantController::class,'change_treatments_status']);  // logout
Route::get('/assistant/get_patient_name/{patient_id}',[AssistantController::class,'get_patient_name']);  // logout
Route::post('/assistant/date_validation',[AssistantController::class,'date_validation']);  // logout




Route::post('/secretary/store',[SecretarieController::class,'store']);  // create Secretarie
Route::get('/secretary/show_initial_appointments/{access_token}',[SecretarieController::class,'show_initial_appointments']);  // login secretarie
Route::post('/secretary/search_initial',[SecretarieController::class,'search_initial']);  // login secretarie
Route::get('/secretary/download_file/{access_token}',[SecretarieController::class,'download_file']);  // login secretarie
Route::get('/secretary/send_appointments/{access_token}',[SecretarieController::class,'send_appointments']);  // login secretarie
Route::get('/secretary/get_week_appointments/{access_token}',[SecretarieController::class,'get_week_appointments']);  // login secretarie

//

Route::post('/radiographer/store',[RadiographerController::class,'store']);  // create Secretarie
Route::post('/radiographer/login',[RadiographerController::class,'login']);  // login secretarie
Route::post('/radiographer/logout',[RadiographerController::class,'logout']);  // login secretarie
Route::post('/radiographer/change_password',[RadiographerController::class,'change_password']);  // create Secretarie
Route::post('/radiographer/update_image',[RadiographerController::class,'update_image']);  // create Secretarie
Route::post('/radiographer/show_patient_files',[RadiographerController::class,'show_patient_files']);  // login secretarie
Route::post('/radiographer/set_patient_image',[RadiographerController::class,'set_patient_image']);  // login secretarie



//doctors
Route::post('/doctor/store',[DoctorController::class,'store']);  // create Secretarie
Route::get('/doctor/get_courses/{access_token}',[DoctorController::class,'get_courses']);
Route::post('/doctor/get_course_clinics',[DoctorController::class,'get_course_clinics']);  // create Secretarie
Route::post('/doctor/get_clinic_students',[DoctorController::class,'get_clinic_students']);  // create Secretarie
Route::post('/doctor/update_treatment_status',[DoctorController::class,'update_treatment_status']);
Route::post('/doctor/get_student_req',[DoctorController::class,'get_student_req']);
Route::post('/doctor/get_selected_file',[DoctorController::class,'get_selected_file']);
Route::post('/doctor/get_over_view',[DoctorController::class,'get_over_view']);


//get_students_req


Route::post('/admin/store',[AdminController::class,'store']);  // create Secretarie
Route::post('/admin/login',[AdminController::class,'login']);  // login secretarie

Route::post('/admin/add_initial',[AdminController::class,'add_initial']);
Route::get('/admin/get_general_info/{access_token}',[AdminController::class,'get_general_info']);
Route::get('/show_stars_evaluation',[AdminController::class,'show_stars_evaluation']);  // get student data to update it
Route::post('/admin/get_comment_text',[AdminController::class,'get_comment_text']);  // get student data to update it

Route::get('/admin/get_sentiments_result',[AdminController::class,'get_sentiments_result']);

Route::post('/admin/add_stars_aspect',[AdminController::class,'add_stars_aspect']);  // get student data to update it
Route::post('/admin/delete_stars_aspect',[AdminController::class,'delete_stars_aspect']);  // get student data to update it
Route::post('/admin/update_stars_aspect',[AdminController::class,'update_stars_aspect']);  // get student data to update it

Route::post('/admin/add_medical_advice',[AdminController::class,'add_medical_advice']);  // get student data to update it
Route::post('/admin/delete_medical_advice',[AdminController::class,'delete_medical_advice']);  // get student data to update it
Route::post('/admin/update_medical_advice',[AdminController::class,'update_medical_advice']);  // get student data to update it

Route::get('/admin/get_medical_advice',[AdminController::class,'get_medical_advice']);






Route::post('/course/create',[CourseController::class,'create']);
Route::get('/course/get_courses',[CourseController::class,'get_courses']);

Route::post('/requirement/create',[RequirementController::class,'create']);
Route::get('/requirement/get_requirement',[RequirementController::class,'get_requirement']);



