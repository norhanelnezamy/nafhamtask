<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Schedule extends Model
{

    protected $table = 'schedules';


    protected $fillable = [
      'start_date', 'days_number', 'sessions_number',
    ];

    /**
   * Add new  Schedule
   */
    public static function addRow($request, $user_id)
    {
      $schedule = new Schedule();
      $schedule->user_id = $user_id;
      $schedule->start_date = $request->start_date;
      $schedule->days_number = json_encode(sort($request->days_number));
      $schedule->chapter_sessions_number = $request->chapter_sessions_number;
      $schedule->save();
    }

    /**
   * A Schedule belongs to a User => student.
   */
  public function user()
  {
      return $this->belongsTo(User::class, 'user_id', 'id');
  }

}
