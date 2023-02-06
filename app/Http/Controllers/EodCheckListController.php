<?php

namespace App\Http\Controllers;

use App\Models\Warehouse\EodChecklist;
use App\Models\Warehouse\EodChecklistEodTask;
use App\Models\Warehouse\EodTask;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class EodCheckListController
 * @package App\Http\Controllers
 */
class EodCheckListController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        $today = EodChecklist::where('name', '=', "checklist_" . Carbon::now()->format('Y-m-d'))->first();

        if (!$today) {
            EodChecklist::create(['name'=> "checklist_" . Carbon::now()->format('Y-m-d')]);
        }
        $checklists = (EodChecklist::with(['completed_tasks'])->get())->sortBy('id', null, true)->take(20);

        if (\request()->expectsJson()) {

            return response()->json($checklists);
        }
        return view('checklists.EodChecklists', compact('checklists'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|RedirectResponse|\Illuminate\View\View
     */
    public function store(EodChecklist $checklist, Request $request)
    {
        $tasks = $request->get('task');
        $tasksDB = [];

        foreach ($tasks as $task) {
            EodChecklistEodTask::create([
                'eod_checklist_id' => $checklist->getKey(),
                'eod_task_id' => $task,
                'completedBy' => $request->get('completedBy')
            ]);

            $tasksDB[] = EodTask::find($task)->description;
        }

        // Send Task Completed to Zapier
        $client = new Client();
//        $headers = [
//            "Accept" => 'application/json',
//            "Content-Type" => 'application/json'
//        ];

        $response = $client->post('https://hooks.zapier.com/hooks/catch/7568449/ozcdksq/', ['json' => [
                "tasks" => $tasksDB,
                'completed_by' => $request->get('completedBy')
        ], 'verify' => false]);



        if ($response->getStatusCode() !== 200) {
            throw new Exception('Error notifying Zapier.');
        }

        if ($request->expectsJson()) {
            return $this->index();
        }

        return response()->redirectTo("/warehouse/eodchecklist/{$checklist->getKey()}");
    }

    public function create(Request $request) {

    }

    /**
     * @param $date
     */
    public function show($checklist, Request $request)
    {
        $checklist = EodChecklist::find($checklist);

        if ($request->expectsJson()) {
            $availableTasks = EodTask::all();

            $ctn = optional($checklist)->completed_tasks->pluck('name')->toArray();

            $missedTasks = [];
            foreach ($availableTasks as $task) {
                if (!in_array($task->name, $ctn, true)) {
                    $missedTasks[] = $task;
                }
            }

            $checklist->missed_tasks = $missedTasks;

            return response()->json(compact('checklist'));
        }
        return view('checklists.EodChecklistView', compact('checklist'));
    }

    /**
     * @param $date
     */
    public function showByDate($date, Request $request)
    {
        $checklist = EodChecklist::with('completed_tasks')
            ->where('created_at', 'LIKE', Carbon::parse($date)->format("Y-m-d")."%")
            ->first();

        if ($request->expectsJson()) {
            $availableTasks = EodTask::all();

            $ctn = optional($checklist)->completed_tasks->pluck('name')->toArray();

            $missedTasks = "";
            foreach ($availableTasks as $task) {
                if (!in_array($task->name, $ctn, true)) {
                    $missedTasks .= sprintf("- %s.\r\n", $task->description);
                }
            }

            return response()->json(compact('missedTasks'));
        }
        return view('checklists.EodChecklistView', compact('checklist'));
    }

    public function complete($checklist, Request $request) {
        $checklist = EodChecklist::find($checklist);
        $tasks = $request->get('task');

        foreach ($tasks as $task) {
            EodChecklistEodTask::create([
                'eod_checklist_id' => $checklist->getKey(),
                'eod_task_id' => (int) $task,
                'completedBy' => 'Manager'
            ]);
        }
        return view('checklists.EodChecklistView', compact('checklist'));
    }

    public function incomplete($checklist, Request $request) {
        $checklist = EodChecklist::find($checklist);
        $tasks = $request->get('task');
        $ctasks = $checklist->completed_tasks()->detach($tasks);
        return view('checklists.EodChecklistView', compact('checklist'));
    }

    /**
     * @param $date
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function edit($checklist, Request $request)
    {
        if (strpos($checklist, '-')) {
            $checklist = EodChecklist::where('name', '=', "checklist_{$checklist}")->first();
            if (!$checklist) {
                $checklist = EodChecklist::create(['name' => "checklist_{$checklist}"]);
            }
        }

        $checklist = EodChecklist::find($checklist);
        if ($request->expectsJson()) {
                return response()->json($checklist);
        }

        return view('checklists.EodChecklist', compact('checklist'));
    }

    /**
     * @param $date
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($date, Request $request)
    {
        if ($request->expectsJson()) {
            $name = "checklist_" . Carbon::parse($date)->format('Y-m-d');
            $checklist = EodChecklist::where('name', '=', $name);
            EodChecklist::destroy($checklist->getKey());

            return response()->json('Checklist deleted.');
        }
    }
}
