var count_range = [5, 10];

function wspc_count_up() {
	if (  parseInt(wspc.post_id) === 0) { // single以外
		return;
	}
	// カウントアップ数の決定
	// e.g. count = 800
	var sub = count_range[1] - count_range[0];
	var count = Math.floor(Math.random() * sub); // 最大と最小の差の中での乱数を取得する
	count += count_range[0]; // 最小側を足して範囲に収める
	console.log(count);
	// カウントアップ数が有効か無効かの判定
	// 1/800 で抽選する
	if (Math.floor(Math.random() * count) === 0) {
		console.log('atari');
		// 抽選に当たった場合

		jQuery.ajax({
			url: wspc.ajax_url,
			type: 'POST',
			data: {
				'action': 'wspc_count_up',
				'post_id': wspc.post_id,
				'count': count
			}
		})
			.done(function (data) {
				console.log(data);
			})
			.fail(function () {
				console.log(data);
			});
	} else {
		console.log('hazure');
	}
	return;
}

jQuery(document).ready(function ($) {
	wspc_count_up();
});
