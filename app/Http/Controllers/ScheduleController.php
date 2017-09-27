<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Validator;
use App\Schedule;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function store(Request $request, $token){

      try {
        if ($user = JWTAuth::toUser($token)) {
          // return $user;
          $validator = Validator::make($request->all(), [
              'start_date' => 'required|date',
              'days_number' => 'required|array',
              'chapter_sessions_number' => 'required|integer',
          ]);
          if ($validator->fails()) {
              return response()->json(['error' => $validator->errors()]);
          }

          $start_date_key = array_search(Carbon::parse($request->start_date)->dayOfWeek+1, $request->days_number);
          if (!empty($start_date_key)) {
            // if start date not is the first day reorder days_number array
            if ($start_date_key != 0) {
              $new_order_days = array();
              for ($i=$start_date_key; $i < sizeof($request->days_number) ; $i++) {
                array_push($new_order_days, $request['days_number'][$i]);
              }
              for ($i=0; $i <= ($start_date_key-1) ; $i++) {
                array_push($new_order_days, $request['days_number'][$i]);
              }
              $request->days_number =$new_order_days;
            }
            // Schedule::addRow($request, $user->id);
            // return $request->days_number;
            $sessions_dates = array($request->start_date);
            for ($i=0; $i < 30*$request->chapter_sessions_number ; $i+=sizeof($request->days_number)) {
              foreach ($request->days_number as $key => $day) {
                $last_date = Carbon::parse(end($sessions_dates));
                array_push($sessions_dates, $last_date->addDays(abs($day - Carbon::parse($last_date)->dayOfWeek+1))->format('Y-m-d'));
              }
            }
            // return sizeof($sessions_dates);
            return response()->json(['dates' => $sessions_dates], 201);
          }
          return response()->json(['status' => 'Please, enter an available starting date according to your chosen days.'], 201);
        }
      } catch (JWTException $e){
        return response()->json(['status' => 'Invalid token. please, try to login again.'], 500);
      }

    }
}
