'use strict';

searchApp.controller('mainCtrl',function($scope,$http){
	
	$scope.$emit("selectFlag", "1");
	
	$scope.keywordList = [];
	$scope.txt = {};
	$scope.stateType = {
		"Y" : "수집",
		"N" : "미수집"
	};
	
	$scope.fnGetList = function(){
		
		$http({
			method:'GET',
			url : '/action/action.php?ctrl=Keyword&action=getKeywordList',
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
	 * 키워드추가 모달폼
	 */
	$scope.showAddKeywordModal = false;
	$scope.fnShowAddKeyword = function(){
		$scope.txt.addKeyword = "";
		$scope.showAddKeywordModal = true;
	};
	
	/**
	 * 키워드추가 처리
	 */
	$scope.fnAddKeyword = function(){

		if ($scope.txt.addKeyword == "") {
			alert("추가하실 키워드를 입력해 주세요.");
			return;
		}
		
		$http({
			method:'POST',
			url : '/action/action.php?ctrl=Keyword&action=addKeywordProc',
			data : $.param({addKeyword:$scope.txt.addKeyword}),
			headers : {'Content-Type': 'application/x-www-form-urlencoded'}
		})
		.success(function(data){
			if(data.result=="OK"){
				alert("연관키워드가 등록되었습니다.");
				$scope.showAddKeywordModal = false;
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
	
});