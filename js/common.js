/**
 * 출퇴근 코드
 */
var upDownCodes = {"U":"등교","D":"하교"};
var useYnCodes = {"Y":"사용","N":"미사용"};

//발행 기초 데이터
var defaultPubData = {
    "uid":"",
    "customerNm":"전북대",
    "perNm":"",
    "perNo":"",
    "pubDate":""
};

/**
 * format 
 */
String.prototype.format = function() {
	var args = arguments;
	return this.replace(/{(\d+)}/g, function(match, number) { 
		return typeof args[number] != 'undefined'? args[number]: match;
    });
};

/**
 * 시작일과 종료일 체크
 * @param startDate
 * @param endDate
 * @returns {Boolean}
 */
function checkDueDate(startDate, endDate){
	if(parseInt(startDate.replace(/-/gi,"")) > parseInt(endDate.replace(/-/gi,""))){
		return false;
	} else {
		return true;
	}
}

/**
 * 객체 복사
 * @param obj
 * @returns
 */
function cloneObject(obj){
	return JSON.parse(JSON.stringify(obj));
}

/**
 * 날짜를 포맷형시에 맞게 String출력
 * @param Date d //날짜
 * @param String f //포멧
 * @returns String
 */
function formatDate(d,f){
	if (!d.valueOf()) return " ";
    var weekName = ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"];
    var shortWeekName = ["일","월","화","수","목","금","토"];
    //var d = this;
     
    return f.replace(/(yyyy|yy|MM|dd|E|e|hh|mm|ss|a\/p)/gi, function($1) {
        switch ($1) {
            case "yyyy": return d.getFullYear();
            case "yy": return (d.getFullYear() % 1000).zf(2);
            case "MM": return (d.getMonth() + 1).zf(2);
            case "dd": return d.getDate().zf(2);
            case "E": return weekName[d.getDay()];
            case "e": return shortWeekName[d.getDay()];
            case "HH": return d.getHours().zf(2);
            case "hh": return ((h = d.getHours() % 12) ? h : 12).zf(2);
            case "mm": return d.getMinutes().zf(2);
            case "ss": return d.getSeconds().zf(2);
            case "a/p": return d.getHours() < 12 ? "오전" : "오후";
            default: return $1;
        }
    });
}
function comma(str) {
	str = String(str);
	return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}
function uncomma(str) {
	str = String(str);
	return str.replace(/[^\d]+/g, '');
}
String.prototype.string = function(len){var s = '', i = 0; while (i++ < len) { s += this; } return s;};
String.prototype.zf = function(len){return "0".string(len - this.length) + this;};
Number.prototype.zf = function(len){return this.toString().zf(len);};

/**
 * 브라우저 가지고 오기
 * 출처 : http://serpiko.tistory.com/370
 * @returns {*}
 */
function getBrowserType(){
    var _ua = navigator.userAgent;
    var rv = -1;

    //IE 11,10,9,8
    var trident = _ua.match(/Trident\/(\d.\d)/i);
    if( trident != null )
    {
        if( trident[1] == "7.0" ) return rv = "IE" + 11;
        if( trident[1] == "6.0" ) return rv = "IE" + 10;
        if( trident[1] == "5.0" ) return rv = "IE" + 9;
        if( trident[1] == "4.0" ) return rv = "IE" + 8;
    }

    //IE 7...
    if( navigator.appName == 'Microsoft Internet Explorer' ) return rv = "IE" + 7;

    //other
    var agt = _ua.toLowerCase();
    if (agt.indexOf("chrome") != -1) return 'Chrome';
    if (agt.indexOf("opera") != -1) return 'Opera';
    if (agt.indexOf("staroffice") != -1) return 'Star Office';
    if (agt.indexOf("webtv") != -1) return 'WebTV';
    if (agt.indexOf("beonex") != -1) return 'Beonex';
    if (agt.indexOf("chimera") != -1) return 'Chimera';
    if (agt.indexOf("netpositive") != -1) return 'NetPositive';
    if (agt.indexOf("phoenix") != -1) return 'Phoenix';
    if (agt.indexOf("firefox") != -1) return 'Firefox';
    if (agt.indexOf("safari") != -1) return 'Safari';
    if (agt.indexOf("skipstone") != -1) return 'SkipStone';
    if (agt.indexOf("netscape") != -1) return 'Netscape';
    if (agt.indexOf("mozilla/5.0") != -1) return 'Mozilla';
}