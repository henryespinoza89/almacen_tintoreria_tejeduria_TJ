<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter HTML Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/html_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Heading
 *
 * Generates an HTML heading tag.  First param is the data.
 * Second param is the size of the heading tag.
 *
 * @access	public
 * @param	string
 * @param	integer
 * @return	string
 */
if ( ! function_exists('heading'))
{
	function heading($data = '', $h = '1', $attributes = '')
	{
		$attributes = ($attributes != '') ? ' '.$attributes : $attributes;
		return "<h".$h.$attributes.">".$data."</h".$h.">";
	}
}

// ------------------------------------------------------------------------

/**
 * Unordered List
 *
 * Generates an HTML unordered list from an single or multi-dimensional array.
 *
 * @access	public
 * @param	array
 * @param	mixed
 * @return	string
 */
if ( ! function_exists('ul'))
{
	function ul($list, $attributes = '')
	{
		return _list('ul', $list, $attributes);
	}
}

// ------------------------------------------------------------------------

/**
 * Ordered List
 *
 * Generates an HTML ordered list from an single or multi-dimensional array.
 *
 * @access	public
 * @param	array
 * @param	mixed
 * @return	string
 */
if ( ! function_exists('ol'))
{
	function ol($list, $attributes = '')
	{
		return _list('ol', $list, $attributes);
	}
}

// ------------------------------------------------------------------------

/**
 * Generates the list
 *
 * Generates an HTML ordered list from an single or multi-dimensional array.
 *
 * @access	private
 * @param	string
 * @param	mixed
 * @param	mixed
 * @param	integer
 * @return	string
 */
if ( ! function_exists('_list'))
{
	function _list($type = 'ul', $list, $attributes = '', $depth = 0)
	{
		// If an array wasn't submitted there's nothing to do...
		if ( ! is_array($list))
		{
			return $list;
		}

		// Set the indentation based on the depth
		$out = str_repeat(" ", $depth);

		// Were any attributes submitted?  If so generate a string
		if (is_array($attributes))
		{
			$atts = '';
			foreach ($attributes as $key => $val)
			{
				$atts .= ' ' . $key . '="' . $val . '"';
			}
			$attributes = $atts;
		}
		elseif (is_string($attributes) AND strlen($attributes) > 0)
		{
			$attributes = ' '. $attributes;
		}

		// Write the opening list tag
		$out .= "<".$type.$attributes.">\n";

		// Cycle through the list elements.  If an array is
		// encountered we will recursively call _list()

		static $_last_list_item = '';
		foreach ($list as $key => $val)
		{
			$_last_list_item = $key;

			$out .= str_repeat(" ", $depth + 2);
			$out .= "<li>";

			if ( ! is_array($val))
			{
				$out .= $val;
			}
			else
			{
				$out .= $_last_list_item."\n";
				$out .= _list($type, $val, '', $depth + 4);
				$out .= str_repeat(" ", $depth + 2);
			}

			$out .= "</li>\n";
		}

		// Set the indentation for the closing tag
		$out .= str_repeat(" ", $depth);

		// Write the closing list tag
		$out .= "</".$type.">\n";

		return $out;
	}
}

// ------------------------------------------------------------------------

/**
 * Generates HTML BR tags based on number supplied
 *
 * @access	public
 * @param	integer
 * @return	string
 */
if ( ! function_exists('br'))
{
	function br($num = 1)
	{
		return str_repeat("<br />", $num);
	}
}

// ------------------------------------------------------------------------

/**
 * Image
 *
 * Generates an <img /> element
 *
 * @access	public
 * @param	mixed
 * @return	string
 */
if ( ! function_exists('img'))
{
	function img($src = '', $index_page = FALSE)
	{
		if ( ! is_array($src) )
		{
			$src = array('src' => $src);
		}

		// If there is no alt attribute defined, set it to an empty string
		if ( ! isset($src['alt']))
		{
			$src['alt'] = '';
		}

		$img = '<img';

		foreach ($src as $k=>$v)
		{

			if ($k == 'src' AND strpos($v, '://') === FALSE)
			{
				$CI =& get_instance();

				if ($index_page === TRUE)
				{
					$img .= ' src="'.$CI->config->site_url($v).'"';
				}
				else
				{
					$img .= ' src="'.$CI->config->slash_item('base_url').$v.'"';
				}
			}
			else
			{
				$img .= " $k=\"$v\"";
			}
		}

		$img .= '/>';

		return $img;
	}
}

// ------------------------------------------------------------------------

/**
 * Doctype
 *
 * Generates a page document type declaration
 *
 * Valid options are xhtml-11, xhtml-strict, xhtml-trans, xhtml-frame,
 * html4-strict, html4-trans, and html4-frame.  Values are saved in the
 * doctypes config file.
 *
 * @access	public
 * @param	string	type	The doctype to be generated
 * @return	string
 */
if ( ! function_exists('doctype'))
{
	function doctype($type = 'xhtml1-strict')
	{
		global $_doctypes;

		if ( ! is_array($_doctypes))
		{
			if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/doctypes.php'))
			{
				include(APPPATH.'config/'.ENVIRONMENT.'/doctypes.php');
			}
			elseif (is_file(APPPATH.'config/doctypes.php'))
			{
				include(APPPATH.'config/doctypes.php');
			}

			if ( ! is_array($_doctypes))
			{
				return FALSE;
			}
		}

		if (isset($_doctypes[$type]))
		{
			return $_doctypes[$type];
		}
		else
		{
			return FALSE;
		}
	}
}

// --------------------------