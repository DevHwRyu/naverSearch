<!DOCTYPE html>
<html lang="ko">
<head>
	<? include 'include/commonHtmlHead.html'; ?>
	<script type="text/javascript" src="/app/loginApp.js"></script>
</head>
<body ng-app="searchLogin" ng-controller="loginCtrl">
	<div class="container">

      <form class="form-signin">
        <h2>검색어 분석 시스템 Login</h2>
        <input type="text" id="id" class="form-control" placeholder="아이디 입력" ng-model="id">
        <input type="password" id="password" class="form-control" placeholder="패스워드" ng-model="pass">
        <button class="btn btn-lg btn-primary btn-block" ng-click="fnLogin()">로그인</button>
      </form>

    </div> <!-- /container -->

</html>