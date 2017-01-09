'use strict';

searchApp.controller('keywordViewCtrl',function($scope,$http,$routeParams){
		
	$scope.$emit("selectFlag", "3");
	$scope.pageNo = $routeParams.pageNo;
		
	$scope.gridOptions = {
		enableColumnMenus: false,
		enableColumnResizing : true
	};
	
	$scope.gridOptions.columnDefs = [
	    { displayName : 'No', 					name : "no", width:50, type: 'number' },
		{ displayName : '연관키워드', 			name : "keyword", width:150},
		{ displayName : '월간검색수(PC)', 		name : "monthSearchPc", width:150, type: 'number'},
		{ displayName : '월간검색수(Mobile)', 	name : "monthSearchMobile", width:150, type: 'number'},
		{ displayName : '월평균클릭수(PC)', 	name : "monthClickPc", width:150, type: 'number'},
		{ displayName : '월평균클릭수(Mobile)', name : "monthClickMobile", width:150, type: 'number'},
		{ displayName : '월평균클릭률(PC)', 	name : "monthAvgClickPc", width:150, type: 'number'},
		{ displayName : '월평균클릭률(Mobile)', name : "monthAvgClickMobile", width:150, type: 'number'},
		{ displayName : '경쟁정도', 			name : "compitition", width:150},
		{ displayName : '월평균노출광고수', 	name : "monthAdCnt", width:150, type: 'number'}
	];
	
	$scope.keywordList = [];
	$scope.txt = {};
	$scope.stateType = {
		"Y" : "수집",
		"N" : "미수집"
	};
	
	$scope.fnGetList = function(){
		
		$http({
			method:'POST',
			url : '/action/action.php?ctrl=Keyword&action=getKeywordViewData',
			data : $.param({seq:$scope.pageNo}),
			headers : {'Content-Type': 'application/x-www-form-urlencoded'}
		})
		.success(function(data){
			if(data.result=="OK"){
				$scope.gridOptions.data = data.data;
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