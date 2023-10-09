

<div class="table responsive" style="width:100%">
    <table class="table table-bordered">
        <thead class="table-dark">
            <th>SL</th>
            <th>User</th>
            <th>Keyword</th>
            <th>Searching Date Time</th>
            <th>Result</th>
        </thead>
        @foreach($searchHistories as $key=> $history)
        <tr>
            <td>{{ $key+1}}</td>
            <td>{{ $history->user->name ?? ''}}</td>
            <td>{{ $history->keyword }}</td>
            <td>{{ $history->searched_at }}</td>
            <td> {{ $history->result == 0 ? 'No':'Yes' }}</td>
        </tr>
        @endforeach
    </table>
   
</div>