<script  type="text/javascript">
var inputnumber = 'Giá trị nhập vào không phải là số';
	function FormatNumber(str) {
		var strTemp = GetNumber(str);
		if (strTemp.length <= 3)
			return strTemp;
		strResult = "";
		for (var i = 0; i < strTemp.length; i++)
			strTemp = strTemp.replace(",", "");
		var m = strTemp.lastIndexOf(".");
		if (m == -1) {
			for (var i = strTemp.length; i >= 0; i--) {
				if (strResult.length > 0 && (strTemp.length - i - 1) % 3 == 0)
					strResult = "," + strResult;
				strResult = strTemp.substring(i, i + 1) + strResult;
			}
		} else {
			var strphannguyen = strTemp.substring(0, strTemp.lastIndexOf("."));
			var strphanthapphan = strTemp.substring(strTemp.lastIndexOf("."),
					strTemp.length);
			var tam = 0;
			for (var i = strphannguyen.length; i >= 0; i--) {

				if (strResult.length > 0 && tam == 4) {
					strResult = "," + strResult;
					tam = 1;
				}

				strResult = strphannguyen.substring(i, i + 1) + strResult;
				tam = tam + 1;
			}
			strResult = strResult + strphanthapphan;
		}
		return strResult;
	}

	function GetNumber(str) {
		var count = 0;
		for (var i = 0; i < str.length; i++) {
			var temp = str.substring(i, i + 1);
			if (!(temp == "," || temp == "." || (temp >= 0 && temp <= 9))) {
				alert(inputnumber);
				return str.substring(0, i);
			}
			if (temp == " ")
				return str.substring(0, i);
			if (temp == ".") {
				if (count > 0)
					return str.substring(0, ipubl_date);
				count++;
			}
		}
		return str;
	}

	function IsNumberInt(str) {
		for (var i = 0; i < str.length; i++) {
			var temp = str.substring(i, i + 1);
			if (!(temp == "." || (temp >= 0 && temp <= 9))) {
				alert(inputnumber);
				return str.substring(0, i);
			}
			if (temp == ",") {
				return str.substring(0, i);
			}
		}
		return str;
	}
</script>

<div class="main">
		<div class="main-content container">
		<h1 class="the-title">Cách tính mua xe trả góp</h1>
		<div class="the-content">
			<p>Mức biểu phí đươi đây là tạm tính và có thể thay đổi do sự thay đổi của thuế và các bên cung cấp dịch vụ khác.</p>
		</div>
		<form id="loan_calculator_form" class="page-form calculator clearfix">
			<div class="form-group clearfix">
				<h2>Thông tin xe</h2>
				<p>
					<label>Chọn xe (*)</label>
					<select id="price_calculating_vehicle_select" name="vehicle_id" required autocomplete="off">
                        <option value="">Chọn</option>
						<?php
						//$vehicle = $_GET['vehicle']? $_GET['vehicle'] : "";
						?>
						<option value="yaris">YARIS</option>
						<option value="vios">VIOS</option>
						<option value="corolla">COROLLA ALTIS</option>
						<option value="camry">CAMRY</option>
						<option value="innova">INNOVA</option>
						<option value="fortuner">FORTUNER</option>
						<option value="land">LAND CRUISER</option>
						<option value="prado">LAND CRUISER PRADO</option>
						<option value="hilux">HILUX</option>
						<option value="hiace">HIACE</option>
						<option value="alphard">ALPHARD</option>
					</select>
				</p>
				<p id="price_calculator_version" class="clear-both hidden"></p>
				<p class="clear-both">
					<label>Thời hạn vay vốn (*)</label>
					<select id="thoihanvay" name="thoihanvay" required autocomplete="off">
						<option value="1">1 năm</option>
						<option value="2">2 năm</option>
						<option value="3">3 năm</option>
						<option value="4">4 năm</option>
						<option value="5">5 năm</option>
						<option value="6">6 năm</option>
						<option value="7">7 năm</option>
						<option value="8">8 năm</option>
					</select>
				</p>
				<p class="clear-both">
					<label>Số tiền trả trước (*)</label>
					<input id="tientratruoc" class="field" name="tientratruoc" onkeyup="this.value=FormatNumber(this.value);" required />
				</p>
				<p class="clear-both">
					<label>Lãi suất (VD: 0.67) (*)</label>
					<input id="laisuat" class="field" name="laisuat" required />
				</p>
				
				<p class="clear-both">
					<label></label>
					<input type="hidden" name="action" value="loan_calculator_action" />
					<input type="hidden" name="nonce" value="1ef6ed5d99" />
					<button class="button">Tính</button>
				</p>
			</div>
			<div id="loan_calculator_result" class="calculator-result hidden"></div>
		</form>
		<div><p>*Giá trị được hiển thị trên đây được ước tính cho khách hàng cá nhân và chỉ mang tính chất tham khảo. Giá của xe có thể thay đổi. Để có được thông tin chi tiết và chính xác, <b>vui lòng liên hệ:&nbsp;&nbsp;<?php echo $hotline;?></b>.</p></div>
	</div>
	</div>