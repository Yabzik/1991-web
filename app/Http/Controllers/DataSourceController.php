<?php

namespace App\Http\Controllers;

use App\Models\DataSource;
use App\Models\Event;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

use Carbon\Carbon;

use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event as EventCal;

class DataSourceController extends Controller
{
    public function process_all()
    {
        $all_sources = DataSource::all();
        foreach ($all_sources as $key => $source) {
            $headers = get_headers($source->url, 1);
            $size = $headers['Content-Length'];

            if ($size != $source->last_size)
            {
                $result = $this->process_source($source);
                if ($result) {
                    $source->last_updated = Carbon::now();
                }
                $source->last_size = $size;

            }
            $source->last_checked = Carbon::now();
            $source->save();
        }
    }

    private function process_source($source)
    {
        $updated = false;

        $temp_file = tempnam(sys_get_temp_dir(), 'temp.' . pathinfo($source->url, PATHINFO_EXTENSION));
        copy($source->url, $temp_file);

        $spreadsheet = IOFactory::load($temp_file);
        $sheetCount = $spreadsheet->getSheetCount();
        for ($i = 0; $i < $sheetCount; $i++) {
            $sheet = $spreadsheet->getSheet($i);
            $sheetData = $sheet->toArray(null, true, true, true);
            $result = $this->process_sheet($sheetData, $source->faculty, $source->speciality);
            if ($result) {
                $updated = true;
            }
        }
        return $updated;
    }

    private function process_sheet($sheetData, $faculty, $speciality)
    {
        $group_cols = [];
        foreach ($sheetData[8] as $key => $col) {
            if ($col) {
                $group_cols[(int)($col / 100)] = $key;
            }
        }

        $day_rows = [];
        foreach (array_column($sheetData, 'A') as $key => $row) {
            if (($key > 7) && ($row)) {
                $exploded_row = explode(' ', $row);
                $date_string_raw = end($exploded_row);
                if (strpos($date_string_raw, '/') == FALSE) {
                    $day_rows[$date_string_raw] = $key;
                }
                else {
                    foreach (explode('/', $date_string_raw) as $k => $d_tmp) {
                        $day_rows[$d_tmp] = $key;
                    }
                }
            }
        }

        $updated = false;

        foreach ($group_cols as $group => $group_col) {
            foreach ($day_rows as $date_string => $date_row) {
                $day = array_column($sheetData, $group_col);
                $day = array_slice($day, $date_row, 8);
                // print_r($date_string . " " . $group);
                // print_r($day);

                for ($i=0; $i < 8; $i+=2) {
                    $event_raw = [
                        'date' => Carbon::parse($date_string),
                        'index' => ($i / 2) + 1,
                        'faculty' => $faculty,
                        'course' => $group,
                        'speciality' => $speciality,
                        'title' => $day[$i],
                        'subtitle' => $day[$i+1],
                    ];
                    $existent = Event::where($event_raw)->get();

                    if (!$existent->isEmpty()) {
                        $event_raw['date'] = $event_raw['date']->toDateString();
                        $filtered_compare = array_intersect_key($existent->toArray()[0], array_flip(array_keys($event_raw)));

                        $diff = array_diff($event_raw, $filtered_compare);

                        if (!empty($diff)) {
                            $existent[0]->delete();

                            $event = new Event($event_raw);
                            $event->save();

                            if (!$updated) {
                                $updated = true;
                            }
                        }
                    }
                    else {
                        $event = new Event($event_raw);
                        $event->save();
                        if (!$updated) {
                            $updated = true;
                        }
                    }
                }
            }
        }
        return $updated;
    }

    public function get_updates()
    {
        $events = Event::where('seen', 0)->get()->makeHidden(['date', 'course', 'speciality', 'faculty', 'seen']);
        $events = $events->groupBy('speciality');
        foreach ($events as $k1 => $speciality) {
            $events[$k1] = $speciality->groupBy('date');
            foreach ($events[$k1] as $k2 => $date) {
                $events[$k1][$k2] = $date->groupBy('course');
            }
        }
        return response()->json(['updates' => $events->toArray()]);
    }

    public function acquire_updates(Request $request)
    {
        $ids = json_decode($request->ids);
        $events = Event::find($ids)->all();
        foreach ($events as $key => $event) {
            $event->seen = 1;
            $event->save();
        }
        return response()->json(['success' => 'true']);
    }

    // public function schedule_view($speciality, $course, Request $request)
    // {
    //     $week = request('week', Carbon::now()->weekOfYear);
    //     $now = Carbon::now();

    //     $week_base = $now->copy()->setISODate($now->year, $week);
    //     $start = $week_base->copy()->startOfWeek();
    //     $end = $week_base->copy()->endOfWeek();

    //     $events = Event::where([['speciality', $speciality], ['course', $course]])/*->where('date', '>=', $start)->where('date', '<=', $end)*/->get();
    //     $events = $events->groupBy('date')->toArray();

    //     $timings = [
    //         ['9:00:00', '10:20:00'],
    //         ['10:30:00', '11:50:00'],
    //         ['12:10:00', '13:30:00'],
    //         ['14:00:00', '15:20:00']
    //     ];

    //     foreach ($events as $k1 => $day) {
    //         foreach ($day as $k2 => $event) {
    //             if ((!$event['title']) && (!$event['subtitle'])) {
    //                 unset($events[$k1][$k2]);
    //             }
    //             else {
    //                 if ($event['index']) {
    //                     $events[$k1][$k2]['start'] = $timings[$event['index']-1][0];
    //                     $events[$k1][$k2]['end'] = $timings[$event['index']-1][1];
    //                 }
    //             }
    //         }
    //     }

    //     return view('schedule', ['schedule' => $events]);
    // }

    public function get_schedule(Request $request)
    {
        $events = Event::where([['speciality', $request->speciality], ['course', $request->course]])->where('date', Carbon::parse($request->date)->toDateString())->get();
        $events = $events->sortBy('index')->toArray();
        return response()->json(['success' => true, 'schedule' => array_values($events)]);
    }

    public function generate_ical($speciality, $course, Request $request)
    {
        $events_db = Event::where([['speciality', $speciality], ['course', $course]])->get()->toArray();
        $events = [];

        $timings = [
            ['9:00:00', '10:20:00'],
            ['10:30:00', '11:50:00'],
            ['12:10:00', '13:30:00'],
            ['14:00:00', '15:20:00']
        ];

        foreach ($events_db as $key => $event) {
            if ((!$event['title']) && (!$event['subtitle'])) {
                unset($events_db[$key]);
            }
            else {
                if ($event['index']) {
                    $events_db[$key]['start'] = $timings[$event['index']-1][0];
                    $events_db[$key]['end'] = $timings[$event['index']-1][1];
                }
            }
        }
        foreach ($events_db as $key => $event) {
            $events[] = EventCal::create()
                ->name($event['title'])
                ->description($event['subtitle'])
                ->startsAt(Carbon::parse($event['date'])->setTimeFromTimeString($event['start']))
                ->endsAt(Carbon::parse($event['date'])->setTimeFromTimeString($event['end']));
        }

        $calendar = Calendar::create(sprintf('Расписание по специальности "%s" для %d курса', DataSource::where('speciality', $speciality)->get()->first()->title, $course))->refreshInterval(60)->withTimezone()->event($events);

        return response($calendar->get())
            ->header('Content-Type', 'text/calendar')
            ->header('charset', 'utf-8');
    }

    public function schedule_feed($speciality, $course, Request $request)
    {
        $events = Event::where([['speciality', $speciality], ['course', $course]])->where('date', '>=', Carbon::parse($request->start)->toDateString())->where('date', '<=', Carbon::parse($request->end)->toDateString())->get();
        $events = $events->toArray();

        $timings = [
            ['9:00:00', '10:20:00'],
            ['10:30:00', '11:50:00'],
            ['12:10:00', '13:30:00'],
            ['14:00:00', '15:20:00']
        ];

        foreach ($events as $k => $event) {
            if ((!$event['title']) && (!$event['subtitle'])) {
                unset($events[$k]);
            }
            else {
                if ($event['index']) {
                    $events[$k]['start'] = $timings[$event['index']-1][0];
                    $events[$k]['end'] = $timings[$event['index']-1][1];
                }
            }
        }

        foreach ($events as $key => $event) {
            $start = Carbon::parse($event['date'] . ' ' . $event['start']);
            $end = Carbon::parse($event['date'] . ' ' . $event['end']);
            $events[$key] = [
                'title' => $event['title'],
                'description' => $event['subtitle'],
                'start' => $start->getPreciseTimestamp(3),
                'end' => $end->getPreciseTimestamp(3),
            ];
        }

        return response()->json(array_values($events));
    }

    public function add_event($speciality, $course, Request $request)
    {
        $faculty = Event::where('speciality', $speciality)->first()->faculty;
        $event_raw = [
            'date' => Carbon::parse($request->date),
            'index' => $request->index,
            'faculty' => $faculty,
            'course' => $course,
            'speciality' => $speciality,
        ];
        $existent = Event::where($event_raw)->get();

        if (!$existent->isEmpty()) {
            $existent[0]->delete();
        }
        $event_raw['title'] = $request->title;
        $event_raw['subtitle'] = $request->subtitle;

        $event = new Event($event_raw);
        $event->save();

        return response()->json(['success' => true]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DataSource  $dataSource
     * @return \Illuminate\Http\Response
     */
    public function show(DataSource $dataSource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DataSource  $dataSource
     * @return \Illuminate\Http\Response
     */
    public function edit(DataSource $dataSource)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DataSource  $dataSource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DataSource $dataSource)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DataSource  $dataSource
     * @return \Illuminate\Http\Response
     */
    public function destroy(DataSource $dataSource)
    {
        //
    }
}
