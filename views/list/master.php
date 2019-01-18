<table id='releaseTable'>
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
        "processing": true,
        "serverSide": true,
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
        ]
    });
  });
</script>