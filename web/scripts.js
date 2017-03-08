$(document).ready(function(){
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
        $(".comment-holder").append("<div class='comment-parent' id='"+ copy[i].comment_id + "'><div class='row'><div class'col-sm-10'><input"
        + "type='hidden' name='currentUrl' value='" + window.location.pathname + "'><p>" + "Score:" +  copy[i].score + " - " + copy[i].comment + "</p></div><div class'col-sm-2'><form class='button-form' action='/score/" + copy[i].comment_id + "''method='post'> <button class='button-add' type='submit' name='like-button' value='1'><img src='/img/like.png'></button></form><form class='button-form' action='/score/" + copy[i].comment_id + "'method='post'> <button class='button-add' type='submit'" +
        "name='dislike-button' value='-1'><img src='/img/dislike.png'></button></form> </div> </div> </div>");
        added.push(copy[i]);
        copy.splice(i,1);
        loop();

      }else{
        if(search(copy[i].parent_id,added)){
          $("#"+copy[i].parent_id).append("<div class='comment' id='"+ copy[i].comment_id + "'><div class='row'><div class'col-sm-6'><input"
          + "type='hidden' name='currentUrl' value='" + window.location.pathname + "'><p>" + "Score:" +  copy[i].score + " - " + copy[i].comment + "</p></div><div class'col-sm-2'><form class='button-form' action='/score/" + copy[i].comment_id + "''method='post'> <button class='button-add' type='submit' name='like-button' value='1'><img src='/img/like.png'></button></form><form class='button-form' action='/score/" + copy[i].comment_id + "'method='post'> <button class='button-add' type='submit'" +
          "name='dislike-button' value='-1'><img src='/img/dislike.png'></button></form> </div> </div> </div>");
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
console.log(comments);
});
  }
// console.log(copy);

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

