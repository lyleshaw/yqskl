<?php 
# 备忘：上课啦appkey：92d95b14c05d7eff0a67e0505722acb97b6d9e7b6033fab8e25fc0d8914f60ce
  header("content-type:text/json; charset=utf-8");
  function mb_substr_middle($text, $str_start, $str_end) {
		$t1 = mb_strpos($text, $str_start);
		$t2 = mb_strpos($text, $str_end, $t1);
		if ($t1 === false || $t2 === false || $t2 <= $t1) {
			return '';
		}
		return $s = mb_substr($text, $t1 + mb_strlen($str_start),$t2 - $t1 - mb_strlen($str_start));
  }

  function getUserInfo($token) {
    $url = "http://skl.hdu.edu.cn/api/userinfo?type=";
    $headers = array(
      "X-Auth-Token: " . $token,
      "Referer: http://skl.hdu.edu.cn/",
      "User-Agent: Mozilla/5.0 (Linux; Android) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/66.0.3359.126 Mobile Safari/537.36 yiban_android"
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $data = curl_exec($curl);
    return array(
      "code" => curl_getinfo($curl, CURLINFO_HTTP_CODE),
      "body" => json_decode($data, true),
      "msg" => curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200? "": "无法取得用户信息，可能是token过期，需要重新登录！"
    );
  }

  function login($user, $pass) {
    $url = "https://mobile.yiban.cn/api/v2/passport/login?account=" . $user . "&passwd=" . $pass . "&ct=2&app=1&v=4.6.2&apn=wifi&identify=0&sig=&token=&device=Huawei666&sversion=26";
    $curl = curl_init();
    $headers = array(
      "User-Agent: Mozilla/5.0 (Linux; Android) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/66.0.3359.126 Mobile Safari/537.36 yiban_android"
    );
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    $data = curl_exec($curl);
    $data = json_decode($data, true);
    if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
      return array(
        "code" => curl_getinfo($curl, CURLINFO_HTTP_CODE),
        "body" => $data,
        "msg" => "请求登录失败，请重新登录！"
      );
    }

    if ($data['response'] != 100) {
      return array(
        "code" => curl_getinfo($curl, CURLINFO_HTTP_CODE),
        "body" => $data,
        "msg" => $data['message']
      );
    }

    $access_token = $data['data']['access_token'];
    
    $url = "https://f.yiban.cn/iapp/index?act=iapp319528&v=" . $access_token;
    $headers = array(
      "User-Agent: Mozilla/5.0 (Linux; Android) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/66.0.3359.126 Mobile Safari/537.36 yiban_android"
    );
    $cookie = "yibanM_user_token=" . $access_token;
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 302跟随
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    $data = curl_exec($curl);

    preg_match("/token=(.*)/", curl_getinfo($curl, CURLINFO_EFFECTIVE_URL), $token);

    $data = json_decode($data, true);
    if ($token == null) {
      return array(
        "code" => curl_getinfo($curl, CURLINFO_HTTP_CODE),
        "body" => $data,
        "msg" => "易班接口返回：" . $data['result']
      );
    } else {
      $token = $token[1];
      return array(
        "code" => 666,
        "token" => $token
      );
    }
    curl_close($curl);
  }

  function signIn($token, $code) {
    $url = "http://skl.hdu.edu.cn/api/checkIn/code-check-in?code=" . $code . "&w=100&h=30";
    $headers = array(
      "X-Auth-Token: " . $token,
      "Origin: http://skl.hdu.edu.cn",
      "Referer: http://skl.hdu.edu.cn/",
      "User-Agent: Mozilla/5.0 (Linux; Android) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/66.0.3359.126 Mobile Safari/537.36 yiban_android"
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $data = curl_exec($curl);
    return array(
      "code" => curl_getinfo($curl, CURLINFO_HTTP_CODE),
      // "body" => $data, // $data是一张图片
      "msg" => "返回码是200，意味着可以开始请求验证码了。"
    );
  }

  function getCodeImg($token) {
    $url = "http://skl.hdu.edu.cn/api/checkIn/create-code-img";
    $headers = array(
      "X-Auth-Token: " . $token,
      "Origin: http://skl.hdu.edu.cn",
      "Referer: http://skl.hdu.edu.cn/",
      "User-Agent: Mozilla/5.0 (Linux; Android) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/66.0.3359.126 Mobile Safari/537.36 yiban_android"
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $data = curl_exec($curl);
    $data = 'data:image/jpeg;base64,' . chunk_split(base64_encode($data));
    $data = str_replace("\r\n", "", $data);
    return array(
      "code" => curl_getinfo($curl, CURLINFO_HTTP_CODE),
      "body" => $data, // base64格式图片
      "msg" => "body里的是base64格式图片"
    );
  }

  function validCode($token, $code) {
    $url = "http://skl.hdu.edu.cn/api/checkIn/valid-code?code=" . $code;
    $headers = array(
      "X-Auth-Token: " . $token,
      "Origin: http://skl.hdu.edu.cn",
      "Referer: http://skl.hdu.edu.cn/",
      "User-Agent: Mozilla/5.0 (Linux; Android) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/66.0.3359.126 Mobile Safari/537.36 yiban_android"
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $data = curl_exec($curl);
    return array(
      "code" => curl_getinfo($curl, CURLINFO_HTTP_CODE),
      "body" => $data,
      "msg" => "400.验证码错误；401.签到码错误"
    );
  }

  if (isset($_POST["action"])) {
    switch ($_POST["action"]) {
      // 登录
      // 传入参数用户名、密码
      // 返回token
      case "login": echo(
        json_encode(
          login($_POST["user"], $_POST["pass"])
        )
      ); break;
      // 取用户信息
      // 传入参数token
      // 返回个人信息
      case "getUserInfo": echo(
        json_encode(
          getUserInfo($_POST["token"])
        )
      ); break;
      // 签到
      // 传入参数token、签到码
      // 返回允许请求验证码图片
      case "signIn": echo(
        json_encode(
          signIn($_POST["token"], $_POST["code"])
        )
      ); break;
      // 验证验证码是否输入正确
      // 传入参数token、验证码
      // 如果签到码和验证码正确，返回签到成功；否则返回验证码错误或签到码错误
      case "validCode": echo(
        json_encode(
          validCode($_POST["token"], $_POST["code"]) // 这里的code是验证码
        )
      ); break;
      // 取验证码图片
      // 传入参数token
      // 返回base64格式图片
      case "getCodeImg": echo(
        json_encode(
          getCodeImg($_POST["token"])
        )
      ); break;
    }
  }
 ?>
