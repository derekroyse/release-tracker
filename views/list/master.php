<button class="addToList">Add selected titles to your list.</button>
<table id='releaseTable' class="table">
</table>
<button class="addToList">Add selected titles to your list.</button>
<?php echo date("Y-m-d")?>
<script type="text/javascript">  
  $(document).ready(function() {
    var releaseTable = $('#releaseTable').DataTable({
        aLengthMenu: [
        [25, 50, 100, 200, -1],
        [25, 50, 100, 200, "All"]],
        "processing": true,
        "paging": false,
        "order": [[3, "asc"]],
        "ajax": {
            "url": "/lib/masterList.php",
            "type": "POST",
            "dataType": 'json',
        },                              
        "columns": [
            { title:"ID", "data": "id", render: function (data, type, row) {
                return '<input type="checkbox" value="' + data + '"></input>';
              }
            },
            { title:"Title", "data": "title"},
            { title:"Release Date", 
                render: function (data, type, row) {
                  var jsDate = new Date(row.release_date);                  
                  var day = jsDate.getDate();
                  var month = jsDate.getMonth();
                  var year = jsDate.getFullYear();
                  var monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                  ];

                  switch(month){
                    case 1:
                    case 2:
                    case 3:
                      var quarter = '1';
                      break;
                    case 4:
                    case 5:
                    case 6:
                      var quarter = '2';
                      break;
                    case 7:
                    case 8:
                    case 9:
                      var quarter = '3';
                      break;
                    case 10:
                    case 11:
                    case 12:
                      var quarter = '4';
                      break;
                  }

                  switch(row.release_accuracy){
                    case 1:
                      return 'TBD';
                      break;
                    case 2:
                      return year;
                      break;
                    case 3:
                      return 'Q' + quarter + ' ' + year;
                      break;
                    case 4:
                      return monthNames[month] + ' ' + year;
                      break;
                    case 5:
                      return monthNames[month] + ' ' + day + ', ' + year;
                      break;
                  }
              }
            },
            {"data": "release_date", "visible":false}, 
            { title:"Type", "data": "type"},
            { title:"Platform(s)", "data": "platform"},
        ],
        createdRow: function( row, data, dataIndex ) {
          if (data.type == 'Movie'){
            $( row ).addClass('table-primary');
          } else if (data.type == 'Video Game'){
            $( row ).addClass('table-success');
          }
        }
    });

    $('.addToList').on( 'click', function () {
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
          url: "/lib/addToList.php",
          type: "POST",
          data: ({currentUserID, selectedArray}),
          success: function(result){
            alert(result);
          }
        });
      else
        alert("Not logged in!");
    });  
  });
</script>