<?php 
	require 'libs/config.php';
	require 'libs/functions.php';
	
	//로그인 여부 체크
	loginCheck();
?>
<!DOCTYPE html>
<html>
<head>
	<? include 'include/commonHtmlHead.html'; ?>
	<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="/js/ng-ckeditor.js"></script>
	<script type="text/javascript" src="/js/ng-file-upload-shim.js"></script>
	<script type="text/javascript" src="/js/ng-file-upload.js"></script>
	<script type="text/javascript" src="/js/ng-google-chart.js"></script>
	
	<script type="text/javascript" src="/app/searchApp.js"></script>
	<script type="text/javascript" src="/js/modal-directive.js"></script>
	<script type="text/javascript" src="/js/excel-factory.js"></script>
	
	<script type="text/javascript" src="/app/mainCtrl.js"></script>
	<script type="text/javascript" src="/app/keywordEditCtrl.js"></script>
	<script type="text/javascript" src="/app/keywordCollectionCtrl.js"></script>
	<script type="text/javascript" src="/app/keywordViewCtrl.js"></script>
	
</head>
<body ng-app="searchApp" ng-controller="indexCtrl">
<div id="wrap">
	<div ng-include="'include/header.html'"></div>
	<div class="container-fluid">
		<div class="row">
			<div ng-include="'include/left.html'"></div>
			<div id="container" ng-view></div>		
		</div>
	</div>
	<!--
	<div id="footer_bg">
		<div id="footer">
			서울특별시 서초구 남부순환로 331길 10, 3층 (서초1동 1427-17 삼승B/D 3F) <br />
			Tel : 02-585-4550&nbsp;&nbsp;<span class="footer_address_bar">ㅣ</span>&nbsp;&nbsp;Fax : 02-6455-2093&nbsp;&nbsp;<span class="footer_address_bar">ㅣ</span>&nbsp;&nbsp;Email : ask@thunderds.co.kr&nbsp;&nbsp;<span class="footer_address_bar">ㅣ</span>&nbsp;&nbsp;사업자등록번호 : 214-88-46165
		</div>
		<div id="copyright">
			Copyright (c) 2009 Thunder Data Service Co., Ltd. All Right Reserved.
		</div>
	</div>
	-->
</div>
</body>
</html>