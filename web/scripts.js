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

                  "<p><a href='/user/" + copy[i].user_id + "'>" + copy[i].username + "</a> - "+ timeDifference(new Date(), new Date(copy[i].post_time)) + "&nbsp;&nbsp;" + copy[i].comment + "</p><br><br>" + "<div class='tag'></div>" +

                "</div>" +
                "<div class='row'>" +
                  "<button type='button' name='reply-button' class='reply-button button-add' value='" + copy[i].comment_id + "'><img src='/img/new_comment.png'></button>" +
                  "<div class='reply-form'></div>" +
                "</div>" +
              "</div>" +
          "</div>"
          );

          // put a div in the comment that tags can be appended to
          var tags = copy[i].tags;
          tags = tags.split(" ");
          for(j=0;j<tags.length;j++){
            if(tags[j]!== ""){
              $(".tag").append(
                  "<span class='label label-warning'> " + tags[j] +"</span>"
              );
              console.log(tags[j]);
            }
          }
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

                  "<p><a href='/user/" + copy[i].user_id + "'>" + copy[i].username + "</a> - "+ timeDifference(new Date(), new Date(copy[i].post_time)) + "&nbsp;&nbsp;" + copy[i].comment + "</p><br><br>" + "<div class='tag'></div>" +

                "</div>" +
                  "<div class='row'>" +
                    "<button type='button' name='reply-button' class='reply-button button-add' value='" + copy[i].comment_id + "'><img src='/img/new_comment.png'></button>" +
                    "<div class='reply-form'></div>" +
                  "</div>" +
                "</div>" +
            "</div>"
          );

          // put a div in the comment that tags can be appended to
          var tags2 = copy[i].tags2;
          tags2 = tags2.split(" ");
          for(j=0;j<tags2.length;j++){
            if(tags2[j]!== ""){
                $(".tag").append(
                    "<span class='label label-warning'>" + tags2[j] +"</span>"
                );
              console.log(tags2[j]);
            }
          }
          added.push(copy[i]);
          copy.splice(i,1);
          loop();
        }
      }
    }
  }

// from http://stackoverflow.com/questions/6108819/javascript-timestamp-to-relative-time-eg-2-seconds-ago-one-week-ago-etc-best
  function timeDifference(current, previous) {

      console.log(current, previous);
      var msPerMinute = 60 * 1000;
      var msPerHour = msPerMinute * 60;
      var msPerDay = msPerHour * 24;
      var msPerMonth = msPerDay * 30;
      var msPerYear = msPerDay * 365;

      var elapsed = current - previous;

      if (elapsed < msPerMinute) {
           return Math.round(elapsed/1000) + ' seconds ago';
      }

      else if (elapsed < msPerHour) {
           return Math.round(elapsed/msPerMinute) + ' minutes ago';
      }

      else if (elapsed < msPerDay ) {
           return Math.round(elapsed/msPerHour ) + ' hours ago';
      }

      else if (elapsed < msPerMonth) {
          return 'approximately ' + Math.round(elapsed/msPerDay) + ' days ago';
      }

      else if (elapsed < msPerYear) {
          return 'approximately ' + Math.round(elapsed/msPerMonth) + ' months ago';
      }

      else {
          return 'approximately ' + Math.round(elapsed/msPerYear ) + ' years ago';
      }
  }

  // while(copy.length>0){
    loop();
  // }

  $(".reply-button").click(function(){
    $(this).next().append(
      '<form action="/category/' + category + '/'+ thread +'" method="post">' +
          '<input type="hidden" name="inputParent" value="'+ $(this).val() +'">' +
          '<textarea name="inputComment" rows="6" cols="90" placeholder="post a reply" required></textarea>' +
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
        '<button class="button-add"type="submit" name="edit-button"><img src="/img/edit.png"></button>' +
      '</form>'
    );
  });

});
