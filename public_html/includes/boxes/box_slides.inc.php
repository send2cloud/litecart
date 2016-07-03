<?php
  $box_slides_cache_id = cache::cache_id('box_slides', array('language'));
  if (cache::capture($box_slides_cache_id, 'file')) {

    $slides_query = database::query(
      "select * from ". DB_TABLE_SLIDES ."
      where status
      and (language_code = '' or language_code = '". database::input(language::$selected['code']) ."')
      and (date_valid_from <= '". date('Y-m-d H:i:s') ."')
      and (year(date_valid_to) < '1971' or date_valid_to >= '". date('Y-m-d H:i:s') ."')
      order by priority asc;"
    );

    if (database::num_rows($slides_query)) {

      $box_slides = new view();

      $box_slides->snippets['slides'] = array();

      while ($slide = database::fetch($slides_query)) {
        $box_slides->snippets['slides'][] = array(
          'id' => $slide['link'],
          'link' => $slide['link'],
          'image' => WS_DIR_IMAGES . $slide['image'],
          'caption' => $slide['caption'],
        );
      }

      echo $box_slides->stitch('views/box_slides');
    }

    cache::end_capture($box_slides_cache_id);
  }
?>