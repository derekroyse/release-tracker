<table id='releaseTable'>
<thead>
    <tr>
        <th>Test</th>
        <th>Test</th>
        <th>Test</th>
    </tr>
</thead>
  <tr>
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
            "url": "/lib/getUpdates.php",
            "type": "POST",
            "dataType": 'json',
        },                              
        "columns": [
            { "data": "id" },
            { "data": "name"},
            { "data": "release_dates[0].human"}
        ]
    });
  });
</script>