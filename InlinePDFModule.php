<?php
namespace InlinePDFModule\InlinePDFModule;

use ExternalModules\ExternalModules;

class InlinePDFModule extends \ExternalModules\AbstractExternalModule {
	public function redcap_data_entry_form($project_id, $record, $instrument, $event_id, $group_id, $repeat_instance) {
		$forms = $this->getProjectSetting("output-form");
		$fields = $this->getProjectSetting("input-field");
		
		$fieldData = \REDCap::getData(["project_id" => $project_id, "records" => $record,"fields" => $fields,"return_format" => "json"]);
		
		if($fieldData) {
			list($fieldData) = json_decode($fieldData,true);
		}
		
		foreach($forms as $formKey => $thisForm) {
			if($thisForm == $instrument && $fieldData[$fields[$formKey]] != "") {
				$thisUrl = $this->getUrl("loadPdf.php?record=$record&field=".$fields[$formKey]);
				
			?>
				<script type="application/javascript">
					$("#formtop-div").append("<iframe src='<?=$thisUrl?>' width='100%' height='500px'></iframe>");
				</script>
			<?php
			}
		}
		
		
	}
}