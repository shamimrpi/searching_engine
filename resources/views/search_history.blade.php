{{-- resources/views/search-history.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">

    <div class="filter-section">
        <div class="row">
            <div class="col-md-4">
                <h4>All Keywords:</h4>
        @foreach($keywords as $keyword => $count)
        <div>
            <input type="checkbox" class="filter" data-type="keyword" value="{{ $keyword }}">
            {{ $keyword }} ({{ $count }} times found)
        </div>
        @endforeach
            </div>
            <div class="col-md-2">
                <h4>All Users:</h4>
                @foreach($users as $user)
                <div>
                    <input type="checkbox" class="filter" data-type="user" value="{{ $user->id }}">
                    {{ $user->name }}
                </div>
                @endforeach
            </div>
            <div class="col-md-6">
                <h4>Time Range:</h4>
                <div>
                    <input type="checkbox" class="filter" data-type="time" value="yesterday"> See data from yesterday
                    <input type="checkbox" class="filter" data-type="time" value="last-week"> See data from last week
                    <input type="checkbox" class="filter" data-type="time" value="last-month"> See data from last month
                </div>

                <h4 class="mt-2">Select Date:</h4>
                <div>
                    <input type="date" id="startDate">
                    <input type="date" id="endDate">
                </div>
            </div>
        </div>
        

        

        

        
    </div>

    {{-- Display results here --}}
    <div id="ajax_content">
        
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
    </div>
</div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
    $(".filter").change(function() {
        let filters = {
            keyword: [],
            user: [],
            time: []
        };

        $(".filter:checked").each(function() {
            filters[$(this).data('type')].push($(this).val());
        });

        let startDate = $("#startDate").val();
        let endDate = $("#endDate").val();

        // Make AJAX call to your Laravel endpoint (you'd have to create this) with the filter data
        $.ajax({
            url: "{{route('search_history')}}", // Adjust with your actual endpoint
            data: {
                filters: filters,
                startDate: startDate,
                endDate: endDate
            },
            success: function(data) {
                // Update the #results div with the new data
                $("#ajax_content").html(data);
            }
        });
    });
});
    </script>
@endsection
