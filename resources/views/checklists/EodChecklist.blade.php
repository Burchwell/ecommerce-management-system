@php
    $completedTasks = ($checklist && $checklist->completed_tasks) ? ($checklist->completed_tasks->pluck('name'))->toArray() : null;
@endphp
@include('checklists._partials.header')

<form action="/warehouse/eodchecklist/{{$checklist->getKey()}}" method="post" id="eodchecklist">
    @csrf
    <input hidden name="checklist" value="{{$checklist->id}}" />
    <div class="row mb-3">
        <div class="col-sm-12 bg-dark text-white mb-3">
            <h3 class="text-center">End Of Day Checklist</h3>
            <p class="text-center">{{\Carbon\Carbon::parse(str_replace("checklist_", "", $checklist->name))->format('F jS, Y')}}</p>
        </div>
    </div>

    @if (count($completedTasks) >= 9)
        <div class="jumbotron">
            <div class="row">
                <div class="col col-sm-12">
                    <h2 class="text-center">All Tasks Completed for today</h2>
                    <p class="text-center">Form is resetting at midnight!</p>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-sm-12">
                <h3>Task</h3>
            </div>
        </div>
        @foreach(\App\Models\Warehouse\EodTask::all() as $task)
            @if (is_null($completedTasks) || !in_array($task->name, $completedTasks))
                <div class="form-row">
                    <div class="form-group col-sm-12">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input task" id="{{$task->name}}" name="task[]" value="{{$task->id}}">
                            <label class="form-check-label" for="{{$task->name}}">{{$task->description}}</label>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
        <div class="row">
            <div class="col-sm-12">
                <h3>Completed By</h3>
            </div>
        </div>
        <div class="form-row" style="padding: 0;">
            <div class="form-group col-sm-12" style="padding: 0; background: #fff;">
                <select name="completedBy" id="completedby" size="12" required="" class="fsField fsRequired"
                        aria-required="true" required
                        style="width: 100%; height: 100%; padding: 10px; border: 0 none;"
                >
                    <option value="" selected class="text-center">-- Select Your Name --</option>
                    <option value="Thomas Fitzpatrick">Thomas Fitzpatrick</option>
                    <option value="Jakob Rosiek">Jakob Rosiek</option>
                    <option value="Cody Gyory">Cody Gyory</option>
                    <option value="Wendell Albury">Wendell Albury</option>
                    <option value="Jonathan Mateo">Jonathan Mateo</option>
                    <option value="Parker Spell">Parker Spell</option>
                    <option value="John Pigozzo">John Pigozzo</option>
                    <option value="Matthew Weisgal">Matthew Weisgal</option>
                    <option value="Evan Martin">Evan Martin</option>
                    <option value="Corey Neal">Corey Neal</option>
                    <option value="Chris Allaster">Chris Allaster</option>
                    <option value="Joe Allaster">Joe Allaster</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-sm-12">
                <button type="submit" class="btn btn-default btn-block">Submit Form</button>
            </div>
        </div>
    @endif
</form>

<script>
    $(function () {
        $("#completedby").on("click", function (e) {
            $(this).children('option:selected').removeAttr('selected');

        });

        $("#eodchecklist").on('submit', function (e) {
            e.preventDefault();
            var tasks = $(".task:checked").length;
            var employee = $("#completedby").val();
            console.log
            if (tasks > 0) {
                if (employee !== "") {
                    $("#eodchecklist")[0].submit();
                } else {
                    alert("Select your name.")
                }
            } else {
                alert("No task selected.");
            }

        })

        var current = $("#month").val();
        setDays(current);
        $('#month').on('change', function () {
            current = $(this).val();
            setDays(current);
        });
    });

    function setDays(month) {
        $("#days").html("");
        var year = new Date().getFullYear();
        for (var i = 1, days = new Date(year, month, 0).getDate(); i <= days; i++) {
            console.log(new Date().getDate())
            if (i == new Date().getDate()) {
                $("#days").append(new Option(i, i, true, true));
            } else {
                $("#days").append(new Option(i, i));
            }
        }
        $("#year").append(new Option(year, year)).attr('disabled', true)
    }

    function getDays(month) {

    }
</script>
@include('checklists._partials.footer')
