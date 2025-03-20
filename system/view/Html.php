<?php
namespace system\view;
/**
 It's just a wrapper for HTML tags
 */
class Html
{
    public static function stag(string $tag,?string $id,mixed $attribs=null)
    {
        $attrs = '';
        if(is_array($attribs))
        {
            if(!array_is_list($attribs))
            {
                foreach($attribs as $k => $v)
                {
                    $attrs .= ' '.$k.'='.(is_string($v)?('"'.$v.'"'):$v);
                }
            }
            else $attrs = implode($attribs);
        }
        else if(is_string($attribs))
        {
            $attrs=$attribs;
        }
        return '<'.$tag.($id?' id="'.$id.'"':'').$attrs.'/>'; 
    }
    public static function tag($tag,?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        if($escapeXSS)$text = htmlspecialchars($text,ENT_QUOTES);
        $attrs = '';
        if(is_array($attribs))
        {
            if(!array_is_list($attribs))
            {
                foreach($attribs as $k => $v)
                {
                    $attrs .= ' '.$k.'='.(is_string($v)?('"'.$v.'"'):$v);
                }
            }
            else $attrs = implode($attribs);
        }
        else if(is_string($attribs))
        {
            $attrs=$attribs;
        }
        return '<'.$tag.($id?' id="'.$id.'"':'').$attrs.'>'.$text.'</'.$tag.'>'; 
    }
    public static function h5(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("h5",$id,$text,$escapeXSS,$attribs);
    }
    public static function h4(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("h4",$id,$text,$escapeXSS,$attribs);
    }
    public static function h3(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("h3",$id,$text,$escapeXSS,$attribs);
    }
    public static function h2(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("h2",$id,$text,$escapeXSS,$attribs);
    }
    public static function h1(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("h1",$id,$text,$escapeXSS,$attribs);
    }
    public static function strong(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("strong",$id,$text,$escapeXSS,$attribs);
    }
    public static function p(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("p",$id,$text,$escapeXSS,$attribs);
    }
    public static function div(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("div",$id,$text,$escapeXSS,$attribs);
    }
    public static function address(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("address",$id,$text,$escapeXSS,$attribs);
    }
    public static function article(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("article",$id,$text,$escapeXSS,$attribs);
    }
    public static function aside(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("aside",$id,$text,$escapeXSS,$attribs);
    }
    public static function footer(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("footer",$id,$text,$escapeXSS,$attribs);
    }
    public static function header(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("header",$id,$text,$escapeXSS,$attribs);
    }
    public static function hgroup(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("hgroup",$id,$text,$escapeXSS,$attribs);
    }
    public static function main(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("main",$id,$text,$escapeXSS,$attribs);
    }
    public static function nav(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("nav",$id,$text,$escapeXSS,$attribs);
    }
    public static function section(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("section",$id,$text,$escapeXSS,$attribs);
    }
    public static function search(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("search",$id,$text,$escapeXSS,$attribs);
    }
    public static function blockquote(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("blockquote",$id,$text,$escapeXSS,$attribs);
    }
    public static function dd(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("dd",$id,$text,$escapeXSS,$attribs);
    }
    public static function dl(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("dl",$id,$text,$escapeXSS,$attribs);
    }
    public static function dt(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("dt",$id,$text,$escapeXSS,$attribs);
    }
    public static function figcaption(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("figcaption",$id,$text,$escapeXSS,$attribs);
    }
    public static function figure(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("figure",$id,$text,$escapeXSS,$attribs);
    }
    public static function hr(?string $id,mixed $attribs=null)
    {
        return self::stag("hr",$id,$attribs);
    }
    public static function li(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("li",$id,$text,$escapeXSS,$attribs);
    }
    public static function menu(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("menu",$id,$text,$escapeXSS,$attribs);
    }
    public static function ol(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("ol",$id,$text,$escapeXSS,$attribs);
    }
    public static function pre(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("pre",$id,$text,$escapeXSS,$attribs);
    }
    public static function ul(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("ul",$id,$text,$escapeXSS,$attribs);
    }
    public static function a(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("a",$id,$text,$escapeXSS,$attribs);
    }
    public static function abbr(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("abbr",$id,$text,$escapeXSS,$attribs);
    }
    public static function b(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("b",$id,$text,$escapeXSS,$attribs);
    }
    public static function bdi(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("bdi",$id,$text,$escapeXSS,$attribs);
    }
    public static function bdo(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("bdo",$id,$text,$escapeXSS,$attribs);
    }
    public static function br(?string $id,mixed $attribs=null)
    {
        return self::stag("br",$id,$attribs);
    }
    public static function cite(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("cite",$id,$text,$escapeXSS,$attribs);
    }
    public static function code(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("code",$id,$text,$escapeXSS,$attribs);
    }
    public static function data(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("data",$id,$text,$escapeXSS,$attribs);
    }
    public static function dfn(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("dfn",$id,$text,$escapeXSS,$attribs);
    }
    public static function em(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("em",$id,$text,$escapeXSS,$attribs);
    }
    public static function i(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("i",$id,$text,$escapeXSS,$attribs);
    }
    public static function kbd(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("kbd",$id,$text,$escapeXSS,$attribs);
    }
    public static function mark(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("mark",$id,$text,$escapeXSS,$attribs);
    }
    public static function q(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("q",$id,$text,$escapeXSS,$attribs);
    }
    public static function rp(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("rp",$id,$text,$escapeXSS,$attribs);
    }
    public static function rt(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("rt",$id,$text,$escapeXSS,$attribs);
    }
    public static function ruby(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("ruby",$id,$text,$escapeXSS,$attribs);
    }
    public static function s(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("s",$id,$text,$escapeXSS,$attribs);
    }
    public static function samp(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("samp",$id,$text,$escapeXSS,$attribs);
    }
    public static function small(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("small",$id,$text,$escapeXSS,$attribs);
    }
    public static function span(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("span",$id,$text,$escapeXSS,$attribs);
    }
    public static function sub(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("sub",$id,$text,$escapeXSS,$attribs);
    }
    public static function sup(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("sup",$id,$text,$escapeXSS,$attribs);
    }
    public static function time(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("time",$id,$text,$escapeXSS,$attribs);
    }
    public static function u(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("u",$id,$text,$escapeXSS,$attribs);
    }
    public static function _var(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("var",$id,$text,$escapeXSS,$attribs);
    }
    public static function wbr(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::stag("wbr",$id,$attribs);
    }
    public static function area(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("area",$id,$text,$escapeXSS,$attribs);
    }
    public static function audio(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("audio",$id,$text,$escapeXSS,$attribs);
    }
    public static function img(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("img",$id,$attribs);
    }
    public static function map(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("map",$id,$text,$escapeXSS,$attribs);
    }
    public static function track(?string $id,mixed $attribs=null)
    {
        return self::stag("track",$id,$text,$escapeXSS,$attribs);
    }
    public static function video(?string $id,mixed $attribs=null)
    {
        return self::stag("video",$id,$attribs);
    }
    public static function embed(?string $id,mixed $attribs=null)
    {
        return self::stag("embed",$id,$attribs);
    }
    public static function iframe(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("iframe",$id,$text,$escapeXSS,$attribs);
    }
    public static function _object(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("_object",$id,$text,$escapeXSS,$attribs);
    }
    public static function picture(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("picture",$id,$text,$escapeXSS,$attribs);
    }
    public static function portal(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("portal",$id,$text,$escapeXSS,$attribs);
    }
    public static function source(?string $id,mixed $attribs=null)
    {
        return self::stag("source",$id,$attribs);
    }
    public static function svg(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("svg",$id,$text,$escapeXSS,$attribs);
    }
    public static function math(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("math",$id,$text,$escapeXSS,$attribs);
    }
    public static function noscript(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("noscript",$id,$text,$escapeXSS,$attribs);
    }
    public static function script(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("script",$id,$text,$escapeXSS,$attribs);
    }
    public static function del(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("del",$id,$text,$escapeXSS,$attribs);
    }
    public static function ins(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("ins",$id,$text,$escapeXSS,$attribs);
    }
    public static function caption(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("caption",$id,$text,$escapeXSS,$attribs);
    }
    public static function col(?string $id,mixed $attribs=null)
    {
        return self::stag("col",$id,$attribs);
    }
    public static function colgroup(?string $id,?string $text,bool $escapeXSS = false,mixed $attribs=null)
    {
        return self::tag("colgroup",$id,$text,$escapeXSS,$attribs);
    }
    public static function table(?string $id,?string $text,bool $escapeXSS = false,mixed $attribs=null)
    {
        return self::tag("table",$id,$text,$escapeXSS,$attribs);
    }
    public static function tbody(?string $id,?string $text,bool $escapeXSS = false,mixed $attribs=null)
    {
        return self::tag("tbody",$id,$text,$escapeXSS,$attribs);
    }
    public static function td(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("td",$id,$text,$escapeXSS,$attribs);
    }
    public static function tfoot(?string $id,?string $text,bool $escapeXSS = false,mixed $attribs=null)
    {
        return self::tag("tfoot",$id,$text,$escapeXSS,$attribs);
    }
    public static function th(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("map",$id,$text,$escapeXSS,$attribs);
    }
    public static function thead(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("thead",$id,$text,$escapeXSS,$attribs);
    }
    public static function tr(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("tr",$id,$text,$escapeXSS,$attribs);
    }
    public static function button(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("button",$id,$text,$escapeXSS,$attribs);
    }
    public static function datalist(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("datalist",$id,$text,$escapeXSS,$attribs);
    }
    public static function fieldset(?string $id,?string $text,bool $escapeXSS = false,mixed $attribs=null)
    {
        return self::tag("fieldset",$id,$text,$escapeXSS,$attribs);
    }
    public static function form(?string $id,?string $text,bool $escapeXSS = false,mixed $attribs=null)
    {
        return self::tag("form",$id,$text,$escapeXSS,$attribs);
    }
    public static function input(?string $id,mixed $attribs=null)
    {
        return self::stag("input",$id,$attribs);
    }
    public static function label(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("label",$id,$text,$escapeXSS,$attribs);
    }
    public static function legend(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("legend",$id,$text,$escapeXSS,$attribs);
    }
    public static function meter(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("meter",$id,$text,$escapeXSS,$attribs);
    }
    public static function optgroup(?string $id,?string $text,bool $escapeXSS = false,mixed $attribs=null)
    {
        return self::tag("optgroup",$id,$text,$escapeXSS,$attribs);
    }
    public static function option(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("option",$id,$text,$escapeXSS,$attribs);
    }
    public static function output(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("output",$id,$text,$escapeXSS,$attribs);
    }
    public static function progress(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("progress",$id,$text,$escapeXSS,$attribs);
    }
    public static function select(?string $id,?string $text,bool $escapeXSS = false,mixed $attribs=null)
    {
        return self::tag("select",$id,$text,$escapeXSS,$attribs);
    }
    public static function textarea(?string $id,?string $text,bool $escapeXSS = true,mixed $attribs=null)
    {
        return self::tag("map",$id,$text,$escapeXSS,$attribs);
    }
}
