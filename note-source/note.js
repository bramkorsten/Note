function getPostByTag(tag) {
  $.getJSON('note/note.php?tag=' + tag, function(data) {
        // set the html content of the id myThing to the value contained in data
        console.log(data);
     });
}

function getPostById(id) {
  console.log('note/note.php?post=' + id.toString());
  $.getJSON('note/note.php?post=' + id.toString(), function(data) {
        // set the html content of the id myThing to the value contained in data
        console.log(data);
     });
}

function getAllPosts() {
  $.getJSON('note/note.php?post=*', function(data) {
        // set the html content of the id myThing to the value contained in data
        console.log(data);
     });
}
