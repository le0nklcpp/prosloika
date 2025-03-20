<?php
namespace system\input;
class escapeInput
{
const ESCAPE_STRICT=0;
const ESCAPE_SQL=1;
const ESCAPE_PHP=2;
const ESCAPE_XSS=3;
/**
 * Escapes user input according to parameter $remove
 * Note that SQL query is escaped using addslashes aka "magic quotes"
 * This will affect the contents on reload
 * @param $input User input to sanitize
 * @param $remove Specify sanitization type: ESCAPE_XSS,or ESCAPE_SQL, 
 * or even ESCAPE_PHP
 * @return string Sanitized string
 **/
static function escape(string $input,$type=self::ESCAPE_XSS)
{
    return match($type){
        self::ESCAPE_STRICT =>str_replace(['\'','"','\\','%','--',';','{','}','$','<','>','=',':'],'',$input),
        self::ESCAPE_XSS =>htmlspecialchars($input),
        self::ESCAPE_SQL=>addcslashes($input,'\\\''),
        self::ESCAPE_PHP=>str_replace('}','}}',str_replace('{','{{',$input))
    };
}
}
