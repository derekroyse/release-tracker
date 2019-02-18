<div id="main-content" class="container-fluid">
	<table id='releaseTable' class="table table-striped table-hover"></table>

	<?php
		if($_GET['type'] == 'master'){ 
			echo '<button type="button" class="btn btn-primary addToList">Add selected titles</button>';
		} else {
			echo '<button class="btn btn-danger removeFromList">Remove selected titles</button>';
		}
	?>
</div>

<!-- Begin Javascript -->
<script type="text/javascript">
	$(document).ready(function() {
	// Get current user id, if it exists.
		var currentUserID = "<?php 
			if(array_key_exists('logged_in', $_SESSION) && $_SESSION['logged_in']){ 
				echo $_SESSION['userID'];
			} else {
				echo null;
			}
		?>";
	// Get list type from url. 
		var listType = "<?php echo $_GET['type'];?>";
	// Datatables setup. 
		var releaseTable = $('#releaseTable').DataTable({
				aLengthMenu: [
					[25, 50, 100, 200, -1],
					[25, 50, 100, 200, "All"]],
				"processing": true,
				"paging": false,
				"autoWidth": false,
				"order": [[2, "asc"], [1, "asc"]],
				"searching": false,
				"fnDrawCallback": function(oSettings){
					if ( $("body").height() > $(window).height() ){
						$('footer').css("position", "relative");
					}
				},
				"ajax": {
						"url": "/lib/getList.php",
						"type": "POST",
						"dataType": 'json',
						"data": ({currentUserID, "listType": listType})
				},                              
				"columns": [
						{ title:"", "data": "id", "orderable": false, 
							render: function (data, type, row) {
								return '<input type="checkbox" value="' + data + '"></input>';
							}
						},
						{ title:"Title", "data": "title", className: "title-column",},
						{ title:"Release Date", "data": "release_date",
								render: function (data, type, row) {
									if(type == 'display'){
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
									} else {
										return data;
									}
							}
						},
						{ title:"Type", "data": "type", render: function (data, type, row) {
								return '<span class="btn platform-badge badge-' + data + '">' + data + '</span>';
							}
						},
						{ title:"Platform(s)", "data": "platform", render: function (data, type, row) {
								var returnString = "<span class='platform-badge-container'>";
								var platformList = data.split(" ");

								//loop through array, adding the appropriate color to each element and adding it to the return string
								platformList.forEach(function(platform){
									returnString += '<span class="btn platform-badge badge-' + platform + '">' + platform + '</span>';
								});

								return returnString + '</span>';
							}
						},
				],
				createdRow: function( row, data, dataIndex ) {
					// Color coded rows. Might reuse if badges don't work with expanded data set.
					// if (data.type == 'Movie'){
					//   $( row ).addClass('table-primary');
					// } else if (data.type == 'Video Game'){
					//   $( row ).addClass('table-success');
					// }
				}
		});

		$('thead').addClass('thead-dark');

	// Add titles button.
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
					 bootbox.alert(result);
					}
				});
			else
				bootbox.alert("Not logged in!");
		});

	// Remove titles button.
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
						bootbox.confirm(result, function(){
							window.location.reload();
						});
					}
				});
			else
				alert("Not logged in!");
		});

	// Filter panels work to be finished later.
		// $('.filtersButton').on('click', function () {
		// 	$('#filterPanel').toggle();
		// });

		// Disable sorting when clicking on the Title header, move it to the arrrows only.
		//$('#releaseTable thead th:nth-of-type(2)').append( '&nbsp;<input type="text" placeholder="Search Title" />' ).off();

		//$('#releaseTable thead .sorting ::before').on( 'click', function () {
		//  console.log('test');
		//});
		// On click filter button, look at which icons are checked and visible and filter accordingly
	});
</script>