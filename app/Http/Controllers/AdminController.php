<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Star\Star;
use App\Models\Admin\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Advice\Advice;
use App\Models\Initial\Initial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{

    public function store(Request $request)
    {

       $validator= Validator::make($request->all(),
           [
               'user_name'=>'required|unique:doctors,user_name',
               'name'=>'required',
               'ar_name'=>'required',
               'email'=>'required',
               'password'=>'required|min:8',

           ]);
           if ($validator->fails()) {
             return response()->json(['msg'=> $validator->errors() ],404);
            }
       // bcrypt  &  access_token
           $password= bcrypt($request->password);

       // create and login
       $admin= Admin::create([
           'user_name'=>$request->user_name,
           'name'=>$request->name,
           'ar_name'=>$request->ar_name,
           'email'=>$request->email,
           'password'=>$password,
           'image'=>null,
           'access_token'=>null,
          ]);

          return response()->json(['message'=>'created' ]);

    }


    public function login(Request $request)
    {

           $admin =  DB::table('admins')->where('user_name' ,'=', $request->user_name)->first() ;

            if($admin){

              if(Hash::check($request->password, $admin->password)  ){


                      //  set access token
                    $access_token= Str::random(64);
                  DB::table('admins')->where('user_name' ,'=', $request->user_name)->update(['access_token'=> $access_token])  ;

                  // get student after change the access
                  $admin =  DB::table('admins')->where('user_name' ,'=', $request->user_name)->first() ;
                  if( $admin->image != null){
                       $admin->image=asset("storage").'/'.$admin->image;
                  }


                    return response()->json(['admin'=>$admin,
                         'type'=>'admins',
                         ],200);

                }else{
                    return response()->json(['msg'=>'password not correct' ],404);
               }
            }else{
                return response()->json(['msg'=>'user name not exist' ],404);
            }



    }



public function get_general_info($access_token){
        $admin =  DB::table('admins')->where('access_token' ,'=',$access_token)->first() ;
    if($admin == null){
      return response()->json(['message'=>'no token' ]);
     }
     $treatments =  count(DB::table('treatments')->select('*')->get());
     $cancelled_treatments =  count(DB::table('treatments')->select('*')
                                   ->where('status','=','canceled')
                                   ->get());
      $patients =  count(DB::table('patients')->select('*')->get());

      return response()->json(['total_treatments'=>$treatments,
                               'cancelled_treatments'=>$cancelled_treatments,
                               'clients'=>$patients,]);



}



    //   start , end date --> then add an initial clinic
    public function add_initial(Request $request){


        $admin =  DB::table('admins')->where('access_token' ,'=',$request->access_token)->first() ;
         //  if login
        if($admin){

        $start_date=$request->start_date;
        $end_date=$request->end_date;

        $period = CarbonPeriod::create($start_date, $end_date );

        // Iterate over the period
        foreach ($period as $date) {
            $thisdate= $date->format('Y-m-d ' );
            $timestamp = strtotime($date);
            $day = date('D', $timestamp);
            $start_times=['08:30:00','09:30:00','10:30:00','11:30:00','01:30:00'];
            $end_times=['09:20:00','10:20:00','11:20:00','01:20:00','02:20:00'];

            if($day !='Fri'){
                for($i=0;$i <5 ;$i++){
                    $initial= Initial::create([
                        'day'=>$day,
                        'start_time'=>$start_times[$i],
                        'end_time'=>$end_times[$i],
                        'seats'=>'7',
                        'date'=>$thisdate
                    ]);
                                    }// for

                  }//if


        }
        return response()->json(['msg'=>'initials added'  ]);

        }else{
            return response()->json(['msg'=>'no token ' ],404);

        }


    }



    public function show_stars_evaluation(){
        $topics=DB::table('stars')->select('*')->where('status','=','apparent')->get();

        foreach($topics  as $topic){
               $topic->evaluation= ($topic->sum / $topic->clients_num);
        }
        return response()->json(['topics'=>$topics]);
    }




    public function get_sentiments_result(){

        $month = Carbon::now()->format('m');

       // $avg_stars = DB::table('sentiments')->avg('value')->groupBy('');

        $month_values = DB::table('sentiments')
        ->select(DB::raw("DATE_FORMAT(created_at, '%m') as month" ),
        DB::raw("DATE_FORMAT(created_at, '%y') as year" )
         ,'value' )->get();
           $result=[];

           // get all months name
           $months = [];
           for ($m=1; $m<=12; $m++) {
             $months[] = date('F', mktime(0,0,0,$m, 1, date('Y')));
           }


      // return response()->json(['values avg'=>$months[0] ]);

          for($i =1 ; $i <= 12  ;$i++){
                 $sum =0;  $count =0;
              foreach($month_values  as $month_value){
                   if( $month_value->month == $i){
                       $sum+=$month_value->value;
                       $count +=1;
                   }
              }

              if($count !=0){
                $result[$months[$i-1]] = ($sum /$count)*50 + 50;
              }else{
                $result[$months[$i-1]] = 0;
              }

          }



        return response()->json(['values_avg'=>$result  ]);
    }



///       ============   stars ====================

public function add_stars_aspect(Request $request){

    $admin =  DB::table('admins')->where('access_token' ,'=',$request->access_token)->first() ;
    if($admin == null){
      return response()->json(['message'=>'no token' ]);
     }

    $validator= Validator::make($request->all(),
    [
        'topic'=>'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['message'=> $validator->errors() ]);
     }


     $admin= Star::create([
        'topic'=>$request->topic,
        'sum'=>3,
        'clients_num'=>1
       ]);


    return response()->json(['message'=> "created" ]);


}


public function delete_stars_aspect(Request $request){

    $admin =  DB::table('admins')->where('access_token' ,'=',$request->access_token)->first() ;
    if($admin == null){
      return response()->json(['message'=>'no token' ]);
     }
     $topic =  DB::table('stars')
              ->where('id' ,'=',$request->id)
             ->update(['status'=>'hidden'])
             ;

    return response()->json(['message'=> "deleted" ]);


}


public function update_stars_aspect(Request $request){

    $admin =  DB::table('admins')->where('access_token' ,'=',$request->access_token)->first() ;
    if($admin == null){
      return response()->json(['message'=>'no token' ]);
     }

     $validator= Validator::make($request->all(),
     [
         'topic'=>'required',
     ]);

     if ($validator->fails()) {
       return response()->json(['message'=> $validator->errors() ]);
      }

     $topic =  DB::table('stars')
              ->where('id','=',$request->id)
             ->update(['topic'=>$request->topic])
             ;

    return response()->json(['message'=> "updated" ]);


}





//============================

public function add_medical_advice(Request $request){

    $admin =  DB::table('admins')->where('access_token' ,'=',$request->access_token)->first() ;
    if($admin == null){
      return response()->json(['message'=>'no token' ]);
     }


    $validator= Validator::make($request->all(),
    ['link'=>'required' ,
     'title'=>'required' ,
     'body'=>'required' ,
      ]);

    if ($validator->fails()) {
      return response()->json(['message'=> $validator->errors() ]);
     }


     $admin=Advice::create([
        'link'=>$request->link,
        'title'=>$request->title,
        'body'=>$request->body
       ]);


    return response()->json(['message'=> "created" ]);


}

public function delete_medical_advice(Request $request){

    $admin =  DB::table('admins')->where('access_token' ,'=',$request->access_token)->first() ;
    if($admin == null){
      return response()->json(['message'=>'no token' ]);
     }

     $x =  DB::table('advice')
              ->where('id' ,'=',$request->id)
              ->update(['status'=>'hidden'])
              ;



    return response()->json(['message'=> "deleted" ]);


}


public function update_medical_advice(Request $request){

    $admin =  DB::table('admins')->where('access_token' ,'=',$request->access_token)->first() ;
    if($admin == null){
      return response()->json(['message'=>'no token' ]);
     }


     $validator= Validator::make($request->all(),
     ['link'=>'required' ,
      'title'=>'required' ,
      'body'=>'required' ,
       ]);

     if ($validator->fails()) {
       return response()->json(['message'=> $validator->errors() ]);
      }

     $admin =  DB::table('advice')
              ->where('id' ,'=',$request->id)
              ->update(['link'=>$request->link,
                        'title'=>$request->title,
                        'body'=>$request->body,
                          ]);



    return response()->json(['message'=> "updated" ]);


}




public function get_medical_advice(){

    $advice =  DB::table('advice')
    ->select('link','title','body','id')
    ->where('status','apparent')
    ->get() ;

   return response()->json(['advice'=> $advice ]);

}


//===========================================

public function get_comment_text(Request $request){
    $admin =  DB::table('admins')->select('*')->where('access_token' ,'=',$request->access_token)->first() ;
    if($admin == null){
      return response()->json(['message'=>'no token' ]);
     }


     $validator= Validator::make($request->all(),
     ['date'=>'required' ,
      ]);

     if ($validator->fails()) {
       return response()->json(['message'=> $validator->errors() ]);
      }


      $positive =  DB::table('sentiments')
      ->select('text')
      ->where('created_at' ,'=',$request->date)
      ->where('value' ,'>',0)
      ->get() ;

      $negative =  DB::table('sentiments')
      ->select('text')
      ->where('created_at' ,'=',$request->date)
      ->where('value' ,'<' ,0)
      ->get() ;

       return response()->json(['positive'=> $positive  ,'negative'=>$negative ]);


}


}
