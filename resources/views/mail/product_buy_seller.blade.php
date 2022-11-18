<div><br>
	<p><a href="https://haiki-share.net">haiki_share</a>にて、貴店の出品した商品が購入されました。<br>
		以下の注文内容をご確認下さい。</p><br>
	<span>---------------------------</span><br>
    <p>＜購入者情報＞</p>
	<p>・購入者<br>
		{{ $userName }}</p>
	<p>・購入者のメールアドレス<br>
		{{ $email }}</p>
	<p>・購入日時<br>
        {{ $buyDate }}</p><br>
    <p>＜購入された商品情報＞</p>
    <p>・商品名<br>
		{{ $productName }}</p>
	<p>・金額<br>
		{{ $price }}円(税込)</p>
    <p>・賞味期限<br>
		{{ $bestBefore }}</p><br>
	<span>---------------------------</span><br><br>
	<p>購入された商品は、<a href="{{ $url }}">購入された商品ページ</a>よりご確認ください。</p>
	<p>上記内容が確認でき次第、配達手続きの程宜しくお願いいたします。</p>
</div>



