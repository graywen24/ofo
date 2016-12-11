<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="zh">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>ofo小黄车</title>
	<!-- jquery -->
<script src="/Public/js/jquery.min.js"></script>
<!-- vue -->
<script src="/Public/js/vue.js"></script>
<!-- <script src="//cdn.bootcss.com/vue/2.0.3/vue.min.js"></script> -->
<script src="/Public/js/vue-resource.min.js"></script>
<!-- css -->
<link rel="stylesheet" href="/Public/css/main.css">
</head>
<body>
	<div class="container" id="ofobike">
		<div class="front">
			<div class="bike">
				<h1>ofo</h1>
				<input type="text" pattern="[0-9]*" placeholder="请输入车牌号" v-model="bike">
				<div class="ret">
					<div class="btn btn-primary" v-on:click="ofo()">
						马上用车
					</div>
				</div>
			</div>
		</div>
		<div class="back">
			<div class="pass">
				<h3>解锁码</h3>
				<div class="bike_pass" >{{ pass }}</div>
				<div class="ret">
					<div class="forget" v-on:click="forget()">密码不对?</div>
					<div class="btn btn-normal" v-on:click="ofo()">
						返回
					</div>
				</div>
			</div>
		</div>
		<div class="add">
			<div class="close" v-on:click="close()">X</div>
			<div class="add-pass">
				<h1>{{edit}}车辆密码</h1>
				<input type="text" pattern="[0-9]*" placeholder="请输入密码" v-model="pass">
				<div class="ret">
					<div class="btn btn-primary" v-on:click="ofo()">
						{{edit}}
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
	var ofo = new Vue({
		el: '#ofobike',
		data: {
			state: false,
			bike: null,
			pass: null,
			isFront: true,
			isAdd: false,
			edit: null
		},
		methods: {
			ofo: function(){
				if (this.state) {
					if (this.isAdd) {
						if (this.pass) {
							var that = this;
							$.ajax({
								url: "<?php echo U('/Home/index/addpass');?>",
								type: 'post',
								dataType: 'json',
								data: {bike: that.bike,pass: that.pass},
								success: function(data){
									if (data.status) {
										alert(data.result);
										$(".front").css('transform','rotateY(0deg)');
										$(".add").css('transform','rotateY(-180deg)');
										that.state = !that.state;
										that.isAdd = false;
									}else{
										alert(data.result);
									}
								}
							});
						}else{
							alert('请填写密码');
						}
					}else{
						this.bike = null;
						$(".front").css('transform','rotateY(0deg)');
						$(".back").css('transform','rotateY(-180deg)');
						this.state = !this.state;
					}
				}else{
					var that  = this;
					that.pass = null;
					$.ajax({
						url: '<?php echo U("/Home/index/getpass");?>',
						type: 'post',
						dataType: 'json',
						data: {bike:that.bike},
						success: function(data){
							if (data.status) {
								if (data.pass) {
									$(".front").css('transform','rotateY(180deg)');
									$(".back").css('transform','rotateY(0deg)');
									that.pass = data.pass;
									that.state = !that.state;
								}else{
									if (confirm(that.bike+'的密码还未添加，是否需要添加?')) {
										that.edit = '添加';
										that.isAdd = true;
										that.state = !that.state;
										$(".add").css('display','block');
										$(".front").css('transform','rotateY(180deg)');
										$(".add").css('transform','rotateY(0deg)');
									}
								}
							}else{
								alert(data.result);
							}
						}
					});
				}
				
			},
			close:function(){
				this.pass = null;
				$(".front").css('transform','rotateY(0deg)');
				$(".add").css('transform','rotateY(-180deg)');
				this.state = !this.state;
				this.isAdd = false;
			},
			forget:function(){
				this.edit  = '修改';
				this.isAdd = true;
				this.pass  = null;
				$(".back").css('transform','rotateY(-180deg)');
				$(".add").css('transform','rotateY(0deg)');
			}
		}
	});
</script>
</html>