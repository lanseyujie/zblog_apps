/**
 * ScrollBar Plugin For Z-Blog
 * 
 * @package     ScrollBar.zba
 * @version     1.0.1
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */
 
$(function() {
	var a = null;
	$(window).scroll(function() {
		var b = $(this).height(),
			d = b / $(document).height() * b,
			c = $(this).scrollTop() / ($(document).height() - b),
			e = c * (b - d) + d / 2 - $("#scroll").height() / 2;
		$("#scroll").css("top", e).text(" (" + Math.round(c * 100) + "%)").fadeIn(100);
		if (a !== null) {
			clearTimeout(a)
		}
		a = setTimeout(function() {
			$("#scroll").fadeOut()
		}, 1500)
	});
	$(window).scroll(function() {
		if ($(this).scrollTop() > 400) {
			$(".go-up").fadeIn()
		}
		else {
			$(".go-up").fadeOut()
		}
	});
	$(".go-up").on("click", function() {
		$("html, body").animate({
			scrollTop: 0
		}, 600);
		return false
	})
});