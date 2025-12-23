<?php 
//pagination
function iteck_pagination($pages = '', $range = 2){  
	$showitems = ($range * 2)+1;  

	global $paged;
	if(empty($paged)) { $paging = 1; } else { $paging = $paged; };

	if($pages == ''){
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if(!$pages){$pages = 1;}
	}   

	if(1 != $pages){
		echo "<ul class='pagination'>";
		if($paging > 2 && $paging > $range+1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link(1)."'>&laquo;</a></li>";
		if($paging > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paging - 1)."'>&lsaquo;</a>";

		for ($i=1; $i <= $pages; $i++){
			if (1 != $pages &&( !($i >= $paging+$range+1 || $i <= $paging-$range-1) || $pages <= $showitems )){
			  echo wp_kses_post($paging == $i)? "<li class='active'><a href='#' >".$i."</a></li>":"<li><a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a></li>";
			} 
		}

		if ($paging < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paging + 1)."'>&rsaquo;</a></li>";  
		if ($paging < $pages-1 &&  $paging+$range-1 < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($pages)."'>&raquo;</a></li>";
		echo "</ul>\n";
	}
}
