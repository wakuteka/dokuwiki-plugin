<?php
/**
 * Plugin Mozshot: Inserts a website screenshot.
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Takao Yokoyama <wakataka423@gmail.com>
 */

// must be run within DokuWiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once DOKU_PLUGIN.'syntax.php';

class syntax_plugin_mozshot extends DokuWiki_Syntax_Plugin {
	function getInfo() {
		return array(
			'author' => 'Takao Yokoyama',
			'email'  => 'cb.yokoyama@gmail.com',
			'date'   => '2010-01-01',
			'name'   => 'Mozshot Plugin',
			'desc'   => 'Inserts a website screenshot',
			'url'    => 'http://wakuteka.info/main/memo:informatics:dokuwiki:mozshot');
 	}
	var $html;
	var $pattern;
	function syntax_plugin_mozshot(){
		$this->html    = @file_get_contents(DOKU_PLUGIN.'mozshot/object.htm');
		$this->pattern = '/\{\{(\s?)mozshot>(small|large|link):([^} |]+)\|?(.*?)(\s?)\}\}/';
	}

  function getType() { return 'substition'; }
		function getSort() { return 32; } 
		function connectTo($mode) {
      $this->Lexer->addSpecialPattern('\{\{\s?mozshot>[^}]*\s?\}\}',$mode,'plugin_mozshot');
  }

	function handle($match, $state, $pos, &$handler) {
		$pm = preg_match_all($this->pattern,$match,$result);
		$left  = ($result[1][0] == " ");
		$right = ($result[5][0] == " ");
		$cmd   = $result[2][0];
		$id    = $result[3][0];
		$title = $result[4][0];
		if ($left == true && $right == true){
			$align = 'center';
		} else if($left == true){
			$align = 'right';
		} else if($right == true){
			$align = 'left';
		}
    return array($state, array($cmd,$id,$align,$title));
  }

	function render($mode, &$renderer, $data) {
		if($mode != 'xhtml'){ return false;}
	  list($state, $match) = $data;
		list($cmd,$id,$align,$title) = $match;
		$title = urlencode($title);
		$title = str_replace("+"," ", $title);
		switch($cmd) {
		case 'small':
			if($align == 'center'){$renderer->doc .= "<center>";}
			$renderer->doc.=sprintf("<a href='http://".$id."' target='_blank'><img src='http://mozshot.nemui.org/shot/80x60?http://"."$id' alt='".$id."'/></a>");
			if($align == 'center'){$renderer->doc .= "</center>";}
			$renderer->doc.=NL;
     return true;
		case 'large':
			if($align == 'center'){$renderer->doc .= "<center>";}
			$renderer->doc.=sprintf("<a href='http://".$id."' target='_blank'><img src='http://mozshot.nemui.org/shot/200x150?http://"."$id' alt='".$id."'/></a>");
			if($align == 'center'){$renderer->doc .= "</center>";}
			$renderer->doc.=NL;
			return true;
		default:
			if($align == 'center'){$renderer->doc .= "<center>";}
			$renderer->doc.=sprintf("<a href='http://".$id."' target='_blank'><img src='http://mozshot.nemui.org/shot/120x90?http://"."$id' alt='".$id."'/></a>");
			if($align == 'center'){$renderer->doc .= "</center>";}
			$renderer->doc.=NL;
			return true;
		}
		$renderer->doc.=NL;
	}
}
