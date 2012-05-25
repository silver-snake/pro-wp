<?php
class Flotheme_Plugin_Pagination
{
	public function init()
	{
		add_action('admin_menu', array($this, 'addToMenu'));
	}

	public function addToMenu()
	{
		add_submenu_page('flotheme', 'Flosites Page Numbers Options', 'Page Numbers', 10, __FILE__, array($this, 'settings'));
	}

	public function render(array $settings = array())
	{
		$settings = is_array($settings) ? $settings : array();
		$start = "";
		$end = "";
		global $wp_query;
		global $max_page;
		global $paged;
		if ( !$max_page ) { $max_page = $wp_query->max_num_pages; }
		if ( !$paged ) { $paged = 1; }

		$options = get_option('flo_page_numbers_array');
		$options = is_array($options) ? $options : array();
		$settings = array_merge($options, $settings);
		$page_of_page = $settings["page_of_page"];
		$page_of_page_text = $settings["page_of_page_text"];
		$page_of_of = $settings["page_of_of"];

		$next_prev_text = $settings["next_prev_text"];
		$show_start_end_numbers = $settings["show_start_end_numbers"];
		$show_page_numbers = $settings["show_page_numbers"];

		$limit_pages = $settings["limit_pages"];
		$nextpage = $settings["nextpage"];
		$prevpage = $settings["prevpage"];
		$startspace = $settings["startspace"];
		$endspace = $settings["endspace"];

		if( $nextpage == "" ) { $nextpage = "&gt;"; }
		if( $prevpage == "" ) { $prevpage = "&lt;"; }
		if( $startspace == "" ) { $startspace = "..."; }
		if( $endspace == "" ) { $endspace = "..."; }

		if($limit_pages == "") { $limit_pages = "10"; }
		elseif ( $limit_pages == "0" ) { $limit_pages = $max_page; }

		if($this->check_num($limit_pages) == true)
		{
			$limit_pages_left = ($limit_pages-1)/2;
			$limit_pages_right = ($limit_pages-1)/2;
		}
		else
		{
			$limit_pages_left = $limit_pages/2;
			$limit_pages_right = ($limit_pages/2)-1;
		}

		if( $max_page <= $limit_pages ) { $limit_pages = $max_page; }

		$pagingString = "<div id='flo_page_numbers'>\n";

		if($next_prev_text != "no")
			$pagingString .= $this->prevpage($paged, $max_page, $prevpage);


		$pagingString .= '<div class="pages">';

		if($page_of_page != "no")
			$pagingString .= $this->page_of_page($max_page, $paged, $page_of_page_text, $page_of_of);


		if( ($paged) <= $limit_pages_left )
		{
			list ($value1, $value2, $page_check_min) = $this->left_side($max_page, $limit_pages, $paged, $pagingString);
			$pagingMiddleString .= $value1;
		}
		elseif( ($max_page+1 - $paged) <= $limit_pages_right )
		{
			list ($value1, $value2, $page_check_min) = $this->right_side($max_page, $limit_pages, $paged, $pagingString);
			$pagingMiddleString .= $value1;
		}
		else
		{
			list ($value1, $value2, $page_check_min) = $this->middle_side($max_page, $paged, $limit_pages_left, $limit_pages_right);
			$pagingMiddleString .= $value1;
		}


		if ($page_check_min == false && $show_start_end_numbers != "no")
		{
			$pagingString .= "<a class='first' href=\"" . get_pagenum_link(1) . "\">1</a>&nbsp;&nbsp;&nbsp;";
		}

		if($show_page_numbers != "no")
			$pagingString .= '<span class="digits">';
		$pagingString .= $pagingMiddleString;

		if ($value2 == false && $show_start_end_numbers != "no")
		{
			$pagingString .= '<span class="sep">&#133;</span>';
			$pagingString .= "&nbsp;&nbsp;&nbsp;<a class='last' href=\"" . get_pagenum_link($max_page) . "\">" . $max_page . "</a>";
		}
		$pagingString .= '</span>';
		$pagingString .= "</div>\n";

		if($next_prev_text != "no")
			$pagingString .= $this->nextpage($paged, $max_page, $nextpage);


		$pagingString .= "</div>\n";

		if($max_page > 1)
			echo $start . $pagingString . $end;
	}

	public function page_of_page($max_page, $paged, $page_of_page_text, $page_of_of)
	{
		$pagingString = "";
		if ( $max_page > 1)
		{
			if($page_of_page_text == "")
				$pagingString .= '<span class="total">Page ';
			else
				$pagingString .= $page_of_page_text . ' ';

			if ( $paged != "" )
				$pagingString .= $paged;
			else
				$pagingString .= 1;

			if($page_of_of == "")
				$pagingString .= ' of ';
			else
				$pagingString .= ' ' . $page_of_of . ' ';
			$pagingString .= floor($max_page) . '</span>';
		}
		return $pagingString;
	}

	public function prevpage($paged, $max_page, $prevpage)
	{
		if( $max_page > 1 && $paged > 1 )
			$pagingString = '<a href="'.get_pagenum_link($paged-1). '" class="prev">'.$prevpage.'</a>';
		else
			$pagingString = '<a href="" onclick="return false;" class="prev prev-inactive">'.$prevpage.'</a>';
		return $pagingString;
	}

	public function check_num($num)
	{
		return ($num%2) ? true : false;
	}

	public function left_side($max_page, $limit_pages, $paged, $pagingString)
	{
		$pagingString = "";
		$page_check_max = false;
		$page_check_min = false;
		if($max_page > 1)
		{
			for($i=1; $i<($max_page+1); $i++)
			{
				if( $i <= $limit_pages )
				{
					if ($paged == $i || ($paged == "" && $i == 1))
						$pagingString .= '<a class="active" href="'.get_pagenum_link($i). '">'.$i.'</a>';
					else
						$pagingString .= '<a href="'.get_pagenum_link($i). '">'.$i.'</a>';
					if ($i == 1)
						$page_check_min = true;
					if ($max_page == $i)
						$page_check_max = true;
				}
			}
			return array($pagingString, $page_check_max, $page_check_min);
		}
	}

	public function middle_side($max_page, $paged, $limit_pages_left, $limit_pages_right)
	{
		$pagingString = "";
		$page_check_max = false;
		$page_check_min = false;
		for($i=1; $i<($max_page+1); $i++)
		{
			if($paged-$i <= $limit_pages_left && $paged+$limit_pages_right >= $i)
			{
				if ($paged == $i)
					$pagingString .= '<a class="active" href="'.get_pagenum_link($i). '">'.$i.'</a>';
				else
					$pagingString .= '<a href="'.get_pagenum_link($i). '">'.$i.'</a>';

				if ($i == 1)
					$page_check_min = true;
				if ($max_page == $i)
					$page_check_max = true;
			}
		}
		return array($pagingString, $page_check_max, $page_check_min);
	}
	//flo_page_numbers_
	function right_side($max_page, $limit_pages, $paged, $pagingString)
	{
		$pagingString = "";
		$page_check_max = false;
		$page_check_min = false;
		for($i=1; $i<($max_page+1); $i++)
		{
			if( ($max_page + 1 - $i) <= $limit_pages )
			{
				if ($paged == $i)
					$pagingString .= '<a class="active" href="'.get_pagenum_link($i). '">'.$i.'</a>';
				else
					$pagingString .= '<a href="'.get_pagenum_link($i). '">'.$i.'</a>';

				if ($i == 1)
					$page_check_min = true;
			}
			if ($max_page == $i)
				$page_check_max = true;

		}
		return array($pagingString, $page_check_max, $page_check_min);
	}

	function nextpage($paged, $max_page, $nextpage)
	{
		if( $paged != "" && $paged < $max_page)
			$pagingString = '<a href="'.get_pagenum_link($paged+1). '" class="next">'.$nextpage.'</a>';
		else
			$pagingString = '<a href="" onclick="return false;" class="next next-inactive">'.$nextpage.'</a>';
		return $pagingString;
	}

	function settings()
	{
		if(isset($_POST['submitted'])) {
			if($_POST["page_of_page"] == "")
				$_POST["page_of_page"] = "no";
			if($_POST["next_prev_text"] == "")
				$_POST["next_prev_text"] = "no";
			if($_POST["show_start_end_numbers"] == "")
				$_POST["show_start_end_numbers"] = "no";
			if($_POST["show_page_numbers"] == "")
				$_POST["show_page_numbers"] = "no";
			if($_POST["style_theme"] == "")
				$_POST["style_theme"] = "default";

			$settings = array (
				"page_of_page"						=> $_POST["page_of_page"],
				"page_of_page_text"					=> $_POST["page_of_page_text"],
				"page_of_of"						=> $_POST["page_of_of"],
				"next_prev_text"					=> $_POST["next_prev_text"],
				"show_start_end_numbers"			=> $_POST["show_start_end_numbers"],
				"show_page_numbers"					=> $_POST["show_page_numbers"],
				"limit_pages"						=> $_POST["limit_pages"],
				"nextpage"							=> $_POST["nextpage"],
				"prevpage"							=> $_POST["prevpage"],
				"startspace"						=> $_POST["startspace"],
				"endspace"							=> $_POST["endspace"],
				"style_theme"						=> $_POST["style_theme"],
			);
			update_option('flo_page_numbers_array', $settings);

			echo "<div id=\"message\" class=\"updated fade\"><p><strong>Flosites Page Numbers plugin options updated.</strong></p></div>";
		}

		$settings = get_option('flo_page_numbers_array');

		$page_of_page = $settings["page_of_page"];
		$page_of_page_text = $settings["page_of_page_text"];
		$page_of_of = $settings["page_of_of"];

		$next_prev_text = $settings["next_prev_text"];
		$show_start_end_numbers = $settings["show_start_end_numbers"];
		$show_page_numbers = $settings["show_page_numbers"];

		$limit_pages = $settings["limit_pages"];

		$nextpage = $settings["nextpage"];
		$prevpage = $settings["prevpage"];
		$startspace = $settings["startspace"];
		$endspace = $settings["endspace"];

		?>
	<form method="post" name="options" target="_self">

		<div class="wrap">
			<h2>Settings - Text</h2>
			<table style="width: 100%;" border="0">
				<tr>
					<td style="width: 400px;"><strong>Default text: </strong>Page</td>
					<td colspan="3">
						<input name="page_of_page_text" type="text" value="<?php echo $page_of_page_text; ?>" />
					</td>
				</tr>
				<tr>
					<td><strong>Default text: </strong>of</td>
					<td colspan="3">
						<input name="page_of_of" type="text" value="<?php echo $page_of_of; ?>" />
					</td>
				</tr>
				<tr>
					<td><strong>Default text: </strong>&lt;</td>
					<td colspan="3">
						<input name="prevpage" type="text" value="<?php echo $prevpage; ?>" />
					</td>
				</tr>
				<tr>
					<td><strong>Default text: </strong>...</td>
					<td colspan="3">
						<input name="startspace" type="text" value="<?php echo $startspace; ?>" />
					</td>
				</tr>
				<tr>
					<td><strong>Default text: </strong>...</td>
					<td colspan="3">
						<input name="endspace" type="text" value="<?php echo $endspace; ?>" />
					</td>
				</tr>
				<tr>
					<td><strong>Default text: </strong>&gt;</td>
					<td colspan="3">
						<input name="nextpage" type="text" value="<?php echo $nextpage; ?>" />
					</td>
				</tr>
			</table>
		</div>

		<div class="wrap">
			<h2>Settings - show / hide</h2>
			<table style="width: 100%;" border="0">
				<tr>
					<td style="width: 400px;"><strong>Show page info</strong></td>
					<td>
						<input type="checkbox" name="page_of_page" <?php
                    if($page_of_page == "on" || $page_of_page == "") {
							echo 'checked="checked"';
						}
							?>/> Page 3 of 5
					</td>
				</tr>
				<tr>
					<td><strong>Show next / previous page text</strong></td>
					<td>
						<input type="checkbox" name="next_prev_text" <?php
			if($next_prev_text == "on" || $next_prev_text == "")
						{
							echo 'checked="checked"';
						}
							?>/> &lt; &gt;
					</td>
				</tr>

				<tr>
					<td><strong>Show start and end numbers</strong></td>
					<td>
						<input type="checkbox" name="show_start_end_numbers" <?php
			if($show_start_end_numbers == "on" || $show_start_end_numbers == "")
						{
							echo 'checked="checked"';
						}
							?>/> 1... ...5
					</td>
				</tr>

				<tr>
					<td><strong>Show page numbers</strong></td>
					<td>
						<input type="checkbox" name="show_page_numbers" <?php
			if($show_page_numbers == "on" || $show_page_numbers == "")
						{
							echo 'checked="checked"';
						}
							?>/>
					</td>
				</tr>
			</table>
		</div>

		<div class="wrap">
			<h2>Settings - Misc</h2>
			<table style="width: 100%;" border="0">
				<tr>
					<td style="width: 400px;"><strong>Number of pages to show: </strong>10 (0 = unlimited)</td>
					<td colspan="3">
						<input name="limit_pages" type="text" value="<?php echo $limit_pages; ?>" />
					</td>
				</tr>
			</table>
		</div>

		<div class="wrap">
			<p class="submit">
				<input name="submitted" type="hidden" value="yes" />
				<input type="submit" class="button-primary" name="Submit" value="Update Settings &raquo;" />
			</p>
	</form>
	</div><?php
}
}

function flotheme_pagination(array $settings = array())
{
	static $object;
	if (!is_object($object))
		$object = new Flotheme_Plugin_Pagination($settings);
	return $object->render();
}