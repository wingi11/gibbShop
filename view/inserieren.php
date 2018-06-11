<div class="row">
	<div class="col s12 m4 l2"></div>
	<div class="col s12 m4 l8">
		<div class="row">
			<div class="card">
				<form action="inserieren/create" name="inserat" class="insertform" method="post"
					  onsubmit="return validateInserat()" enctype="multipart/form-data">
					<div class="card-content">
						<span class="card-title">Inserieren</span>
						<div class="row">
							<div class="col s8">
								<div class="input-field col s8">
									<input id="product_name" name="product_name" maxlength="20" data-length="20"
										   type="text" class="black-text">
									<label for="product_name">Produktname</label>
								</div>
								<div class="input-field col s8">
									<textarea id="product_description" maxlength="1000" data-length="1000"
											  name="product_description" class="materialize-textarea"></textarea>
									<label for="product_description">Beschreibung</label>
								</div>
								<div class="col s8">
									<p id="preis">Preis</p>
									<div class="price-selection">
										<input name="price_type" class="price_type" type="radio" value="1"
											   id="price_fix"/>
										<label for="price_fix">Fixer Preis</label>

										<input name="price_type" class="price_type" type="radio" value="0"
											   id="price_auction" checked/>
										<label for="price_auction">Auktion</label>
									</div>
									<div class="input-field inline price_franken">
										<input id="price_franken" maxlength="4" oninput="maxLengthCheck(this)"
											   placeholder="0000" type="number"
											   class="validate black-text price_franken">
									</div>
									.
									<div class="input-field inline price_rappen">
										<input id="price_rappen" maxlength="2" oninput="maxLengthCheck(this)"
											   placeholder="00" type="number" class="validate black-text price_rappen">
									</div>
									<span>CHF</span>
									<div class="input-field col s4" id="expiryDate-picker">
										<input type="text" placeholder="Ablaufdatum" name="expiryDate" id="expiryDate"
											   class="ExpiryDate">
									</div>
									<input type="hidden" name="product_price" id="product_price"/>
								</div>
								<div class="col s8">
									<label for="tags">Tags</label>
									<div id="tags" class="chips"></div>
									<input type="hidden" name="product_tags" id="product_tags"/>
								</div>
							</div>
							<div class="col s4">
								<div class="product-image file-field">
									<img id="preview-img" src="#" alt="Product Image">
									<input type="file" name="product_image" id="prodImg" onchange="readURL(this);">
									<p class="center white-text">Bild auswählen</p>
								</div>
							</div>
						</div>
						<div class="center-align">
							<input type="submit" value="Inserat ausschreiben" name="submit"
								   class="btn waves-effect waves-light normal-btn">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col s12 m4 l2"></div>
</div>