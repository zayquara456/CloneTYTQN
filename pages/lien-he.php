<div class="main">
		<div class="main-content container">
		<h1 class="the-title">
			Liên hệ		</h1>

		<div class="the-content">
			<h3><?php echo $company; ?></h3>
<p><?php echo $address?></p>
<p><b>Điện thoại: </b>0569.323.456
<p><b>Hotline: </b><?php echo $hotline?></p>
<p><strong>Email:</strong> <?php echo $email?></p>
<p><strong>Website:</strong> http://www.toyota.quangninh.vn</p>
<hr />
<p>Nếu có thắc mắc, quý khách vui lòng gửi lại theo mẫu bên dưới. Chúng tôi sẽ phản hồi lại trong thời gian sớm nhất.</p>
		</div>

		<form id="contact_form" class="page-form contact clearfix">
			<div class="form-group clearfix">
				
				<h2>Thông tin của bạn</h2>

				<p>
					<label for="contact_name">Tên (*)</label>
					<input id="contact_name" class="field" name="name" required />
				</p>
				<p>
					<label for="contact_email">E-mail (*)</label>
					<input id="contact_email" class="field" name="email" required />
				</p>
				<p>
					<label for="contact_phone">Điện thoại</label>
					<input id="contact_phone" class="field" name="phone" />
				</p>				
			</div>

			<div class="form-group clearfix">
				<h2>Nội dung liên hệ</h2>

				<p>
					<label for="contact_message">Lời nhắn (*)</label>
					<textarea id="contact_message" class="field" type="hidden" name="message" rows="6" required></textarea>
				</p>
				<p class="clear-both">
					<label></label>
					<input type="hidden" name="action" value="contact_submit" />
					<input type="hidden" name="nonce" value="591d744b59" />
					<button class="button">Gửi</button>
				</p>
			</div>

			<div class="form-group alert hidden clearfix">
				<h2>Có lỗi xảy ra</h2>
				<label></label>
				<ul class="arlet-list"></ul>
			</div>
		</form>

		<div class="the-content hidden">
			<p>Xin cảm ơn quý khách đã quan tâm đến Toyota Quảng Ninh.</p>
			<p>Chúng tôi sẽ liên hệ với quý khách trong thời gian sớm nhất. Trân trọng cảm ơn!</p>
			<p class="margin-top-xx"><a class="button" href="http://www.toyota.quangninh.vn">Trở về trang chủ</a></p>
		</div>
	</div>
	</div>