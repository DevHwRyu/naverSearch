'use strict';

var searchApp = angular.module('searchApp',['ngRoute','ngCkeditor','googlechart','ui.grid']);

searchApp.config(['$routeProvider','$httpProvider','$provide',function($routeProvider,$httpProvider,$provide){
	$routeProvider.
	when('/',{
		templateUrl : 'templates/main.html',
		controller : 'mainCtrl'
	})
	.when('/kwdEdit',{
		templateUrl : 'templates/keywordEdit.html',
		controller : 'keywordEditCtrl'
	})
	.when('/kwdCollection',{
		templateUrl : 'templates/keywordCollection.html',
		controller : 'keywordCollectionCtrl'
	})
	.when('/kwdView/:pageNo',{
		templateUrl : 'templates/keywordView.html',
		controller : 'keywordViewCtrl'
	})
	.when('/sales/refundList',{
		templateUrl : 'templates/sales/refundList.html',
		controller : 'refundListCtrl'
	})
	.otherwise({redirectTo:'/'});

	$httpProvider.defaults.headers.post['Content-Type'] = "application/x-www-form-urlencoded";

	//IE 한글 입력 오류 관련
	$provide.decorator('inputDirective', function($delegate, $log) {
		//$log.debug('Hijacking input directive');
		var directive = $delegate[0];
		angular.extend(directive.link, {
			post: function(scope, element, attr, ctrls) {
				element.on('compositionupdate', function (event) {
					element.triggerHandler('compositionend');
				})
			}
		});
		return $delegate;
	});
}]);

searchApp.controller('indexCtrl', function($scope, $interval, $http){
	
	//left 설정
	$scope.$on("selectFlag",function(e,v){

		$scope.leftMenu = v;
		
		//$interval($scope.loginStay, (1000 * 60 * 5)); //5분당 1회씩 호출
	});
	
	/*
	$scope.$on("setTopMenu",function(e,v){
		$scope.topMenu = v;
		
		$interval($scope.loginStay, (1000 * 60 * 5)); //5분당 1회씩 호출
	});
	*/
	$scope.isIE = getBrowserType().indexOf("IE")>-1;

	$scope.comma = function(str) {
		str = String(str);
		return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
	};

	//승차자 아이디 숨기기
	$scope.hideId = function(id){
		id = id.toString();
		var r= "";
		for(var i=0;i<id.length-2;i++){
			r=r+"*";
		}
		r = r+id.substr(-2);
		return r;
	};

	//승차자 이름 숨기기
	$scope.hideName = function(name){
		var r="";
		r=name.substr(0,1);
		for(var i=0;i<name.length-2;i++){
			r=r+"*";
		}
		r=r+name.substr(-1);
		return r;
	};

	//연락처 숨기기
	$scope.hideTel = function(tel){
		tel = tel.toString();
		var r= "";

		if(tel.indexOf("0")>0){
			tel = "0"+tel;
		}

		r = tel.substr(0,3);
		for(var i=0;i<tel.length-7;i++){
			r=r+"*";
		}
		r=r+tel.substr(-4);
		return r;
	};
});