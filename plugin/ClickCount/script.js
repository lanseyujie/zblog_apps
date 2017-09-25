/**
 * ClickCount Plugin For Z-Blog
 * 
 * @package     ClickCount.zba
 * @version     1.5.1
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */
 
$(function() {
    function get_cookie(name) {
        var search = name + "=",
            returnvalue = "";
        if (document.cookie.length > 0) {
            offset = document.cookie.indexOf(search);
            if (offset != -1) {
                offset += search.length;
                end = document.cookie.indexOf(";", offset);
                if (end == -1) {
                    end = document.cookie.length;
                }
                returnvalue = unescape(document.cookie.substring(offset, end));
            }
        }
        return returnvalue;
    }
    function set_cookie(name, value) {
        var Days = 1;
        var exp = new Date();
        exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
        document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
    }
    var click_count = 0;
    $(document).click(function(e) {
        if (get_cookie("clickcount") == null) {
            set_cookie("clickcount", 0);
        }
        click_count = get_cookie("clickcount");
        set_cookie("clickcount", ++click_count);
        var $i = $("<i>").text("+" + (click_count));
        var x = e.pageX,
            y = e.pageY;
        $i.css({
            "position": "absolute",
            "z-index": "10000",
            "top": y - 15,
            "left": x,
            "color": "red",
            "font-size": "14px"
        });
        $("body").append($i);
        $i.animate({
            "top": y - 180,
            "opacity": "0"
        }, 2000, function() {
            $i.remove();
        });
        e.stopPropagation();
    });
});