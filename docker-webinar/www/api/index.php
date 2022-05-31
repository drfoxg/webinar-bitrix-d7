23<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?>
<?$APPLICATION->IncludeComponent(
	"drfoxg:webinar",
	"",
	Array(
		"INFOBLOCKID" => "2",
		"MONTHS" => [],
		"THEMES" => ""
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>