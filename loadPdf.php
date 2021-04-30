<?php
if($_GET['pid'] == "" || $_GET['record'] == "" || $_GET['field'] == "") {
	return false;
}
$fieldData = \REDCap::getData(["project_id" => $_GET['pid'], "records" => $_GET['record'],"fields" => $_GET['field'],"return_format" => "json"]);

if($fieldData) {
	list($fieldData) = json_decode($fieldData,true);
}

$sql = "SELECT stored_name, file_extension
		FROM redcap_edocs_metadata
		WHERE project_id = ?
			AND doc_id = ?";

$q = $module->query($sql,[$_GET['pid'],$fieldData[$_GET['field']]]);

if($row = db_fetch_assoc($q)) {
	if($row['file_extension'] != "pdf") {
		echo "This is not a valid PDF document";
	}
	else {
		header("Content-type:application/pdf");
		readfile(EDOC_PATH.$row['stored_name']);
	}
}
		