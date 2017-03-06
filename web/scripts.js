$(document).ready(function() {

    $(".addCopies").click(function(){
        var total = $(".total").text();
        var available = $(".available").text();
        var checkedOut = $(".checkedOut").text();
        var id = $(this).val();
        console.log(id);

        $(this).parent().html(
            "<button class='btn btn-xs btn-warning tbl-edit' type='button'>Cancel</button>" +
            "<form class='form-group tbl-edit' action='/add-copies/" + id + "' method='post'>" +
            '<input type="hidden" name="_method" value="patch">' +
            "<input placeholder='# of new copies' type='number' min='0' name='inputTotal' id='newCopies'>" +
            "<button class='btn btn-xs btn-success' type='submit'>Add</button>" +
            "</form>"
        );

        // $(".total").html("<input type='text'>")

    });
  // $(".course-edit").click(function(){
  //   var status = $(this).parent().prev().text();
  //   var student_id = $(".student_id").text();
  //   console.log(status);
  //   $(this).parent().prev().html('<form class="form-group tbl-edit" action="/edit-course/' + $(this).val() + '" method="post">' +
  //     '<input type="hidden" name="_method" value="patch">' +
  //     '<input type="hidden" name="student_id" value="' + student_id + '">' +
  //     '<select name="courseStatus">' +
  //     '<option name="courseStatus" value="Drop">Drop</option>' +
  //     '<option name="courseStatus" value="Pass">Pass</option>' +
  //     '<option name="courseStatus" value="Fail">Fail</option>' +
  //     '</select>' +
  //     '<button type="submit" class="btn btn-xs btn-info" name="course-button">Submit Edit</button>' +
  //     '<button type="button" class="btn btn-xs btn-danger course-cancel" name="course-cancel">Cancel Edit</button>' +
  //   '</form>');
  //
  //   $(".course-cancel").click(function(){
  //     $(this).parent().html("<p><span class='status'>" + status + "</span></p>");
  //   });
  // });
  //
  // $(".student-edit").click(function(){
  //     var fName = $(".fName").text();
  //     var lName = $(".lName").text();
  //     var student_id = $(this).val()
  //   $(this).parent().prev().html('<form class="form-group tbl-edit" action="/edit-student/' + $(this).val() + '" method="post">' +
  //     '<input type="hidden" name="_method" value="patch">' +
  //     '<input type=text name="student_last_name" value="' + lName + '">' +
  //     '<input type=text name="student_first_name" value="' + fName + '">' +
  //     '<button type="submit" class="btn btn-xs btn-info" name="student-button">Submit Edit</button>' +
  //     '<button type="button" class="btn btn-xs btn-danger student-cancel" name="student-cancel">Cancel Edit</button>' +
  //   '</form>');
  //
  //   $(".student-cancel").click(function(){
  //     $(this).parent().html("<p><a href='/students/" + student_id + "'<span class='lName'>" + lName + "</span>, <span class='fName'>" + fName + "</span></a></p>");
  //   });
  // });


  // <form class="form-group tbl-edit" action="/add-copies/{{book.getId}}" method="post">
  //     <input type="hidden" name="_method" value="patch">
  //     <button type="submit" class="btn btn-xs btn-success" name="button">Add copies</button>
  // </form>

});
