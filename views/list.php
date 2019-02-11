<table id='releaseTable' class="table table-striped" style="width:100%">
</table>

<?php
  if($_GET['type'] == 'master'){ 
    echo '<button type="button" class="btn btn-primary addToList">Add selected titles to your list.</button>';
  } else {
    echo '<button class="btn btn-danger removeFromList">Remove selected titles from your list.</button>';
  }
?>

<script type="text/javascript">
  $(document).ready(function() { 
    var currentUserID = "<?php 
      if(array_key_exists('logged_in', $_SESSION) && $_SESSION['logged_in']){ 
        echo $_SESSION['userID'];
      } else {
        echo null;
      }
    ?>";
    var listType = "<?php echo $_GET['type'];?>";

    var releaseTable = $('#releaseTable').DataTable({
        aLengthMenu: [
        [25, 50, 100, 200, -1],
        [25, 50, 100, 200, "All"]],
        "processing": true,
        "paging": false,
        "order": [[3, "asc"]],
        "searching": false,
        "ajax": {
            "url": "/lib/getList.php",
            "type": "POST",
            "dataType": 'json',
            "data": ({currentUserID, "listType": listType})
        },                              
        "columns": [
            { title:"", "data": "id", "orderable": false, render: function (data, type, row) {
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
            { title:"Type", "data": "type", render: function (data, type, row) {
                return '<span class="btn platform-badge badge-' + data + '">' + data + '</span>';
              }
            },
            { title:"Platform(s)", "data": "platform", "width": 600, render: function (data, type, row) {
                var returnString = "";
                var platformList = data.split(" ");

                //loop through array, adding the appropriate color to each element and adding it to the return string
                platformList.forEach(function(platform){
                  returnString += '<span class="btn platform-badge badge-' + platform + '">' + platform + '</span>';
                });

                return returnString;
              }
            },
        ],
        createdRow: function( row, data, dataIndex ) {
          // Color coded rows.
          // if (data.type == 'Movie'){
          //   $( row ).addClass('table-primary');
          // } else if (data.type == 'Video Game'){
          //   $( row ).addClass('table-success');
          // }
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
        $(this).prop("checked", false);
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

    $('.filtersButton').on('click', function () {
      $('#filterPanel').toggle();
    });

    // Disable sorting when clicking on the Title header, move it to the arrrows only.
    //$('#releaseTable thead th:nth-of-type(2)').append( '&nbsp;<input type="text" placeholder="Search Title" />' ).off();

    //$('#releaseTable thead .sorting ::before').on( 'click', function () {
    //  console.log('test');
    //});

    // On click filter button, look at which icons are checked and visible and filter accordingly
  });
</script>