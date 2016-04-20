<!DOCTYPE html>
<head>
	<title>phpExcel-test</title>
	<meta charset="UTF-8">
</head>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
<link rel="stylesheet" type="text/css" href="./media/css/jquery.dataTables.css">
<script type="text/javascript" language="javascript" src="./media/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" language="javascript" src="./media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="./media/js/jquery.jeditable.js"></script>
<body>

<table id="example" class="display" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>�û���</th>
				<th>������</th>
				<th>��ַ</th>
				<th>�绰</th>
				<th>����</th>
				<th>н��</th>
			</tr>
		</thead>
		<tbody>
			
		</tbody>
	</table>
	
</body>
	
	<script type="text/javascript" language="javascript" class="init">

		$(document).ready(function() {
			var mytable = $('#example').DataTable( {
				"searching":false,
		        "processing": true,
				"serverSide": true,
				"bDestory": true,
	        	"bRetrieve": true,
		        "ajax": {
				url: "./scripts/data.php",
				dataType: "json",
				},

		        columnDefs:[{
		            orderable:false,          //��������
		            targets:[0,1,2,3]   //ָ������
		       				}],
		       	order: [[ 0, "asc"]],

		       	"lengthMenu": [[10, 20, 30, 50], [10, 20, 30, 50]],

		        "language": {
		            "url": "./scripts/Chinese.json"
		        },
		    });    
		});
	</script>
</html>