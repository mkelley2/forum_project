$(document).ready(function(){
  // comments.forEach(function(elem){
  //   if(elem.parent_id === null){
  //     $(".container").append("<div id="+ elem.comment_id + "><h1>" + elem.text + "</h1></div>");
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
  
  var copy = comments;
  var added =[];
  
  function loop(){
    for(i=0;i<copy.length;i++){
      if(copy[i].parent_id === null){
        $(".container").append("<div id="+ copy[i].comment_id + "><h1>" + "Score: " + copy[i].score + " - " + copy[i].text + "</h1></div>");
        added.push(copy[i]);
        copy.splice(i,1);
        loop();
        
      }else{
        if(search(copy[i].parent_id,added)){
          $("#"+copy[i].parent_id).append("<div id="+ copy[i].comment_id + "><h1>" + "Score: " + copy[i].score + " - " + copy[i].text + "</h1></div>");
          added.push(copy[i]);
          copy.splice(i,1);
          loop();
        }
      }
    }
  }

  while(copy.length>0){
    loop();
  }

});