<button class="removeFromList">Remove selected titles from your list.</button>
<table id='releaseTable' class="table">
</table>
<button class="removeFromList">Remove selected titles from your list.</button>

<script type="text/javascript">  
  $(document).ready(function() {
    var currentUserID = "<?php 
      if(array_key_exists('logged_in', $_SESSION) && $_SESSION['logged_in']){ 
        echo $_SESSION['userID'];
      } else {
        echo null;
      }
    ?>";

    var releaseTable = $('#releaseTable').DataTable({
        aLengthMenu: [
        [25, 50, 100, 200, -1],
        [25, 50, 100, 200, "All"]],
        "processing": true,
        "ajax": {
            "url": "/lib/getList.php",
            "type": "POST",
            "dataType": 'json',
            "data": ({currentUserID, "listType": "released"})
        },                              
        "columns": [
            { title:"ID", "data": "id", render: function (data, type, row) {
                return '<input type="checkbox" value="' + data + '"></input>';
              }
            },
            { title:"Title", "data": "title"},
            { title:"Release Date", "data": "release_date"},
            { title:"Type", "data": "type"},
            { title:"Platform(s)", "data": "platform"},
        ],
        createdRow: function( row, data, dataIndex ) {
          if (data.type == 'Movie'){
            $( row ).addClass('table-primary');
          } else if (data.type == 'Video Game'){
            $( row ).addClass('table-success');
          }
        },
        "language": {
          "emptyTable": "No results found."
        }
    });

    // Rework this to remove items from list.
      $('.removeFromList').on( 'click', function () {
        var currentUserID = "<?php 
          if(array_key_exists('logged_in', $_SESSION) && $_SESSION['logged_in']){ 
            echo $_SESSION['userID'];
          } else {
            echo null;
          }
        ?>";

        var selectedArray = [];

        $("input:checkbox:checked").each(function(){
          selectedArray.push($(this).val());
        });

        if (currentUserID)
          $.ajax({
            url: "/lib/removeFromList.php",
            type: "POST",
            data: ({currentUserID, selectedArray}),
            success: function(result){
              alert(result);
              window.location.reload();
            }
          });
        else
          alert("Not logged in!");
        });  
  });
</script>