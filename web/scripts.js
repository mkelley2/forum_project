$(document).ready(function(){
    $("#edit-user-bio").click(function() {
        $('#bio-form').show();
        $('#edit-user-bio').hide();
    });
    $("#btn-bio-cancel").click(function() {
        $('#edit-user-bio').show();
        $('#bio-form').hide();
    });
  // comments.forEach(function(elem){
  //   if(elem.parent_id === null){
  //     $(".comment-holder").append("<div id="+ elem.comment_id + "><h1>" + elem.text + "</h1></div>");
  //   }else{
  //     $("#"+elem.parent_id).append("<div id="+ elem.comment_id + "><h1>" + elem.text + "</h1></div>");
  //   }
  // });

  function search(nameKey, myArray){
    for (var i=0; i < myArray.length; i++) {
      if (myArray[i].comment_id === nameKey) {
        return myArray[i];
      }
    }
  }

  var copy = [];
  comments.forEach(function(elem){
    copy.push(JSON.parse(elem));
  });
  var added =[];

  function loop(){
    for(i=0;i<copy.length;i++){
      if(copy[i].parent_id == false){
        $(".comment-holder").append(
          "<div class='comment-parent panel-body' id='" + copy[i].comment_id + "'>" +
              "<div class='col-xs-1'>" +
                "<p class='pScore'>" + copy[i].score + "</p>" +
                "<form class='button-form' action='/score/" + copy[i].comment_id + "' method='post'>" +
                  "<input type='hidden' name='_method' value='patch'>" +
                  "<input type='hidden' name='inputScore' value='1'>" +
                  "<button class='button-add' type='submit' name='like-button'><img src='/img/like.png'></button>" +
                "</form>" +
                "<form class='button-form' action='/score/copy[i].comment_id'method='post'>" +
                  "<input type='hidden' name='_method' value='patch'>" +
                  "<input type='hidden' name='inputScore' value='-1'>" +
                  "<button class='button-add' type='submit' name='dislike-button'><img src='/img/dislike.png'></button>" +
                "</form>" +
              "</div>" +
              "<div class='col-xs-10'>" +
                "<div class='row'>" +
                  "<p><a href='/user/" + copy[i].user_id + "'>" + copy[i].username + "</a> - " + copy[i].comment + "</p><br><br>" + "<a>" + copy[i].tag + "</a>" +
                "</div>" +
                "<div class='row'>" +
                  "<button type='button' name='reply-button' class='reply-button' value='" + copy[i].comment_id + "'><img src='/img/new_comment.png'></button>" +
                  "<div class='reply-form'></div>" +
                "</div>" +
              "</div>" +
          "</div>"
          );
        added.push(copy[i]);
        copy.splice(i,1);
        loop();

      }else{
        if(search(copy[i].parent_id,added)){
          $("#"+copy[i].parent_id).append(
            "<div class='comment panel-body' id='" + copy[i].comment_id + "'>" +
                "<div class='col-xs-1'>" +
                  "<p class='pScore'>" + copy[i].score + "</p>" +
                  "<form class='button-form' action='/score/" + copy[i].comment_id + "' method='post'>" +
                    "<input type='hidden' name='_method' value='patch'>" +
                    "<input type='hidden' name='inputScore' value='1'>" +
                    "<button class='button-add' type='submit' name='like-button'><img src='/img/like.png'></button>" +
                  "</form>" +
                  "<form class='button-form' action='/score/copy[i].comment_id'method='post'>" +
                    "<input type='hidden' name='_method' value='patch'>" +
                    "<input type='hidden' name='inputScore' value='-1'>" +
                    "<button class='button-add' type='submit' name='dislike-button'><img src='/img/dislike.png'></button>" +
                  "</form>" +
                "</div>" +
                "<div class='col-xs-10'>" +
                  "<div class='row'>" +
                    "<p><a href='" + copy[i].user_id + "'>" + copy[i].username + "</a> - " + copy[i].comment + "</p>" +
                  "</div>" +
                  "<div class='row'>" +
                    "<button type='button' name='reply-button' class='reply-button' value='" + copy[i].comment_id + "'><img src='/img/new_comment.png'></button>" +
                    "<div class='reply-form'></div>" +
                  "</div>" +
                "</div>" +
            "</div>"
          );
          added.push(copy[i]);
          copy.splice(i,1);
          loop();
        }
      }
    }
  }



  // while(copy.length>0){
    loop();
  // }

  $(".reply-button").click(function(){
    $(this).next().append(
      '<form action="/category/' + category + '/'+ thread +'" method="post">' +
          '<input type="hidden" name="inputParent" value="'+ $(this).val() +'">' +
          '<textarea name="inputComment" rows="6" cols="130" placeholder="post a reply" required></textarea>' +
          '<button class="btn btn-primary" type="submit" name="button">Submit</button>' +
      '</form>'

    );

  });

  $(".editThreadBTN").click(function(){


    $(".editThread").empty();
    $(".editThread").html(
      '<form action="/edit-thread/' + thread + '" method="post">' +
        '<input type="hidden" name="_method" value="patch">' +
        '<input type="hidden" name="categoryName" value="'+ category +'">' +
        '<input type="text" name="inputPost">' +
        '<button type="submit" name="edit-button"><img src="/img/edit.png"></button>' +
      '</form>'
    );
  });

});
