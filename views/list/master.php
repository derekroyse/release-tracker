<table id='releaseTable' class="table">
<thead>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Release Date</th>
        <th>Type</th>
        <th>Platform</th>
    </tr>
</thead>
  <tr>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
  </tr>
<tbody></tbody>
</table>

<script type="text/javascript">  
  $(document).ready(function() {
    var releaseTable = $('#releaseTable').dataTable({
        aLengthMenu: [
        [25, 50, 100, 200, -1],
        [25, 50, 100, 200, "All"]],
        "processing": true,
        "ajax": {
            "url": "/lib/masterList.php",
            "type": "POST",
            "dataType": 'json',
        },                              
        "columns": [
            { "data": "id" },
            { "data": "title"},
            { "data": "release_date"},
            { "data": "type"},
            { "data": "platform"},
        ],
        createdRow: function( row, data, dataIndex ) {
          if (data.type == 'Movie (Theatrical Release)'){
            $( row ).addClass('table-primary');
          } else if (data.type == 'Video Game'){
            $( row ).addClass('table-success');
          }
        }
    });
  });
</script>