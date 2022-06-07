<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wama Software</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>            
    <script src="https://markcell.github.io/jquery-tabledit/assets/js/tabledit.min.js"></script>
  </head>
  <body>
    <div class="container">
      <br />
      <h3 align="center">Wama Software</h3>
      <br />
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><span>Employee Data</span><span class="pull-right"><button id="addNew" class="btn btn-sm btn-primary">Add New</button> | <button class="btn btn-sm btn-danger delete-all">Bulk Delete</button></span></h3>
        </div>
        <div class="panel-body">
          <div id="empFormDiv">
            <form id="empForm" name="empForm" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-group">
              <label for="name" class="col-md-12">Employee Name</label>
              <div class="col-md-12">
                <input type="text" class="form-control" id="txtEname" name="txtEname" placeholder="Enter Name" value="" maxlength="50">
                <span class="text-danger error" id="nameError"></span>
              </div>
            </div>
            <div class="form-group">
              <label for="name" class="col-md-12">Contact No</label>
              <div class="col-md-12">
                <input type="text" class="form-control" id="txtCno" name="txtCno" placeholder="Enter Contact Number" value="" maxlength="12" >
                <span class="text-danger error" id="contactError"></span>
              </div>
            </div>
          <div class="form-group">
            <label for="txtHobby" class="col-md-12">Hobby:</label>
            <div class="col-md-12">
              <label><input type="checkbox" value="Programming" name="txtHobby[]"> Programming</label>
              <label><input type="checkbox" value="Games" name="txtHobby[]"> Games</label>
              <label><input type="checkbox" value="Reading" name="txtHobby[]"> Reading</label>
              <label><input type="checkbox" value="Photography" name="txtHobby[]"> Photography</label>
              <br><span class="text-danger error" id="hobbyError"></span>
            </div>
          </div>
          <div class="form-group">
            <label for="txtCategory" class="col-md-12">Category:</label>
            <div class="col-md-12">
              <select class="form-control" name="txtCategory" id="txtCategory">
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
              <span class="text-danger error" id="cateError"></span>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-12">Image</label>
            <div class="col-md-12">
              <input id="image" type="file" name="image" accept="image/*" onchange="readURL(this);">
              <input type="hidden" name="hidden_image" id="hidden_image">
              <span class="text-danger error" id="imgError"></span>
            </div>
          </div>
          <img id="modal-preview" src="https://via.placeholder.com/150" alt="Preview" class="form-group hidden" width="100" height="100">
          <div class="col-md-offset-2 col-md-10">
            <button type="submit" class="btn btn-primary" id="btn-save" value="create">Save changes
            </button>
            <button type="button" id="btnCancel" class="btn btn-default">Cancel</button>
          </div>
          </form>
          </div>


          <div id="empList">
          </div>
        </div>
      </div>
    </div>
  </body>
</html>

<script type="text/javascript">
  $(document).ready(function(){
    function get_emp_list(){
      $("#empFormDiv").hide();
      $('.error').text('');
      $('#modal-preview').addClass('hidden');

      $.ajax({
        type: 'get',
        url: '{{ route("employee-list") }}',
        dataType : 'HTML',
        beforeSend : function() {
          jQuery('#empList').html('<div class="row"><h1>Loading...</h1></div>');
        },
        success : function(data) {
          jQuery('#empList').show();
          jQuery('#empList').html(data);
        },
      });
    }
    get_emp_list();
    $("#addNew").click(function(){
      $("#empList").hide();
      $("#empFormDiv").show();
    });

    $("#btnCancel").click(function(){
      // get_emp_list();
      $("#empFormDiv").hide();
      jQuery('#empList').show();
      $('#empForm').trigger("reset");
      $('.error').text('');
      $('#modal-preview').addClass('hidden');
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

  });
</script>

<script type="text/javascript">

  var url = "{{ route('employee-store') }}";

  $('body').on('submit', '#empForm', function (e) {
  e.preventDefault();
  var actionType = $('#btn-save').val();
  $('#btn-save').html('Please Wait..');

  var formData = new FormData(this);

    $.ajax({
        type:'POST',
        url: url,
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: function(data) {
          $('#empForm').trigger("reset");
          $('.error').text('');
          $('#modal-preview').addClass('hidden');
          jQuery('#empList').show();
          $("#empFormDiv").hide();
          $('#btn-save').html('Save Changes');
          $.ajax({
            type: 'get',
            url: '{{ route("employee-list") }}',
            dataType : 'HTML',
            beforeSend : function() {
              jQuery('#empList').html('<div class="row"><h1>Loading...</h1></div>');
            },
            success : function(data) {
              jQuery('#empList').show();
              jQuery('#empList').html(data);
            },
          });
      },
      error: function(data){
        $('.error').text('');
        $('#imgError').text(data.responseJSON.errors.image);
        $('#cateError').text(data.responseJSON.errors.txtCategory);
        $('#nameError').text(data.responseJSON.errors.txtEname);
        $('#contactError').text(data.responseJSON.errors.txtCno);
        $('#hobbyError').text(data.responseJSON.errors.txtHobby);
        // console.log('Error:', data);
        $('#btn-save').html('Save Changes');
      }
    });
  });
  function readURL(input, id) {
    id = id || '#modal-preview';
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $(id).attr('src', e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
      $('#modal-preview').removeClass('hidden');
      $('#start').hide();
    }
  }
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#check_all').on('click', function(e) {
        if ($(this).is(':checked', true)) {
            $(".checkbox").prop('checked', true);
        } else {
            $(".checkbox").prop('checked', false);
        }
    });
    $('.checkbox').on('click', function() {
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $('#check_all').prop('checked', true);
        } else {
            $('#check_all').prop('checked', false);
        }
    });
    $('.delete-all').on('click', function(e) {
        var idsArr = [];
        $(".checkbox:checked").each(function() {
            idsArr.push($(this).attr('data-id'));
        });
        if (idsArr.length <= 0) {
            alert("Please select atleast one record to delete.");
        } else {
            if (confirm("Are you sure, you want to delete the selected record(s)?")) {
                var strIds = idsArr.join(",");
                $.ajax({
                    url: "{{ route('multiple-delete') }}",
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: 'ids=' + strIds,
                    success: function(data) {
                        if (data['status'] == true) {
                            $(".checkbox:checked").each(function() {
                                $(this).parents("tr").remove();
                            });
                            alert(data['message']);
                        } else {
                            alert('Whoops Something went wrong!!');
                        }
                    },
                    error: function(data) {
                        alert(data.responseText);
                    }
                });
            }
        }
    });
});
</script>