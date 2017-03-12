<?php
// ** key = 3d4b6928ff8a6d4fc081f443dee5b991
$array = [];
function extract_email_address($string) {
    $emails = [];
    $string = str_replace("\r\n", ' ', $string);
    $string = str_replace("\n", ' ', $string);

    foreach (preg_split('/ /', $string) as $token) {
        $email = filter_var($token, FILTER_VALIDATE_EMAIL);
        if ($email !== false) {
            $emails[] = $email;
        }
    }

    return $emails;
}

for ($i = 1; $i <= 11; $i++) {
    $handle   = file_get_contents("http://tuthucnguyenkhuyen.edu.vn/danh-sach-cuu-hoc-sinh-nvitt274p$i.htm", true);
    $array [] = extract_email_address(strip_tags($handle));
}
$count      = 1;
foreach ($array as $emails) {
    foreach ($emails as $email){
        echo $email . "</br>";
        $count++;
    }
}
echo "total $count";
?>
<!--<html>-->
<!--	<head>-->
<!--		<title>Teolo</title>-->
<!--		<meta charset="utf-8">-->
<!--		<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>-->
<!--		<script src="https://trello.com/1/client.js?key="></script>-->
<!--	</head>-->
<!--	<body>-->
<!--	--><?php
//	error_reporting(E_ALL);
//	ini_set('display_errors', TRUE);
//	ini_set('display_startup_errors', TRUE);
//
//	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
//
//	date_default_timezone_set('Europe/London');
//
//	/** Include PHPExcel_IOFactory */
//	require_once 'Classes/PHPExcel/IOFactory.php';
//	$fileName = "fb.xlsx";
// 	/** automatically detect the correct reader to load for this file type */
//	$excelReader = PHPExcel_IOFactory::createReaderForFile($fileName);
//
//	$excelObj = $excelReader->load($fileName);
//	$worksheet = $excelObj->getActiveSheet();
//	$lastRow = $worksheet->getHighestRow();
//	echo "<table>";
//	for ($row=1; $row <= $lastRow ; $row++) {
//		$text = $worksheet->getCell('A'.$row)->getValue();
//		if(trim($text)!=''){
//		echo "<tr><td class='sheet'>";
//		echo $worksheet->getCell('A'.$row)->getValue();
//		echo "</td></tr>";
//		}
//	}
//	echo "</table>";
//	 ?>
<!--	<div id="temp"></div>-->
<!--		<script type="text/javascript">-->
<!--			var authenticationSuccess = function() { console.log('Successful authentication'); };-->
<!--			var authenticationFailure = function() { console.log('Failed authentication'); };-->
<!--			Trello.authorize({-->
<!--				  type: 'popup',-->
<!--				  name: 'Getting Started Application',-->
<!--				  scope: {-->
<!--				    read: 'true',-->
<!--				    write: 'true' },-->
<!--				  expiration: 'never',-->
<!--				  success: authenticationSuccess,-->
<!--				  error: authenticationFailure-->
<!--				});	-->
<!--			// function addList(){-->
<!--			// 	var myList = '5873b5f00e1dcd46ff4e609d';-->
<!--			// 	var creationSuccess = function(data) {-->
<!--			// 	  console.log('Card created successfully. Data returned:' + JSON.stringify(data));-->
<!--			// 	};-->
<!--			// 	var listPeople = document.getElementsByClassName('sheet');-->
<!--				-->
<!--			// 		var newCard = {-->
<!--			// 		  name: listPeople[i].innerHTML, -->
<!--			// 		  desc: 'This is the description of our new card.',-->
<!--			// 		  // Place this card at the top of our list -->
<!--			// 		  idList: myList,-->
<!--			// 		  pos: 'top'-->
<!--			// 		};-->
<!--			// 		Trello.post('/cards/', newCard, creationSuccess);	-->
<!--			// 		}-->
<!--			// 	}-->
<!--				var i = 0;-->
<!--				var myList = '5858f5dd48196b0498b86b2e';-->
<!--				var listPeople = document.getElementsByClassName('sheet');-->
<!--				var creationSuccess = function(data) {-->
<!--				  console.log('Card created successfully. Data returned:' + JSON.stringify(data));-->
<!--				};-->
<!--				function ticker(){-->
<!--					if(i <= listPeople.length){-->
<!--					var newCard = {-->
<!--					  name: listPeople[i].innerHTML, -->
<!--					  desc: 'This is a People in contact List.',-->
<!--					  // Place this card at the top of our list -->
<!--					  idList: myList,-->
<!--					  pos: 'top'-->
<!--					};-->
<!--					Trello.post('/cards/', newCard, creationSuccess);	-->
<!--					}-->
<!--					i++;-->
<!--				}-->
<!--				-->
<!--				var	t = setInterval(ticker, 1000);-->
<!--					-->
<!--		</script>-->
<!--	</body>-->
<!--</html>-->
