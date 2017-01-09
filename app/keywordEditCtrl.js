'use strict';

searchApp.controller('keywordEditCtrl',function($scope,$http){
	
	$scope.$emit("selectFlag", "2");
	
	$scope.allCheckFlag = false;
	$scope.keywordEditList = [];
	$scope.txt = {
		"regKeyword" : ""	
	};
	$scope.stateType = {
		"Y" : "수집",
		"N" : "미수집"
	};
	
	$scope.fnGetKeywordEdit = function(){
		
		$http({
			method:'GET',
			url : '/action/action.php?ctrl=Keyword&action=getKeywordEdit',
			headers : {'Content-Type': 'application/x-www-form-urlencoded'}
		})
		.success(function(data){
			if(data.result=="OK"){
				$scope.keywordEditList = data.data;
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
	
	$scope.fnGetKeywordEdit();
	
	/**
	 * 키워드등록 처리
	 */
	$scope.fnRegKeyword = function(){
		
		if ($scope.txt.regKeyword == "") {
			alert("등록하실 키워드를 입력해 주세요.");
			return;
		}
		
		$http({
			method:'POST',
			url : '/action/action.php?ctrl=Keyword&action=regKeywordProc',
			data : $.param({regKeyword:$scope.txt.regKeyword}),
			headers : {'Content-Type': 'application/x-www-form-urlencoded'}
		})
		.success(function(data){
			if(data.result=="OK"){
				alert("키워드가 등록되었습니다.");
				$scope.txt.regKeyword = "";
				$scope.fnGetKeywordEdit();
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
	 * 키워드등록 엔터키 처리
	 */
	$scope.fnEnterKey = function(event) {
		if (event.keyCode == 13) {
			$scope.fnRegKeyword();
		}
	}
	
	/**
	 * 키워드 삭제처리
	 */
	$scope.fnDelKeyword = function(val){
		
		var askYn = confirm(val.kName+"를 삭제하시겠습니까?");
		
		if (askYn) {
			$http({
				method:'POST',
				url : '/action/action.php?ctrl=Keyword&action=delKeywordProc',
				data : $.param({delSeq:val.seq, ord:val.kOrd}),
				headers : {'Content-Type': 'application/x-www-form-urlencoded'}
			})
			.success(function(data){
				if(data.result=="OK"){
					alert("키워드가 삭제되었습니다.");
					$scope.fnGetKeywordEdit();
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
		}
	};
	
	/**
	 * 키워드 순서 변경(올림)
	 */
	$scope.fnMoveKeyWord = function(val, typ){
		
		$http({
			method:'POST',
			url : '/action/action.php?ctrl=Keyword&action=moveKeywordProc',
			data : $.param({seq:val.seq, ord:val.kOrd, typ:typ}),
			headers : {'Content-Type': 'application/x-www-form-urlencoded'}
		})
		.success(function(data){
			if(data.result=="OK"){
				$scope.fnGetKeywordEdit();
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
	}
		
});