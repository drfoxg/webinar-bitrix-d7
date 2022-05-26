<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?>Hello world<br>
<?$APPLICATION->IncludeComponent(
	"drfoxg:webinar",
	"",
	Array(
		"INFOBLOCKID" => "1",
		"MONTHS" => array("1","10","11","12"),
		"THEMES" => "1,2"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>