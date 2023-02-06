@php
    $availableTasks = \App\Models\Warehouse\EodTask::all();
    $completedTasks = optional($checklist)->completed_tasks;
    $ctn = optional($checklist)->completed_tasks->pluck('name')->toArray();
    $missedTasks = [];
    foreach ($availableTasks as $task) {
        if (!in_array($task->name, $ctn)) {
            $missedTasks[] = $task;
        }
    }
@endphp
@include('checklists._partials.header')
<style>

</style>
<div class="row">
    <div class="col-sm-12 bg-dark text-white">
        <h3 class="text-center">Management View</h3>
        <p class="text-center">{{\Carbon\Carbon::now()->format('F jS, Y')}}</p>
    </div>
</div>
<div class="row mt-3">
    <div class="col-sm-12">
        <h3 class="text-danger">Incomplete Task</h3>
    </div>
</div>
<div class="row">
    <div class="col col-sm-12">
        <form action="/warehouse/eodchecklist/view/complete/{{$checklist->getKey()}}" method="post">
            @csrf
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th colspan="2" class="text-right">
                            <button class="btn btn-primary" type="submit">Mark Completed</button>
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    @forelse($missedTasks as $task)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input task" id="{{$task->name}}"
                                           name="task[]" value="{{$task->id}}">
                                    <label class="form-check-label" for="{{$task->name}}">&nbsp;</label>
                                </div>
                            </td>
                            <td>{{$task->description}}</td>
                        </tr>
                    @empty
                        <div class="row">
                            <div class="col col-sm-12">
                                <h1>No Completed Tasks found.</h1>
                            </div>
                        </div>
                    @endforelse
                </table>
            </div>
        </form>
    </div>
</div>
@if (count($completedTasks) !== 0)
<div class="row mt-3">
    <div class="col-sm-12">
        <h3 class="text-primary">Completed Task</h3>
    </div>
</div>
<div class="row">
    <div class="col col-sm-12">
        <div class="table-responsive">
            <form action="/warehouse/eodchecklist/view/incomplete/{{$checklist->getKey()}}" method="post">
                @csrf
                <table class="table table-striped">
                    <colgroup>
                        <col width="15%"/>
                        <col width="50%"/>
                        <col width="35%"/>
                    </colgroup>
                    <thead>
                    <tr>
                        <th colspan="3" class="text-right">
                            <button class="btn btn-danger" type="submit">Mark Inclomplete</button>
                        </th>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Description</th>
                        <th class="text-right">Completed</th>
                    </tr>
                    </thead>
                    @forelse($completedTasks as $task)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input task" id="{{$task->name}}"
                                           name="task[]"
                                           value="{{$task->id}}">
                                    <label class="form-check-label" for="{{$task->name}}">&nbsp;</label>
                                </div>
                            </td>
                            <td>{{$task->description}}</td>
                            <td class="text-right">{{ucwords($task->pivot->completedBy)}}</td>
                        </tr>
                    @empty
                </table>
                <div class="row">
                    <div class="col col-sm-12">
                        <h1>No Completed Tasks found.</h1>
                    </div>
                </div>
                @endforelse
                </table>
            </form>
        </div>
    </div>
</div>
@endif
<script>
    $(function () {
        $("#completedform")
        $.post('/incomplete/{{$checklist->getKey()}}',)
    })
</script>

@include('checklists._partials.footer')
