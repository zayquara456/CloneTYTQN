<div class="main">
		<div class="main-content container">
		<h1 class="the-title">Dự toán chi phí</h1>
		<div class="the-content">
			<p>Mức biểu phí đươi đây là tạm tính và có thể thay đổi do sự thay đổi của thuế và các bên cung cấp dịch vụ khác. Mức bảo hiểm chưa bao gồm 10% VAT.</p>
		</div>
		<form id="price_calculator_form" class="page-form calculator clearfix">
			<div class="form-group clearfix">
				<h2>Thông tin xe</h2>
				<p>
					<label>Chọn xe</label>
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
					<label>Nơi đăng ký trước bạ</label>
					<select class="select" name="vehicle_location" required autocomplete="off">
                        <option value="">Chọn</option>
						<option value="quangninh">Quảng Ninh</option>
						<option value="hanoi">Hà Nội</option>
						<option value="kvii">Khu vực II</option>
						<option value="kviii">Khu vực III</option>
					</select>
				</p>
				<p class="clear-both">
					<label></label>
					<input type="hidden" name="action" value="price_calculator_action" />
					<input type="hidden" name="nonce" value="1ef6ed5d99" />
					<button class="button">Dự toán</button>
				</p>
			</div>
			<div id="price_calculator_result" class="calculator-result hidden"></div>
		</form>
		<div><p>(*) Bảo hiểm trách nhiệm dân sự 480.700VNĐ/xe dành cho xe 05 chỗ, 873.400VNĐ/xe dành cho xe 07 chỗ. Phí bảo hiểm toàn bộ 1,5%x giá trị hoá đơn. <br>(*) Khu vực II: Gồm các thành phố trực thuộc trung ương (trừ thành phố Hà Nội và thành phố Hồ Chí Minh), các thành phố trực thuộc tỉnh và các thị xã.<br> (*) Khu vực III: Gồm các khu vực khác ngoài Hà Nội, thành phố Hồ Chí Minh và khu vực II nêu trên.<br> (*) Mức biểu phí trên đây là tạm tính, không phải báo giá chính thức và có thể thay đổi do sự thay đổi của thuế, Công ty ô tô Toyota Việt Nam và các bên liên quan. Để có thông tin và bảng tính chi phí chính xác, <b>vui lòng liên hệ:&nbsp;&nbsp;<?php echo $hotline;?></b>.</p></div>
	</div>
	</div>