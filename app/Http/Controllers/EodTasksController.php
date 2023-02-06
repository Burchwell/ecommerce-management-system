<?php

namespace App\Http\Controllers;

use App\Models\Warehouse\EodTask;
use Illuminate\Http\Request;

/**
 * Class EodTasksController
 * @package App\Http\Controllers
 */
class EodTasksController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tasks = EodTask::all();
        return response()->json($tasks);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $param = $request->except('_token');

        $task = EodTask::create([
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ]);

        return response()->json(compact('task'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $task = EodTask::where('id', $id);
        return response()->json(compact('task'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $task = EodTask::where('id', $id)->update([
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ]);

        $task->save();

        return response()->json(compact('task'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        EodTask::destroy($id);
        return response()->json("Task #{$id} deleted.");
    }
}
