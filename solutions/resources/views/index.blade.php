@extends('layouts.app')
@section('content')
{{--  add task modal  --}}
<div class="modal" id="addTaskModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Task Description</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <ul id="errorList"></ul>
          <div class="form-group mb 3">
              <label for="">Task Details</label>
              <input type="text" class="name form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary add_task">Add Task</button>
        </div>
      </div>
    </div>
  </div>
  {{--  end add task modal  --}}
  {{--  edit task modal  --}}
  <div class="modal" id="editTaskModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit & Update Task</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <ul id="errorList"></ul>

          <div class="form-group mb 3">
            <input type="hidden" id="task_id">
              <label for="">Task Details</label>
              <input type="text" id="new_name" class="name form-control">
              <label for="">Status:</label>
              <span>Completed</span><input type="checkbox" id="new_status">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary edit_task">Edit Task</button>
        </div>
      </div>
    </div>
  </div>
  {{--  end edit task modal  --}}
  {{--  delete task modal  --}}
  <div class="modal" id="deleteTaskModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete Task </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <ul id="errorList"></ul>
          <input type="hidden" id="delete_id">
          <div class="form-group mb 3">
              <h3>Are you sure, you want to delete this task?</h3>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-danger delete_task">Delete</button>
        </div>
      </div>
    </div>
  </div>
  {{--  end delete task modal  --}}
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>All Task List
                        <a href="#" data-bs-toggle="modal" data-bs-target="#addTaskModal" class="btn btn-primary btn-sm">Add Task</a>
                        <a href="#" data-bs-toggle="modal" class="all_task btn btn-success btn-sm">Show All Task</a>

                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="success_message"></div>
 <table class="table table-hover">
    <thead>
        <tr>
          <th scope="col">S.No.</th>
          <th scope="col">Task Name</th>
          <th scope="col">Status</th>
          <th scope="col">Edit</th>
          <th scope="col">Delete</th>

        </tr>
      </thead>
      <tbody>

      </tbody>
  </table>


@endsection

@section('script')
<script>
$(document).ready(function(){
tasklist();
function tasklist()
        {
            $.ajax({
                type:"GET",
                url:"/tasklist",
                datatype:"json",
                success: function(response){
                    $('tbody').html("");
                    $.each(response.task, function(key,item){
                        $('tbody').append(
                            '<tr>\
                                <td>'+item.id+'</td>\
                                <td>'+item.name+'</td>\
                                <td>Not Completed</td>\
                                 <td><button type="button" value="'+item.id+'" class="edit btn btn-primary btn-sm">Edit</button></td>\
                                 <td><button type="button" value="'+item.id+'" class="delete btn btn-danger btn-sm">Delete</button></td>\
                              </tr>');

                    })
                  }
            });
        }
$(document).on('click','.all_task',function(e){
    alltasklist();
    function alltasklist()
            {
                $.ajax({
                    type:"GET",
                    url:"/alltasklist",
                    datatype:"json",
                    success: function(response){
                        $('tbody').html("");
                        $.each(response.task, function(key,item){
                            $('tbody').append(
                                '<tr>\
                                    <td>'+item.id+'</td>\
                                    <td>'+item.name+'</td>\
                                    <td>'+item.status+'</td>\
                                  </tr>');

                        });
                      }
                });
            }
});


$(document).on('click','.edit',function(e){
e.preventDefault();
var id= $(this).val();
$('#editTaskModal').modal('show');

$.ajax({
    type:"GET",
    url:"/editTask/"+id,
    success:function(response)
    {
        if(response.status==404)
        {
           $('#success_message').alertClass('alert alert-danger');
           $('#success_message').text(response.message);
        }
        else{
            $('#task_id').val(id);
            $('#new_name').val(response.task.name);
            $('#new_status').val(response.task.status);
        }
    }
    });
});

$(document).on('click','.edit_task', function(e){
e.preventDefault();
 var task_id= $('#task_id').val();
 var data={
'name': $('#new_name').val(),
'status': $('#new_status').val(),
}
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$.ajax({
type:"PUT",
url:"/updatetask/"+task_id,
data:data,
datatype:"json",
success: function(response){
    if(response.status == 400)
    {
                $('#errorList').html("");
                $('#errorList').addClass('alert alert-danger');
                $.each(response.errors, function(key,err_values){
                $('#errorList').append('<li>'+err_values+'</li>');                });
    }
    else(response.status==200)
    {
            $('#updateerrorList').html("");
            $('#success_message').html("");
            $('#success_message').addClass('alert alert-success');
            $('#success_message').text(response.message);
            $('#editTaskModal').modal('hide');
            tasklist();

    }
}
});

});


$(document).on('click','.delete',function(e){
    e.preventDefault();
    var id= $(this).val();
    $('#delete_id').val(id);
    $('#deleteTaskModal').modal('show');
    });
    $(document).on('click','.delete_task',function(e){
        e.preventDefault();
        var id= $('#delete_id').val();
        console.log(id);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"DELETE",
            url:"/delete/"+id,
            success:function(response){
                $('#success_message').addClass('alert alert-success');
                $('#success_message').text(response.message);
                $('#deleteTaskModal').modal('hide');
                tasklist();
            }

        });

    });

$(document).on('click','.add_task',function(e){
    e.preventDefault();
    var task= {
        'name':$('.name').val(),
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

   $.ajax({
    type:"post",
    url:"/addtask",
    data:task,
    datatype:"json",
    success:function(response)
    {
              if(response.status == 400)
            {
                $('#errorList').html("");
                $('#errorList').addClass('alert alert-danger');
                $.each(response.errors, function(key,err_values){
                $('#errorList').append('<li>'+err_values+'</li>');
                });
            }
            else
            {
            $('#errorList').html("");
            $('#success_message').addClass('alert alert-success');
            $('#success_message').text(response.message);
            $('#addTaskModal').modal('hide');
            $('#addTaskModal').find('input').val("");
            tasklist();
        }
    }
    });
});
});

</script>
@endsection
