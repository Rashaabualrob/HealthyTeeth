<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TwilioSMSController;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command send reminders for patient before his initial clinic';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = now();
        $today->day++;
        $tomorrow =$today->format('Y-m-d');

                $tomorrow_reservations=DB::table('patients')
                ->join('reservations','reservations.patient_id','patients.id')
                ->join('initials','reservations.initial_id','initials.id')
                ->select('initials.*','reservations.id as reservation_id',
                            'patients.name' ,'patients.phone')
                ->where('status','=','reserved')
                ->whereDate('date','=',$tomorrow)
                ->orderBy('reservations.id', 'DESC')
                ->get();


         //   return response()->json(['initials'=> $initials ]);
      if($tomorrow_reservations){
           foreach($tomorrow_reservations as $initial){
               $name= explode(' ', $initial->name);

           //  (new TwilioSMSController)->initial_sms($initial  ,$initial->phone  ,$name[0] ,3);
           }

        }





        $patients=DB::table('patients')->select('*')->get();

        if($patients == null){
           return response()->json(['messages'=>"no patients"]);
        }

        $appointments=[];

        foreach($patients as $patient){
            $treatments=DB::table('treatments')
            ->join('diseases','diseases.id','treatments.disease_id')
            ->join('patients','diseases.patient_id','patients.id')
            ->join('registerations','registerations.id','treatments.registeration_id')
            ->join('clinics','registerations.clinic_id','clinics.id')
            ->where('clinics.day',today()->addDays(1)->format('D'))
            ->whereDate('treatments.end_date','> ', now()->format('y-m-d'))
            ->whereDate('treatments.start_date',' <= ', now()->addDays(1)->format('y-m-d'))
            ->where('patients.id', $patient->id)
            ->select( 'patients.name','treatments.id' ,'treatments.start_date','treatments.end_date' ,
                       'patients.phone' ,'clinics.day' ,'clinics.start_time','clinics.end_time')->get();

                if($treatments != null){
                    foreach($treatments as $treatment){
                        $appointments[] = $treatment;

                    }
                }

        }


        if($appointments){
            foreach($appointments as $appointment){
                $name= explode(' ', $appointment->name);

             (new TwilioSMSController)->treatment_sms($appointment->start_time  ,$appointment->phone  ,$name[0]);
            }

              }





    }





}
