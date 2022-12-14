<div><br>
	<p>haiki_shareにて、お客様のご注文を以下の内容で承りました。<br>
		ご確認いただき、内容に誤りなどございましたらお知らせください。</p><br>
	<span>---------------------------</span><br>
	<p>・商品名<br>
		{{ $productName }}</p>
	<p>・代金<br>
		{{ $price }}円(税込)</p>
	<p>・賞味期限<br>
		{{ $bestBefore . "まで"  }}</p><br>
	<span>---------------------------</span><br><br>
	<p>＜出品者情報＞</p>
	<p>・コンビニ名<br>
		{{ $shopName}}</p>
	<p>・住所<br>
		{{ $address }}</p><br>
	<p>購入商品の確認は<a href="{{ $url }}">マイページ</a>よりご確認下さい。</p>
	<p>引き続き、どうぞよろしくお願い申し上げます。</p>
</div>

