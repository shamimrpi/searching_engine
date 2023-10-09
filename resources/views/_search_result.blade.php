@if (!empty($results) && count($results) > 0)
    
    @foreach ($results as $data)
    <div class="col-md-12 mt-3" >
        <h3>{{$data->title}}</h3>
        <p>{{$data->body}}</p>
    </div>
        
    @endforeach
     
         
 @else
     <div class="alert alert-info">No records found!</div>
 @endif
