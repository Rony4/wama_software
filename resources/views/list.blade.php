<!-- <button style="margin: 5px;" class="btn btn-danger btn-xs delete-all" data-url="">Delete All</button> -->
<div class="table-responsive">
  @csrf
  <table id="editable" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th><input type="checkbox" id="check_all"></th>
        <th>ID</th>
        <th>Name</th>
        <th>Contact No</th>
        <th>Hobby</th>
        <th>Category</th>
        <th>Profile</th>
        <!-- <th>Action</th> -->
      </tr>
    </thead>
    <tbody>
      @foreach($data as $row)
      <tr>
        <td><input type="checkbox" class="checkbox" data-id="{{$row->id}}"></td>
        <td>{{ $row->id }}</td>
        <td>{{ $row->name }}</td>
        <td>{{ $row->phone }}</td>
        <td>{{ $row->hobby }}</td>
        <td>{{ $row->category->name }}</td>
        <td><img src="public/images/employee/{{ $row->profile_pic }}" width="60"></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<script type="text/javascript">
$(document).ready(function(){
   
  $.ajaxSetup({
    headers:{
      'X-CSRF-Token' : $("input[name=_token]").val()
    }
  });

  var categories = <?php echo json_encode($categories);?>;
  var categoriesRes = '';
  $.each(categories, function(key, element) {
      categoriesRes += '"'+element.id+'": "'+element.name+'",';
  });
  categoriesRes = categoriesRes.substring(0,categoriesRes.length-1);

  $('#editable').Tabledit({
    url:'{{ route("employee.action") }}',
    dataType:"json",
    columns:{
      identifier:[1, 'id'],
      editable:[[2, 'name'], [3, 'phone'], [4, 'hobby'], [5, 'category', '{'+categoriesRes+'}']]
    },
    restoreButton:false,
    onSuccess:function(data, textStatus, jqXHR)
    {
      if(data.action == 'delete')
      {
        $('#'+data.id).remove();
      }
    }
  });

    $('#check_all').on('click', function(e) {
        if ($(this).is(':checked', true)) {
            $(".checkbox").prop('checked', true);
        } else {
            $(".checkbox").prop('checked', false);
        }
    });

});  
</script>

