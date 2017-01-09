'use strict';

/**
 * 로그인 컨트롤러
 */

var searchLogin = angular.module('searchLogin',[]);
searchLogin.controller('loginCtrl',function($scope,$http){
	
	$scope.id="";
	$scope.pass="";
	
	$scope.fnLogin = function() {
		$http({
			method:'POST',
			url : '/action/action.php?ctrl=Index&action=loginProc',
			data : $.param({id:$scope.id,pass:$scope.pass}),
			headers : {'Content-Type': 'application/x-www-form-urlencoded'}
		})
		.success(function(data){
			if(data.result=="OK"){
				document.location.href="/index.php";
			} else {
				alert(data.resultMsg);
				if(data.result=="REQ_LOGIN"){
					document.location.href="/";
				}
			}
		})
		.error(function(){
			alert("서버와 통신중 오류가 발생되었습니다.");
		});
	};
		
	$scope.enterKey = function($event) {
		if ($event.keyCode == 13) {
			$scope.fnLogin();
		}
	};
});