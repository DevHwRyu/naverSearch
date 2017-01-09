'use strict';

searchApp.controller('keywordCollectionCtrl',function($scope,$http){
	
	$scope.$emit("selectFlag", "3");
	
	var thisYear = new Date().getFullYear();
	var thisMonth = new Date().getMonth();
	var lastYear = new Date(thisYear-1,0,1).getFullYear();
	var nextYear = new Date(thisYear+1,0,1).getFullYear();
	
	$scope.yearList = [lastYear, thisYear, nextYear];
	$scope.monthList = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"];
	
		
	$scope.searchForm = {
		"year" : thisYear.toString(),
		"month" : (thisMonth+1).toString(),
		"status" : ""
	};
	
	$scope.keywordList = [];
	$scope.txt = {};
	$scope.stateType = {
		"Y" : "수집",
		"N" : "미수집"
	};
	
	$scope.fnGetList = function(){
		
		$http({
			method:'POST',
			url : '/action/action.php?ctrl=Keyword&action=getKeywordList',
			data : $.param($scope.searchForm),
			headers : {'Content-Type': 'application/x-www-form-urlencoded'}
		})
		.success(function(data){
			if(data.result=="OK"){
				$scope.keywordList = data.data;
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
	
	$scope.fnGetList();
		
	/**
	 *	연관검색어 수집 
	 */
	$scope.fnGetKeyword = function(r){

		var askYn = confirm(r.searchName+" 연관검색어 수집을 하시겠습니까?");
		
		if (askYn) {
			$http({
				method:'POST',
				url : '/action/action.php?ctrl=Keyword&action=getKeywordProc',
				data : $.param({keyword:r.searchName, mSeq:r.mSeq}),
				headers : {'Content-Type': 'application/x-www-form-urlencoded'}
			})
			.success(function(data){
				if(data.result=="OK"){
					alert("수집이 완료되었습니다.");
					$scope.fnGetList();
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
		
	}
	
	/**
	 * 키워드 불러오기
	 */
	$scope.fnKeywordCopy = function() {
		
		var askYn = confirm("키워드를 불러오시겠습니까?");
		
		if (askYn) {
			$http({
				method:'POST',
				url : '/action/action.php?ctrl=Keyword&action=keywordCopyProc',
				data : $.param($scope.searchForm),
				headers : {'Content-Type': 'application/x-www-form-urlencoded'}
			})
			.success(function(data){
				if(data.result=="OK"){
					alert("키워드불러오기가 완료되었습니다.");
					$scope.fnGetList();
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
	}
	
});