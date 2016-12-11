<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

	public function index(){
		$this->display();
	}

	public function getpass(){

		$bike = I('post.bike/s');
		$ip   = get_client_ip();

		if (!empty($bike)) {

			if (!is_numeric($bike)) {
				$this->ajaxReturn(array('status'=>false,'result'=>'车牌号不正确'));
			}
			if ( strlen($bike) < 5 || strlen($bike) > 6 ) {
				$this->ajaxReturn(array('status'=>false,'result'=>'车牌号不正确'));
			}
			if($pass = M('pass')->field(array('bike_pass'=>'pass'))->where(array('bike_id'=>$bike))->find()){
				M('log')->data(array('type'=>'query','bike_id'=>$bike,'bike_pass'=>$pass['pass'],'time'=>time(),'status'=>1,'ipaddr'=>$ip))->add();
				$this->ajaxReturn(array('status'=>true,'pass'=>$pass['pass']));
			}else{
				M('log')->data(array('type'=>'query','bike_id'=>$bike,'bike_pass'=>'','time'=>time(),'status'=>0,'ipaddr'=>$ip))->add();
				$this->ajaxReturn(array('status'=>true,'result'=>'密码不存在'));
			}

		}else{
			$this->ajaxReturn(array('status'=>false,'result'=>'车牌号不能为空'));
		}
	}

	public function addpass(){

		$bike = I('post.bike/s');
		$pass = I('post.pass/s');
		$ip   = get_client_ip();

		if (!empty($pass)) {

			if (strlen($pass) != 4) {
				M('log')->data(array('type'=>'update','bike_id'=>$bike,'bike_pass'=>$pass,'time'=>time(),'status'=>0,'ipaddr'=>$ip))->add();
				$this->ajaxReturn(array('status'=>false,'result'=>'密码有误'));
			}

			if ($result = M('pass')->where(array('bike_id'=>$bike))->find()) {
				if (M('pass')->where(array('id'=>$result['id']))->data(array('bike_pass'=>$pass))->save()) {
					M('log')->data(array('type'=>'update','bike_id'=>$bike,'bike_pass'=>$pass,'time'=>time(),'status'=>1,'ipaddr'=>$ip))->add();
					$this->ajaxReturn(array('status'=>true,'result'=>'修改成功'));
				}else{
					M('log')->data(array('type'=>'update','bike_id'=>$bike,'bike_pass'=>$pass,'time'=>time(),'status'=>0,'ipaddr'=>$ip))->add();
					$this->ajaxReturn(array('status'=>false,'result'=>'修改失败'));
				}
			}else{
				if (M('pass')->data(array('bike_id'=>$bike, 'bike_pass'=>$pass, 'add_time'=>time()))->add()) {
					M('log')->data(array('type'=>'add','bike_id'=>$bike,'bike_pass'=>$pass,'time'=>time(),'status'=>1,'ipaddr'=>$ip))->add();
					$this->ajaxReturn(array('status'=>true,'result'=>'添加成功'));
				}else{
					M('log')->data(array('type'=>'add','bike_id'=>$bike,'bike_pass'=>$pass,'time'=>time(),'status'=>0,'ipaddr'=>$ip))->add();
					$this->ajaxReturn(array('status'=>false,'result'=>'添加失败'));
				}
			}
		}else{
			$this->ajaxReturn(array('status'=>false,'result'=>'密码不能为空'));
		}
	}

	public function getOrderList(){
		$header = array(
			'Content-Type' => 'application/json; charset=utf-8'
			);
		$post = array(
			'token'    => '',
			'sourse'   => 0,
			'classify' => 0,
			'page'     => 1
			);
		echo $this->http('https://san.ofo.so/ofo/Api/v3/detail', $post, $header);
	}

	public function getBikePass(){
		$header = array(
			'Content-Type' => 'application/json; charset=utf-8',
			'Host'         => 'san.ofo.so',
			'User-Agent'   => 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1',
			'Origin' => 'https://common.ofo.so'
			);
		$post = array(
			'token'  => '',
			'sourse' => 0,
			'carno'  => 56578,
			'lat'    => 30.57252532998116,
			'lng'    => 114.3314644340214
			);
		echo $this->http('https://san.ofo.so/ofo/Api/v2/carno', $post, $header, null, 'https://common.ofo.so/newdist/?Journey&time='.time());
	}

	public function getNearBy(){
		$header = array(
			'Content-Type' => 'application/json; charset=utf-8'
			);
		$post = array(
			'token'  => '',
			'sourse' => 0,
			'lat'    => 30.57252532998116,
			'lng'    => 114.3314644340214
			);
		echo $this->http('https://san.ofo.so/ofo/Api/v2/nearby', $post, $header, null, 'https://common.ofo.so/newdist/?Journey&time='.time());
	}

	private function http($url, $post = null, $header = null, $cookie = null, $refer = null){
		$ch = curl_init();

		$options = array(
			CURLOPT_URL            => $url,
			CURLOPT_HEADER         => false,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_RETURNTRANSFER => true
			);

		curl_setopt_array($ch, $options);

		if ($refer) {
			curl_setopt($ch, CURLOPT_REFERER, $refer);
		}

		if ($header) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}

		if ($cookie) {
			curl_setopt($ch, CURLOPT_COOKIE, $cookie);
		}

		if ($post) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}