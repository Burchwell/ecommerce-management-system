@include('checklists._partials.header')
<style>
    body {
        background: #dfdfdf;
    }

    h1 {
        font-size: 1.75rem;
    }

    h2 {
        font-size: 1.5rem;
    }

    h3 {
        font-size: 1.25rem;
    }



    .container {
        background: #f9f9f9;
        padding-bottom: 3rem;
    }
</style>
<div class="row">
    <div class="col col-sm-12 p-0">
        <div class="table-responsive">
            <table class="table table-striped hide-mobile">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                @forelse($checklists as $checklist)
                    <tr>
                        <td>
                            <a href="/warehouse/eodchecklist/{{$checklist->getKey()}}"
                               class="btn btn-dark">Complete EOD</a>
                        </td>
                        <td class="text-left p-2 pt-3">{{\Carbon\Carbon::parse(str_replace("checklist_", "", $checklist->name))->format('F jS, Y')}}
                        </td>
                        <td class="text-right p-2">
                            <a href="javascript:;" data-href="/warehouse/eodchecklist/view/{{$checklist->getKey()}}"

                               class="btn btn-dark mgrAccess">MGR</a>
                        </td>
                    </tr>
                @empty
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@include('checklists._partials.footer')
