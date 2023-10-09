@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Search Engine') }}</div>

                <div class="card-body">
                    
                    <form action="{{ route('search')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-11">
                                <input type="text" id="keyword" class="form-control" name="keyword">
                            </div>
                            <div class="col-md-1">
                                <input type="submit" id="submit" class="btn btn-success" value="Search">
                            </div>
                        </div>
                       
                    </form>
                    
                </div>
            </div>
        </div>
        <div class="row mt-5" id="ajax_content">

        </div>
    </div>
</div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
        $(document).on('click','#submit',function(e){
            e.preventDefault();
            let keyword = $('#keyword').val()
            const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
            $.ajax({
                url: "{{ route('search')}}", // Adjust with your actual endpoint
                type:'post',
                data: {
                    _token: csrfToken,
                    keyword: keyword
                },
                success: function(data) {
                    // Update the #results div with the new data
                    $("#ajax_content").html(data);
                }
            });
        })
        
    
});
    </script>
@endsection

